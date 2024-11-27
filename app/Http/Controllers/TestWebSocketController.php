<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Events\CountdownEvent;
use App\Events\GetBalanceByUsernameEvent;
use App\Models\User;
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
    
    // public function hitung(Request $request) {
    //     $token = $this->checkAuthentication($request->bearerToken());
    //     if (!$token) {
    //         return response()->json(['status' => 'Failed','message' => 'Unauthorized'], 401);
    //     }
       
    //     $dataRequset = $request->toArray();
    //     $periodNo = $dataRequset["periodno"];
    //     $periodData = Period::where('periodno', $periodNo)->first();
    //     $periodId = $periodData->id;
        
    //     foreach ($dataRequset['data'] as $d) {
    //         PeriodBet::create([
    //             'period_id' => $periodId,
    //             'periodno' => $periodNo,
    //             'username' => $d['username'],
    //             'mc' => $d['mc'],
    //             'head_mc' => $d['head_mc'],
    //             'body_mc' => $d['body_mc'],
    //             'leg_mc' => $d['leg_mc'],
    //             'bm' => $d['bm'],
    //             'head_bm' => $d['head_bm'],
    //             'body_bm' => $d['body_bm'],
    //             'leg_bm' => $d['leg_bm']
    //         ]);
    //     }
        
    //     $bodyData = [
    //         "periodno" => $periodNo,
    //         "data" => $dataRequset["data"]
    //     ];
    //     $response = Http::withHeaders([
    //         'Authorization' => 'Bearer ' . env('TOKEN_CALCULATOR'), 
    //     ])->post('http://192.168.3.247:8100/api/v1/ax0l0tl', $bodyData);
    
    //     if ($response->successful()) {
    //         $data = $response->json();
    //         $dataWin = [];
    //         $periodData->update(['gamestatus' => 2]);
    //         foreach($data['result']['win'] as $index => $dt) {
    //             $amount_win = $dt['mc'] + $dt['head_mc'] + $dt['body_mc'] + $dt['leg_mc'] + $dt['bm'] + $dt['head_bm'] + $dt['body_bm'] + $dt['leg_bm'];
    //             $dataWin[$index] = [
    //                 'username' => $dt['username'],
    //                 'amount_win' => $amount_win
    //             ];

    //             $dataPeriodBet = PeriodBet::where('periodno', $data["periodno"])->where('username', $dt["username"])->first();
    //             $dataPeriodBet->update([
    //                 'amount_win' => $amount_win,
    //                 'gamestatus' => 2
    //             ]);

    //             if($amount_win > 0) {
    //                 PeriodWin::create([
    //                     'period_bet_id' => $dataPeriodBet->id,
    //                     'periodno' => $data["periodno"],
    //                     'username' => $dt["username"],
    //                     'mc' => $dt["mc"],
    //                     'head_mc' => $dt['head_mc'],
    //                     'body_mc' => $dt['body_mc'],
    //                     'leg_mc' => $dt['leg_mc'],
    //                     'bm' => $dt['bm'],
    //                     'head_bm' => $dt['head_bm'],
    //                     'body_bm' => $dt['body_bm'],
    //                     'leg_bm' => $dt['leg_bm']
    //                 ]);
    //             }
    //         }

    //         $results = [
    //             "periodno" => $periodNo,
    //             'dataWin' => $dataWin
    //         ];

    //         return response()->json($results);
            
    //     } else {
    //         return response()->json(['status' => 'Failed','message' => 'Unauthorized'], 401);
    //     }
    // }

   
    // public function generatePeriodBetNo()
    // {
    //     $year = now()->format('y'); 
    //     $month = now()->format('m'); 

    //     $lastPeriodBet = PeriodBet::where('periodno', 'like', "P{$year}{$month}%")
    //         ->orderBy('periodno', 'desc')
    //         ->first();

    //     if ($lastPeriodBet) {
    //         $lastNumber = (int)substr($lastPeriodBet->periodno, -6);
    //         $newNumber = str_pad($lastNumber + 1, 6, '0', STR_PAD_LEFT);
    //     } else {
    //         $newNumber = '000001';
    //     }

    //     return "P{$year}{$month}{$newNumber}";
    // }

    

}
