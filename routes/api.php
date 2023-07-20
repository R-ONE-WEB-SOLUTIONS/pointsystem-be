<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\UserController;
use App\Http\Controllers\API\ClientController;


Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::apiResource('users', UserController::class);
Route::get('/searchUsers', [UserController::class, 'searchUsers']);
Route::apiResource('clients', ClientController::class);
Route::get('/searchClients', [ClientController::class, 'searchClients']);
Route::put('/activateClient/{client}', [ClientController::class, 'activateClient']);