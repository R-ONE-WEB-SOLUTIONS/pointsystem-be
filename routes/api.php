<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\UserController;
use App\Http\Controllers\API\ClientController;
use App\Http\Controllers\API\AccountController;
use App\Http\Controllers\API\BusinessController;
use App\Http\Controllers\API\UserTypeController;
use App\Http\Controllers\API\TransactionController;


// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::post('/login', [AuthController::class, 'login']);

Route::group(['middleware' => ['auth:sanctum']], function() {

    //Auth
    Route::apiResource('auth', AuthController::class);
    Route::get('/logout', [AuthController::class, 'logout']);

    //bussiness
    Route::apiResource('businesses', BusinessController::class);

    // User Type
    Route::apiResource('userType', UserTypeController::class);
    

    //User
    Route::apiResource('users', UserController::class);
    Route::get('/searchUsers', [UserController::class, 'searchUsers']);
    Route::post('/viewUsersByBID',[UserController::class, 'viewUsersByBID']);

    //Pre Reg

    //Client Type

    //Client
    Route::apiResource('clients', ClientController::class);
    Route::get('/searchClients', [ClientController::class, 'searchClients']);
    Route::put('/activateClient/{client}', [ClientController::class, 'activateClient']);
    Route::post('/viewClientsByBID',[ClientController::class, 'viewClientsByBID']);

    //Account
    Route::apiResource('accounts', AccountController::class);

    //
    
    //Transactions
    Route::post('/rewardPoints', [TransactionController::class, 'rewardPoints']);

    //Point Calculation
});