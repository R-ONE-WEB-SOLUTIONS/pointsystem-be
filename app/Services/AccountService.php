<?php

namespace App\Services;

use App\Models\Client;
use App\Models\Account;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;


class AccountService {


    public function __construct() {

    }

    // public function viewClients() {
    //     $clients = Client::leftJoin('client_types', 'clients.client_type_id', '=', 'client_types.id')
    //         ->leftJoin('businesses', 'clients.business_id', '=', 'businesses.id')
    //         ->select('clients.*', 'client_types.client_type', 'businesses.business_name')
    //         ->get();
    //     if ($clients->isEmpty()) {
    //         return response()->json(['message' => 'No Client found'], 200);
    //     }

    //     return response()->json($clients, 200);
    // }

    public function viewAllAccounts($request) {
        if($request->business_id != null){
            $Accounts = Account::
            join('clients', 'clients.id', '=', 'accounts.client_id')
            ->join('client_types', 'client_types.id', '=', 'clients.client_type_id')
            ->join('businesses', $request->business_id, '=', 'businesses.id')
            ->where('clients.business_id', $request->business_id)
            ->select('accounts.*', 'client_types.client_type', 'businesses.business_name')
            ->get();

            if ($clients->isEmpty()) {
                return response()->json(['message' => 'No Clients found'], 200);
            }

            return response()->json($clients, 200);
        }else{
            $Accounts = Account::join('clients', 'clients.id', '=', 'accounts.client_id')
            ->join('client_types', 'client_types.id', '=', 'clients.client_type_id')
            ->join('businesses', $request->business_id, '=', 'businesses.id')
            ->select('clients.*', 'client_types.client_type', 'businesses.business_name')
            ->get();

            if ($Accounts->isEmpty()) {
                return response()->json(['message' => 'No Clients found'], 200);
            }

            return response()->json($Accounts, 200);
        }
        
      
    }

}