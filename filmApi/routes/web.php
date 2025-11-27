<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FilmController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CriticController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/films', [FilmController::class, 'index']);
Route::get('/films/{film}/actors', [FilmController::class, 'actors']);
Route::get('/films/{film}/with-critics', [FilmController::class, 'showWithCritics']);
Route::post('/users', [UserController::class, 'store']);
Route::put('/users/{user}', [UserController::class, 'update']);
Route::delete('/critics/{critic}', [CriticController::class, 'destroy']);
Route::get('/films/{film}/average-score', [FilmController::class, 'averageScore']);
Route::get('/users/{user}/preferred-language', [UserController::class, 'preferredLanguage']);
Route::get('/films/search', [FilmController::class, 'search']);
