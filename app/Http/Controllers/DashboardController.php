<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index ()
    {
        dd(session()->all());
        return view ('dashboard.index', [
            'title' => 'Dashboard'
        ]);
    }
}
