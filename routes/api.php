<?php

use App\Http\Controllers\ApiController;
use App\Http\Controllers\TestWebSocketController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


// Route::post('/processApi', [TestWebSocketController::class, 'hitung']);


// Route::post('/getBalance', [ApiController::class, 'getBalance']);
// Route::post('/getAllMember', [ApiController::class, 'getAllMember']);

Route::post('/login', [ApiController::class, 'login']);
Route::post('/register', [ApiController::class, 'register']);

Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('/testroute', function () {
        return 'test';
    });
    Route::post('/logout', [ApiController::class, 'logout']);
    Route::get('/getBalance', [ApiController::class, 'getBalance']);
    Route::post('/getAllMember', [ApiController::class, 'getAllMember']);
    Route::post('/processApi', [ApiController::class, 'savePalceBet']);

    Route::get('/listGame', [ApiController::class, 'listGame']);
});