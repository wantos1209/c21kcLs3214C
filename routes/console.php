<?php

use App\Events\CountdownEvent;
use App\Models\Member;
use App\Models\Period;
use App\Models\PeriodBet;
use App\Models\PeriodWin;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;

Artisan::command('broadcast:countdown', function () {
    while (true) {
        if (file_exists(storage_path('app/stop_broadcast'))) {
            $this->info('Broadcast stopped.');
            unlink(storage_path('app/stop_broadcast'));
            break;
        }
        
        // Buat periode baru
        $period = Period::create([]);

        // Hitung mundur 10 detik
        broadcastCountdown($period);

        // Ambil data taruhan
        $bodyData = prepareBodyData($period);

        try {
            // Kirim data ke API eksternal
            $response = sendDataToApi($bodyData);

            if ($response->successful() && isset($response['result']['win_state'])) {
                handleApiResponse($response->json(), $period);
            } else {
                broadcastFailure('Failed to fetch data from API.');
            }
        } catch (\Exception $e) {
            broadcastFailure('An error occurred while fetching data.');
        }

        sleep(13); // Jeda 13 detik sebelum iterasi berikutnya
        broadcastFinish($response->json(), $period);
        sleep(3);
    }
})->purpose('Broadcast countdown event every 10 seconds');


function broadcastCountdown($period)
{
    for ($i = 10; $i >= 0; $i--) {
        $data = [
            'status' => 'Success',
            'statusBet' => $i === 10 ? 'Open bet' : ($i === 0 ? 'Close bet' : ''),
            'message' => 'Data successfully',
            'periodno' => $period->periodno,
            'result' => '',
            'is_countdown' => false
        ];

        if ($i === 10 || $i === 0) {
            broadcast(new CountdownEvent($data));
        }

        if($i === 3){
            broadcastAlmostClose($period);
        }

        sleep(1);
    }

    // Update status game setelah hitung mundur selesai
    $period->update(['statusgame' => 2]);
}

function prepareBodyData($period)
{
    $dataBet = PeriodBet::where('periodno', $period->periodno)->get();

    if ($dataBet->isEmpty()) {
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

    broadcast(new CountdownEvent($results));

    foreach ($data['result']['win'] as $dataWin) {
        saveWinData($dataWin, $data['periodno']);
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

    DB::transaction(function () use ($dataWin) {
        $totalWin = $dataWin['mc'] + $dataWin['head_mc'] + $dataWin['body_mc'] +
                    $dataWin['leg_mc'] + $dataWin['bm'] + $dataWin['head_bm'] +
                    $dataWin['body_bm'] + $dataWin['leg_bm'];

        $dataMember = Member::where('username', $dataWin['username'])->firstOrFail();
        $dataMember->increment('balance', $totalWin);
    });
}

function broadcastFailure($message)
{
    $results = [
        'status' => 'Failed',
        'message' => $message
    ];

    broadcast(new CountdownEvent($results));
}

function broadcastFinish($data, $period)
{
    $results = [
        'status' => 'Success',
        'statusBet' => 'Finish bet',
        'message' => 'Data successfully fetched',
        'periodno' => $period->periodno,
        'result' => $data['result']['win_state'],
        'is_countdown' => false
    ];

    broadcast(new CountdownEvent($results));
}


function broadcastAlmostClose($period)
{
    $results = [
        'status' => 'Success',
        'statusBet' => 'Almost close',
        'message' => 'Data successfully fetched',
        'periodno' => $period->periodno,
        'result' => '',
        'is_countdown' => true
    ];

    broadcast(new CountdownEvent($results));
}