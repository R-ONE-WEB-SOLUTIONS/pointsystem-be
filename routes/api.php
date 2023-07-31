<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\UserController;
use App\Http\Controllers\API\ClientController;
use App\Http\Controllers\API\TransactionController;


// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::post('/login', [AuthController::class, 'login']);

Route::group(['middleware' => ['auth:sanctum']], function() {
    Route::apiResource('auth', AuthController::class);
    Route::apiResource('users', UserController::class);
    Route::get('/logout', [AuthController::class, 'logout']);
    Route::get('/searchUsers', [UserController::class, 'searchUsers']);
    Route::apiResource('clients', ClientController::class);
    Route::get('/searchClients', [ClientController::class, 'searchClients']);
    Route::put('/activateClient/{client}', [ClientController::class, 'activateClient']);
    Route::post('/rewardPoints', [TransactionController::class, 'rewardPoints']);
});