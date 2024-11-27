<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LobbyController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\TestWebSocketController;
use App\Http\Middleware\RedirectIfAuthenticated;
use App\Http\Middleware\VerifyApiToken;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;


// Route::get('/', function () {
//     if (Auth::check()) {
//         return redirect('/lobbygame');
//     }
//     abort(404);
// });

Route::get('/', function () {
    dd(Auth::user());
});

// Route::get('/', [TestWebSocketController::class, 'index']);
Route::get('/test', [TestWebSocketController::class, 'testfunction']);

Route::get('/loginlobby', [LoginController::class, 'indexLobby'])->name('login')->middleware([RedirectIfAuthenticated::class]);
Route::post('/loginlobby', [LoginController::class, 'authenticateLobby']);

Route::get('/login', [LoginController::class, 'index'])->name('login')->Middleware('guest');
Route::post('/login', [LoginController::class, 'authenticate']);



Route::get('/dashboard', function () {
    return 'test';
})->middleware('auth');

// Route::middleware(['auth'])->group(function () {
//     Route::get('/dashboard', [DashboardController::class, 'index']);
//     Route::get('/logout', [LoginController::class, 'logout']);
// });


Route::middleware([VerifyApiToken::class])->group(function () {
    Route::get('/logoutlobby', [LoginController::class, 'logoutLobby']);
    Route::get('/lobbygame', [LobbyController::class, 'lobby']); 
});


Route::get('/set-session', function () {
    session(['key' => 'value']);
    return 'Session set!';
});

Route::get('/get-session', function () {
    return session('key', 'default_value');
});