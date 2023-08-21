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

            $originalDataBinary = hex2bin($account);
            $account_number = utf8_decode($originalDataBinary);
           

            $acc = Account::where('account_number', $account_number)->firstOrFail();
            return response()->json([
                'message' => 'Account found',
                'account_number' => $acc->account_number,
                'name' => $acc->client->first_name .' '. ($acc->client->middle_name ? $acc->clients->middle_name. ' ' : null). $acc->client->last_name . ' '. ($acc->client->extension_name ? $acc->clients->extension_name: null)
               
            ]);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => "Account Not Found"], 404);
        }
        catch (\ErrorException $e) {
            return response()->json(['error' => "Qr Code not valid"], 404);
        }

    }
}
