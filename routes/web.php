<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CharacterController;

Route::get('/', function () {
    return view('login');
});

Route::view('/register', 'register');
Route::view('/login', 'login');
Route::view("logout", "logout");


Route::post('/register-api', [AuthController::class, 'register']);
Route::post('/login-api', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name("logout");
    Route::get('/characters', [CharacterController::class, 'getCharacters']);
    Route::get('/characters/{id}', [CharacterController::class, 'getCharacterById']);
    Route::post('/favorites', [CharacterController::class, 'saveFavorite']);
    Route::get('/favorites', [CharacterController::class, 'getFavorites']);
    Route::delete('/favorites/{id}', [CharacterController::class, 'deleteFavorite']);
});
