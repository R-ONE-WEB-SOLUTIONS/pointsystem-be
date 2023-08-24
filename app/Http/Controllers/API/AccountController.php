<?php

namespace App\Http\Controllers\API;

use App\Models\Account;
use Illuminate\Http\Request;
use App\Services\AccountService;
use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class AccountController extends Controller
{
    private $accountService;
    public function __construct(AccountService $accountService){
        $this->accountService = $accountService;
    }
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

    public function scanAccount(Request $request)
    {

        $validatedData = $request->validate([
            'account_number' => 'required',
        ]);


        return $acc = $this->getAccountInfo($validatedData['account_number']);
        


        
    }

    
    public function update(Request $request, $id)
    {
        //
    }

    
    public function destroy($id)
    {
        //
    }

    private function getAccountInfo($account){
        try {
            $acc = Account::where('account_number', $account)->firstOrFail();
            return response()->json([
                'message' => 'Account found',
                'account_number' => $acc->account_number,
                'business_id' => $acc->client->business_id,
                'business_name' => $acc->client->business->business_name,
                'card_type' => $acc->client->clientType->client_type,
                'name' => $acc->client->first_name .' '. ($acc->client->middle_name !== null ? $acc->client->middle_name.' ' : ''). $acc->client->last_name . ' '. ($acc->client->extension_name ? $acc->clients->extension_name: null),
                'current_balance' => $acc->current_balance
            ]);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => "Account Not Found: ". $account], 404);
        }
        catch (\ErrorException $e) {
            return response()->json(['error' => "Qr Code not valid: ". $account], 404);
        }

    }
}
