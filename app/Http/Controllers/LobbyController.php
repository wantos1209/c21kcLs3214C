<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LobbyController extends Controller
{
    public function lobby()
    {
        dd(Auth::user());
        if (session()->has('user')) {
            $user = session('user');
        } else {
            $user = [
                "name" => "anonymus",
                "balance" => "0.00",
            ];
        }

        return view('lobbygame.index', [
            'title' => 'Analytics',
            'user' => $user,
        ]); 
    }
}
