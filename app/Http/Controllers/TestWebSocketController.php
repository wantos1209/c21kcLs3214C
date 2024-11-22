<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Events\CountdownEvent;
use App\Models\Member;
use App\Models\Period;
use App\Models\PeriodBet;
use App\Models\PeriodWin;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class TestWebSocketController extends Controller
{
    //
    public function index(Request $request) {
        try {
            // broadcast(new CountdownEvent(60));
    
            return view('welcome', [
                'title' => 'Analytics',
                'userid' => 'wantos',
            ]);
        } catch (\Exception $e) {
            return response()->view('welcome', [
                'message' => $e->getMessage()
            ], 500);
        }
    }

    
    public function savePalceBet(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'periodno' => 'required|string|max:255',
            'username' => 'required|string|max:255',
            'mc' => 'nullable|numeric|min:0',
            'head_mc' => 'nullable|numeric|min:0',
            'body_mc' => 'nullable|numeric|min:0',
            'leg_mc' => 'nullable|numeric|min:0',
            'bm' => 'nullable|numeric|min:0',
            'head_bm' => 'nullable|numeric|min:0',
            'body_bm' => 'nullable|numeric|min:0',
            'leg_bm' => 'nullable|numeric|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json([
               'status' => 'Failed',
                'message' => 'Validation error: ' . implode(', ', $validator->errors()->all()),
            ], 422);
        }

        $dataPeriod = Period::where('periodno', $request->periodno)->where('statusgame', 1)->first();
        if(!$dataPeriod) {
            return response()->json([
                'status' => 'Failed',
                 'message' => 'Period not found or already completed',
             ], 422);
        }

        $dataMember = Member::where('username', $request->username)->first();
        if(!$dataMember) {
            return response()->json([
                'status' => 'Failed',
                 'message' => 'Member not found. Please check the username and try again.',
             ], 422);
        } 

        $totalBet = $request->mc + $request->head_mc + $request->body_mc + $request->leg_mc + $request->bm + $request->head_bm + $request->body_bm + $request->leg_bm;
        if($totalBet >  $dataMember->balance) {
            return response()->json([
                'status' => 'Failed',
                'message' => 'Insufficient balance.',
            ], 422);
        } 

        if($totalBet == 0) {
            return response()->json([
                'status' => 'Failed',
                'message' => 'Invalid bet.',
            ], 422);
        }

        $cekDataPeriodBet = PeriodBet::where('periodno', $request->periodno)->where('username', $request->username)->first();
        if($cekDataPeriodBet) {
            return response()->json([
                'status' => 'Failed',
                'message' => 'You have already placed a bet for this period.',
            ], 422);
        }

        $periodBet = new PeriodBet();
        $periodBet->period_id = $dataPeriod->id;
        $periodBet->periodno = $request->periodno;
        $periodBet->username = $request->username;
        $periodBet->mc = $request->mc ?? 0;
        $periodBet->head_mc = $request->head_mc ?? 0;
        $periodBet->body_mc = $request->body_mc ?? 0;
        $periodBet->leg_mc = $request->leg_mc ?? 0;
        $periodBet->bm = $request->bm ?? 0;
        $periodBet->head_bm = $request->head_bm ?? 0;
        $periodBet->body_bm = $request->body_bm ?? 0;
        $periodBet->leg_bm = $request->leg_bm ?? 0;

        if ($periodBet->save()) {
            DB::transaction(function () use ($periodBet, $dataMember, $totalBet) {
                $dataMember->refresh(); 
                if ($dataMember->balance >= $totalBet) {
                    $dataMember->update([
                        'balance' => $dataMember->balance - $totalBet,
                        'periodno' => $periodBet->periodno,
                        'statusgame' => 1
                    ]);
                } else {
                    throw new \Exception('Insufficient balance.');
                }
            });
        } 

        return response()->json([
            'message' => 'Period bet created successfully!',
            'data' => $periodBet,
        ], 201);
    }

    public function hitung(Request $request) {
        $token = $this->checkAuthentication($request->bearerToken());
        if (!$token) {
            return response()->json(['status' => 'Failed','message' => 'Unauthorized'], 401);
        }
       
        $dataRequset = $request->toArray();
        $periodNo = $dataRequset["periodno"];
        $periodData = Period::where('periodno', $periodNo)->first();
        $periodId = $periodData->id;
        
        foreach ($dataRequset['data'] as $d) {
            PeriodBet::create([
                'period_id' => $periodId,
                'periodno' => $periodNo,
                'username' => $d['username'],
                'mc' => $d['mc'],
                'head_mc' => $d['head_mc'],
                'body_mc' => $d['body_mc'],
                'leg_mc' => $d['leg_mc'],
                'bm' => $d['bm'],
                'head_bm' => $d['head_bm'],
                'body_bm' => $d['body_bm'],
                'leg_bm' => $d['leg_bm']
            ]);
        }
        
        $bodyData = [
            "periodno" => $periodNo,
            "data" => $dataRequset["data"]
        ];
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . env('TOKEN_CALCULATOR'), 
        ])->post('http://192.168.3.247:8100/api/v1/ax0l0tl', $bodyData);
    
        if ($response->successful()) {
            $data = $response->json();
            $dataWin = [];
            $periodData->update(['gamestatus' => 2]);
            foreach($data['result']['win'] as $index => $dt) {
                $amount_win = $dt['mc'] + $dt['head_mc'] + $dt['body_mc'] + $dt['leg_mc'] + $dt['bm'] + $dt['head_bm'] + $dt['body_bm'] + $dt['leg_bm'];
                $dataWin[$index] = [
                    'username' => $dt['username'],
                    'amount_win' => $amount_win
                ];

                $dataPeriodBet = PeriodBet::where('periodno', $data["periodno"])->where('username', $dt["username"])->first();
                $dataPeriodBet->update([
                    'amount_win' => $amount_win,
                    'gamestatus' => 2
                ]);

                if($amount_win > 0) {
                    PeriodWin::create([
                        'period_bet_id' => $dataPeriodBet->id,
                        'periodno' => $data["periodno"],
                        'username' => $dt["username"],
                        'mc' => $dt["mc"],
                        'head_mc' => $dt['head_mc'],
                        'body_mc' => $dt['body_mc'],
                        'leg_mc' => $dt['leg_mc'],
                        'bm' => $dt['bm'],
                        'head_bm' => $dt['head_bm'],
                        'body_bm' => $dt['body_bm'],
                        'leg_bm' => $dt['leg_bm']
                    ]);
                }
            }

            $results = [
                "periodno" => $periodNo,
                'dataWin' => $dataWin
            ];

            return response()->json($results);
            
        } else {
            return response()->json(['status' => 'Failed','message' => 'Unauthorized'], 401);
        }
    }

    public function checkAuthentication ($token) {
        $tokenEnv = env('TOKEN_BEARER');
        if($token === $tokenEnv) {
            return true;
        } else {
            return false;
        }
    }

    public function generatePeriodBetNo()
    {
        $year = now()->format('y'); 
        $month = now()->format('m'); 

        $lastPeriodBet = PeriodBet::where('periodno', 'like', "P{$year}{$month}%")
            ->orderBy('periodno', 'desc')
            ->first();

        if ($lastPeriodBet) {
            $lastNumber = (int)substr($lastPeriodBet->periodno, -6);
            $newNumber = str_pad($lastNumber + 1, 6, '0', STR_PAD_LEFT);
        } else {
            $newNumber = '000001';
        }

        return "P{$year}{$month}{$newNumber}";
    }

    public function getBalance(Request $request)
    {
        $token = $this->checkAuthentication($request->bearerToken());
        if (!$token) {
            return response()->json(['status' => 'Failed','message' => 'Unauthorized'], 401);
        }

        $username = $request->username;

        if (!$username) {
            return response()->json([
                'status' => 'Failed',
                'message' => 'Username cannot be empty'
            ], 400); 
        }

        $data = Member::where('username', $username)->first();
        return response()->json([
            'status' => 'Success',
            'message' => 'Balance retrieved successfully',
            'data' => $data
        ]);
    }

    public function getAllMember(Request $request)
    {
        $token = $this->checkAuthentication($request->bearerToken());
        if (!$token) {
            return response()->json(['status' => 'Failed','message' => 'Unauthorized'], 401);
        }

        $data = Member::orderBy('created_at', 'DESC')->get();
        return response()->json([
            'status' => 'Success',
            'message' => 'All member retrieved successfully',
            'data' => $data
        ]);
    }

}
