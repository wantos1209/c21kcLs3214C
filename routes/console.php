<?php

use App\Events\CountdownEvent;
use App\Events\GetBalanceByUsernameEvent;
use App\Models\Member;
use App\Models\User;
use App\Models\Period;
use App\Models\PeriodBet;
use App\Models\PeriodWin;
use Carbon\Carbon;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;

Artisan::command('broadcast:countdown', function () {
    $this->info("Starting Open/Close broadcasting. Press Ctrl + C to stop.");
    $periodno = '';
    $dataPeriod = [];
    $step = 0;

    while (true) {
        $currentTime = Carbon::now();
        $currentSecond = (int)$currentTime->format('s');
        
        if ($currentSecond % 20 === 0) {
            $period = Period::create([]);
            $periodno = $period->periodno;

            // Get last 10 data period
            $dataPeriod = Period::latest()->first();

            $step = 1;
        }

        $data = [
            'status' => 'Success',
            'message' => 'Data successfully',
            'periodno' => $periodno,
            'result' => '',
            'is_countdown' => false,
            'timeCD' => $currentTime->toDateTimeString(),
            'statusBet' => 'Open bet'
        ];
    
        $isOpenBet = $currentSecond % 20 < 10; 
        
        if ($isOpenBet) {
            if ($step === 1) {
                $data["lastPeriod"] = $dataPeriod;
                $data["countDown"] = 10 - ($currentSecond % 10);  
                
                if ($currentSecond % 10 < 8) {
                    $data["state_anim"] = 1;  
                } else {
                    $data["state_anim"] = 2;  
                }
                
                try {
                    broadcast(new CountdownEvent($data));
                } catch (\Exception $e) {
                    \Log::error('Error broadcasting CountdownEvent: ' . $e->getMessage());
                }
            }
        } else {
            if ($step === 1) {
                $data["statusBet"] = 'Close bet';
                $data["state_anim"] = 3;  

                $result = '';
                
                if ($currentSecond % 20 === 10) {
                    $period->update(['statusgame' => 2]);
                    try {
                        broadcast(new CountdownEvent($data));
                    } catch (\Exception $e) {
                        \Log::error('Error broadcasting CountdownEvent: ' . $e->getMessage());
                    }
                    $result = postCalc($period);
                }

                if ($currentSecond % 20 == 13) {
                    $data["state_anim"] = 4;
                    broadcast(new CountdownEvent($data));
                }
                
                if ($currentSecond % 20 == 16) {
                    broadcastFinish($result, $period);
                }
            }
        }
        sleep(1);
    }

})->purpose('Broadcast countdown event every 10 seconds');

function postCalc($period)
{
    $bodyData = prepareBodyData($period);

    try {
        $response = sendDataToApi($bodyData);

        if ($response->successful()) {
            $responseData = $response->json();
            if (isset($responseData['result']['win_state'])) {
                handleApiResponse($responseData, $period);
            } else {
                broadcastFailure('Invalid API response structure.');
            }
        } else {
            \Log::error('API response error: ' . $response->status());
            broadcastFailure('Failed to fetch data from API.');
        }
    } catch (\Exception $e) {
        \Log::error('API call exception: ' . $e->getMessage());
        broadcastFailure('An error occurred while fetching data.');
    }
}

function prepareBodyData($period)
{
    $dataBet = PeriodBet::where('periodno', $period->periodno)->get();

    if ($dataBet->isEmpty()) {
        \Log::warning('No bets found for period: ' . $period->periodno);
        return [
            "periodno" => $period->periodno,
            "data" => []
        ];
    }

    return [
        "periodno" => $period->periodno,
        "data" => $dataBet->map(function ($bet) {
            return [
                'username' => $bet->username,
                'mc' => $bet->mc,
                'head_mc' => $bet->head_mc,
                'body_mc' => $bet->body_mc,
                'leg_mc' => $bet->leg_mc,
                'bm' => $bet->bm,
                'head_bm' => $bet->head_bm,
                'body_bm' => $bet->body_bm,
                'leg_bm' => $bet->leg_bm,
            ];
        })->toArray()
    ];
}

function sendDataToApi($bodyData)
{
    return Http::withHeaders([
        'Authorization' => 'Bearer ' . env('TOKEN_CALCULATOR'),
    ])->post('http://192.168.3.247:8100/api/v1/ax0l0tl', $bodyData);
}

function handleApiResponse($data, $period)
{
    $results = [
        'status' => 'Success',
        'statusBet' => 'Result bet',
        'message' => 'Data successfully fetched',
        'periodno' => $period->periodno,
        'result' => $data['result']['win_state'],
        'is_countdown' => false
    ];

    Period::where('periodno', $period->periodno)->update([
        'win_state' => $data['result']['win_state'],
        'statusgame' => 2
    ]);

    try {
        broadcast(new CountdownEvent($results));
    } catch (\Exception $e) {
        \Log::error('Error broadcasting CountdownEvent: ' . $e->getMessage());
    }

    //update statusgame pada member
    Member::where('periodno', $period->periodno)->update([
        'statusgame' => 2
    ]);

    foreach ($data['result']['win'] as $dataWin) {
        saveWinData($dataWin, $data['periodno']);
        $resultsBalance = [
            'status' => 'Success',
            'message' => 'Data successfully fetched',
            'username' => $dataWin['username'],
            'balance' => Member::where('username', $dataWin['username'])->first()->balance
        ];
        try {
            broadcast(new GetBalanceByUsernameEvent($resultsBalance, $dataWin['username']));
        } catch (\Exception $e) {
            \Log::error('Error broadcasting GetBalanceByUsernameEvent: ' . $e->getMessage());
        }
    }
}

function saveWinData($dataWin, $periodno)
{
    $dataBet = PeriodBet::where('periodno', $periodno)
        ->where('username', $dataWin['username'])
        ->firstOrFail();

    PeriodWin::create([
        'period_bet_id' => $dataBet->id,
        'periodno' => $dataBet->periodno,
        'username' => $dataBet->username,
        'mc' => $dataWin['mc'],
        'head_mc' => $dataWin['head_mc'],
        'body_mc' => $dataWin['body_mc'],
        'leg_mc' => $dataWin['leg_mc'],
        'bm' => $dataWin['bm'],
        'head_bm' => $dataWin['head_bm'],
        'body_bm' => $dataWin['body_bm'],
        'leg_bm' => $dataWin['leg_bm'],
    ]);

    try {
        DB::transaction(function () use ($dataWin) {
            $totalWin = $dataWin['mc'] + $dataWin['head_mc'] + $dataWin['body_mc'] +
                        $dataWin['leg_mc'] + $dataWin['bm'] + $dataWin['head_bm'] +
                        $dataWin['body_bm'] + $dataWin['leg_bm'];
            
            $dataUser = Member::where('username', $dataWin['username'])->firstOrFail();
            $dataUser->increment('balance', $totalWin);
        });
    } catch (\Exception $e) {
        \Log::error('Error saving win data: ' . $e->getMessage());
    }
}

function broadcastFailure($message)
{
    $results = [
        'status' => 'Failed',
        'message' => $message
    ];

    try {
        broadcast(new CountdownEvent($results));
    } catch (\Exception $e) {
        \Log::error('Error broadcasting failure: ' . $e->getMessage());
    }
}

function broadcastFinish($result, $period)
{
    $results = [
        'status' => 'Success',
        'statusBet' => 'Finish bet',
        'message' => 'Data successfully fetched',
        'periodno' => $period->periodno,
        'result' => $result,
        'is_countdown' => false
    ];

    try {
        broadcast(new CountdownEvent($results));
    } catch (\Exception $e) {
        \Log::error('Error broadcasting Finish event: ' . $e->getMessage());
    }
}
