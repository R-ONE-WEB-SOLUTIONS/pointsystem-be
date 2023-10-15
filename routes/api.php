<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\UserController;
use App\Http\Controllers\API\ClientController;
use App\Http\Controllers\API\PreRegController;
use App\Http\Controllers\API\AccountController;
use App\Http\Controllers\API\BusinessController;
use App\Http\Controllers\API\UserTypeController;
use App\Http\Controllers\API\ClientTypeController;
use App\Http\Controllers\API\TransactionController;
use App\Http\Controllers\API\PointCalculationController;


// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::post('/login', [AuthController::class, 'login']);

Route::group(['middleware' => ['auth:sanctum']], function() {
    Route::post('/getGraphDetails', [UserController::class, 'graphDetails']);

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

    Route::post('/viewAllUsers',[UserController::class, 'viewAllUsers']);

    //Pre Reg
    Route::apiResource('preReg', PreRegController::class);
    Route::post('/viewAllPreReg',[PreRegController::class, 'viewAllPreReg']);
    Route::put('/applicantStatus/{preReg}',[PreRegController::class, 'applicantStatus']);

    //Client Type
    Route::apiResource('clientType', ClientTypeController::class);

    //Client
    Route::apiResource('clients', ClientController::class);
    Route::get('/searchClients', [ClientController::class, 'searchClients']);
    Route::put('/activateClient/{client}', [ClientController::class, 'activateClient']);
    Route::post('/viewAllClients',[ClientController::class, 'viewAllClients']);

    //Account
    Route::apiResource('accounts', AccountController::class);
    Route::post('/viewAllAccounts',[AccountController::class, 'viewAllAccounts']);
    Route::post('/scanAccount',[AccountController::class, 'scanAccount']);

    //

    //Transactions
    Route::apiResource('transactions', TransactionController::class);
    Route::post('/rewardPoints', [TransactionController::class, 'rewardPoints']);
    Route::post('/checkBalance', [TransactionController::class, 'checkBalance']);
    Route::post('/claimPoints', [TransactionController::class, 'claimPoints']);
    Route::put('/voidTransaction/{transaction}', [TransactionController::class, 'voidTransaction']);
    Route::post('/viewAllTransactions', [TransactionController::class, 'viewAllTransactions']);

    //Point Calculation
    Route::apiResource('pointCalculation', PointCalculationController::class);
    Route::post('/viewPointCalculationByBusiness', [PointCalculationController::class, 'viewPointCalculationByBusiness']);
});


