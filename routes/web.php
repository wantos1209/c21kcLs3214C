<?php

use App\Http\Controllers\TestWebSocketController;
use Illuminate\Support\Facades\Route;

Route::get('/', [TestWebSocketController::class, 'index']);