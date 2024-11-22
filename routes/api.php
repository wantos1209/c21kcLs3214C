<?php

use App\Http\Controllers\TestWebSocketController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


// Route::post('/processApi', [TestWebSocketController::class, 'hitung']);
Route::post('/processApi', [TestWebSocketController::class, 'savePalceBet']);

Route::post('/getBalance', [TestWebSocketController::class, 'getBalance']);
Route::post('/getAllMember', [TestWebSocketController::class, 'getAllMember']);
