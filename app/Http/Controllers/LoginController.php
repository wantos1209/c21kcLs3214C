<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\RedirectResponse;

class LoginController extends Controller
{
    public function index()
    {
        return view('login.index', [
            'title' => 'login',
        ]);
    }

    public function authenticate(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'username' => ['required'],
            'password' => ['required'],
        ]);
 
        if (Auth::guard('web')->attempt($credentials)) {
            $request->session()->put('user_id', Auth::user()->id);
            $request->session()->regenerate();
            return redirect()->intended('dashboard');
        }
 
        return back()->withErrors([
            'username' => 'The provided credentials do not match our records.',
        ])->onlyInput('username');
    }

    public function logout()
    {

        Auth::logout();

        request()->session()->invalidate();

        request()->session()->regenerateToken();

        request()->session()->forget('pin_validated');

        return redirect('/x314cz9kc141DDX');
    }

    public function indexLobby()
    {
        return view('login.indexlobby', [
            'title' => 'Login',
        ]);
    }

    public function authenticateLobby(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'username' => 'required|string',
            'password' => 'required|string',
        ]);
        
        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput(); 
        }

        $bearerToken = env('TOKEN_BEARER');

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $bearerToken,
        ])->post('http://127.0.0.1:8003/api/login', [
            'username' => $request->username,
            'password' => $request->password,
        ]);

        if ($response->successful()) {
            $data = $response->json(); 
            session(['api_token' => $data['data']['token']]); 
            session(['user' => $data['data']['user']]); 

            return redirect()->intended('/lobbygame');
        }

        return back()->with('loginError', 'Log in failed!');
    }

    public function logoutLobby(Request $request)
    {
        $token = session('api_token');

        if ($token) {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $token,
            ])->post('http://192.168.3.244:8003/api/logout');

            if ($response->successful()) {
                session()->forget('api_token');
                session()->forget('user');
                
                return redirect('/loginlobby'); 
            } else {
                return response()->json([
                    'status' => 'Failed',
                    'message' => 'Logout failed from external API',
                ], 500);
            }
        }

        return response()->json([
            'status' => 'Failed',
            'message' => 'No token found for logout',
        ], 401);
    }
}
