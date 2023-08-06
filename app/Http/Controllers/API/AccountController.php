<?php

namespace App\Http\Controllers\API;

use App\Models\Account;
use App\Services\AccountService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AccountController extends Controller
{
    
    public function index()
    {
        $account = Account::all();
        return response()->json([
            'account' => $account
        ]);
    }

    
    public function store(Request $request)
    {
        //
    }

    public function viewAllAccounts(Request $request)
    {   
        return $this->accountService->viewAllAccounts($request);
    }
    public function show($id)
    {
        //
    }

    
    public function update(Request $request, $id)
    {
        //
    }

    
    public function destroy($id)
    {
        //
    }
}
