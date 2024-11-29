<?php

namespace App\Http\Controllers;

use App\Events\GetBalanceByUsernameEvent;
use App\Models\Game;
use App\Models\Member;
use App\Models\Period;
use App\Models\PeriodBet;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class ApiController extends Controller
{
    public function login(Request $request)
    {
        $token = $this->checkAuthentication($request->bearerToken());
        if (!$token) {
            return response()->json(['status' => 'Failed', 'message' => 'Unauthorized'], 401);
        }

        // Validasi input login (username dan password)
        $validator = Validator::make($request->all(), [
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'Failed',
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $credentials = $request->only('username', 'password');

        $member = Member::where('username', $credentials['username'])->first();

        if (!$member || !Hash::check($credentials['password'], $member->password)) {
            return response()->json([
                'status' => 'Failed',
                'message' => 'Login failed'
            ], 401);
        }

        $token = $member->createToken('auth_token')->plainTextToken;

        return response()->json([
            'status' => 'Success',
            'message' => 'Login successful',
            'data' => [
                'user' => $member,
                'token' => $token
            ]
        ]);
    }

    public function register(Request $request)
    {
        $token = $this->checkAuthentication($request->bearerToken());
        if (!$token) {
            return response()->json(['status' => 'Failed', 'message' => 'Unauthorized'], 401);
        }
        
        $validator = Validator::make($request->all(), [
            'username' => 'required|string|unique:member,username', 
            'password' => 'required|string|min:6', 
            'referral' => 'nullable|string',
            'hp' => 'nullable|string',
            'bank' => 'nullable|string',
            'namarek' => 'nullable|string',
            'norek' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'Failed',
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $member = Member::create([
            'username' => $request->username,
            'password' => bcrypt($request->password), 
            'referral' => $request->referral,
            'hp' => $request->hp,
            'bank' => $request->bank,
            'namarek' => $request->namarek,
            'norek' => $request->norek,
            'balance' => $request->balance ?? 0, 
        ]);

        $token = $member->createToken('auth_token')->plainTextToken;

        return response()->json([
            'status' => 'Success',
            'message' => 'Registration successful',
            'data' => [
                'user' => $member,
                'token' => $token
            ]
        ]);
    }

    public function savePalceBet(Request $request)
    {
        // $token = $this->checkAuthentication($request->bearerToken());
        // if (!$token) {
        //     return response()->json(['status' => 'Failed', 'message' => 'Unauthorized'], 401);
        // }

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

        $dataUser = Member::where('username', $request->username)->first();
        if(!$dataUser) {
            return response()->json([
                'status' => 'Failed',
                'message' => 'User not found. Please check the username and try again.',
            ], 422);
        }

        $mc = $request->mc ?? 0;
        $head_mc = $request->head_mc ?? 0;
        $body_mc = $request->body_mc ?? 0;
        $leg_mc = $request->leg_mc ?? 0;
        $bm = $request->bm ?? 0;
        $head_bm = $request->head_bm ?? 0;
        $body_bm = $request->body_bm ?? 0;
        $leg_bm = $request->leg_bm ?? 0;

        $totalBet = $mc + $head_mc + $body_mc + $leg_mc + $bm + $head_bm + $body_bm + $leg_bm;
        if($totalBet > $dataUser->balance) {
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
        $periodBet->mc = $mc;
        $periodBet->head_mc = $head_mc;
        $periodBet->body_mc = $body_mc;
        $periodBet->leg_mc = $leg_mc;
        $periodBet->bm = $bm;
        $periodBet->head_bm = $head_bm;
        $periodBet->body_bm = $body_bm;
        $periodBet->leg_bm = $leg_bm;

        if ($periodBet->save()) {
            try {
                DB::transaction(function () use ($periodBet, $dataUser, $totalBet) {
                    $dataUser->refresh(); 
                    if ($dataUser->balance >= $totalBet) {
                        $dataUser->update([
                            'balance' => $dataUser->balance - $totalBet,
                            'periodno' => $periodBet->periodno,
                            'statusgame' => 1
                        ]);
                    } else {
                        throw new \Exception('Insufficient balance.');
                    }
                });
            } catch (\Exception $e) {
                \Log::error('Transaction failed: ' . $e->getMessage());
                return response()->json([
                    'status' => 'Failed',
                    'message' => 'An error occurred during the transaction. Please try again later.',
                ], 500);
            }
        }

        try {
            $this->broadcastBalance($request->username);
        } catch (\Exception $e) {
            \Log::error('Error broadcasting balance update: ' . $e->getMessage());
            return response()->json([
                'status' => 'Failed',
                'message' => 'Broadcasting failed, but the transaction was successful.',
            ], 500);
        }

        return response()->json([
            'status' => 'Success',
            'message' => 'Period bet created successfully!',
        ], 201);
    }

    public function listGame()
    {
        $dataGame = Game::orderBy('created_at', 'DESC')->get();
        return response()->json([
            'status' => 'Success',
            'message' => 'Data successfully fetched',
            'data' => $dataGame
        ]);
    }

    private function broadcastBalance($username)
    {
        $resultsBalance = [
            'status' => 'Success',
            'message' => 'Data successfully fetched',
            'username' => $username,
            'balance' => Member::where('username', $username)->first()->balance
        ];

        broadcast(new GetBalanceByUsernameEvent($resultsBalance, $username));
    }


    public function getBalance(Request $request)
    {
        // $token = $this->checkAuthentication($request->bearerToken());
        // if (!$token) {
        //     return response()->json(['status' => 'Failed', 'message' => 'Unauthorized'], 401);
        // }
        
        $username = Auth::user()->username;

        if (!$username) {
            return response()->json([
                'status' => 'Failed',
                'message' => 'Username cannot be empty'
            ], 400);
        }

        $data = Member::where('username', $username)->first();
        if (!$data) {
            return response()->json([
                'status' => 'Failed',
                'message' => 'User not found'
            ], 404);
        }

        return response()->json([
            'status' => 'Success',
            'message' => 'Balance retrieved successfully',
            'data' => [
                'username' => $data->username,
                'balance' => $data->balance
            ]
        ]);
    }


    public function getAllUser(Request $request)
    {
        $token = $this->checkAuthentication($request->bearerToken());
        if (!$token) {
            return response()->json(['status' => 'Failed', 'message' => 'Unauthorized'], 401);
        }

        try {
            $data = User::orderBy('created_at', 'DESC')->paginate(100); 

            $dataFormatted = $data->map(function ($member) {
                return [
                    'username' => $member->username,
                    'balance' => $member->balance,
                ];
            });

            return response()->json([
                'status' => 'Success',
                'message' => 'All members retrieved successfully',
                'data' => $dataFormatted,
                'pagination' => [
                    'total' => $data->total(),
                    'current_page' => $data->currentPage(),
                    'per_page' => $data->perPage(),
                    'last_page' => $data->lastPage(),
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'Failed',
                'message' => 'An error occurred while retrieving members: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function checkAuthentication($token)
    {
        if (!$token) {
            return false;
        }
    
        $tokenEnv = env('TOKEN_BEARER');
    
        if (hash_equals($token, $tokenEnv)) {
            return true;
        }
    
        return false;
    }

    public function logout(Request $request)
    {
        $user = $request->user(); 
        $user->currentAccessToken()->delete(); 

        return response()->json([
            'status' => 'Success',
            'message' => 'Logged out successfully'
        ]);
    }
}
