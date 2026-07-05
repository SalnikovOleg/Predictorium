<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Trading\EventController;
use App\Http\Controllers\Trading\MarketController;
use App\Http\Controllers\Trading\TournamentController;
use App\Http\Controllers\WebStructure\NavController;
use Illuminate\Support\Facades\Route;

Route::get('/ping', function () {
    return response()->json(['status' => true, 'message' => 'pong']);
});

/**** Trading */
Route::get('/main-menu', NavController::class)->name('api.main-menu');
Route::get('/categories/{categoryId}/tournaments', TournamentController::class)->name('api.categories.tournaments');
Route::get('/tournaments/{tournamentId}/events', EventController::class)->name('api.tournaments.events');
Route::get('/events/{eventId}/markets', MarketController::class)->name('api.events.markets');

/**** Auth  */
Route::post('/auth/register', [AuthController::class, 'register'])->name('api.auth.register');
Route::post('/auth/login', [AuthController::class, 'login'])->name('api.auth.login');

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/auth/logout', [AuthController::class, 'logout'])->name('api.auth.logout');
    Route::get('/auth/me', [AuthController::class, 'me'])->name('api.auth.me');
});
