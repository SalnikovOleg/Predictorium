<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Trading\CategoryController;
use App\Http\Controllers\Trading\EventController;
use App\Http\Controllers\Trading\TournamentController;
use App\Http\Controllers\Web\HomeController;
use App\Http\Controllers\Web\NavController;
use App\Http\Controllers\Web\SteamGamesController;
use Illuminate\Support\Facades\Route;

Route::get('/ping', function () {
    return response()->json(['status' => true, 'message' => 'pong']);
});

/**** Web Structure */
Route::get('/main-menu', NavController::class)->name('api.main-menu');
Route::get('/home', [HomeController::class, 'index'])->name('api.home');
Route::get('/what_to_play', [SteamGamesController::class, 'what_to_play'])->name('api.what_to_play');

/**** Trading */
Route::get('/categories/{slug}', [CategoryController::class, 'show'])->name('api.categories.show');
Route::get('/tournaments/{tournamentId}', [TournamentController::class, 'show'])->name('api.tournaments.show');
Route::get('/events/{eventId}', [EventController::class, 'show'])->name('api.events.show');

/**** Auth  */
Route::post('/auth/register', [AuthController::class, 'register'])->name('api.auth.register');
Route::post('/auth/login', [AuthController::class, 'login'])->name('api.auth.login');

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/auth/logout', [AuthController::class, 'logout'])->name('api.auth.logout');
    Route::get('/auth/me', [AuthController::class, 'me'])->name('api.auth.me');
});
