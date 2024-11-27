<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LobbyController extends Controller
{
    public function lobby()
    {
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
