<?php

namespace App\Http\Controllers\API;

use Exception;
use Carbon\Carbon;
use App\Models\Account;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Services\TransactionService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class TransactionController extends Controller
{

    public $transactionService;

    public function __construct(TransactionService $transactionService){
        $this->transactionService = $transactionService;
    }

    public function rewardPoints (Request $request) {
        $validator = Validator::make($request->all(), [
            'reciept_number' => 'required|unique:transactions',
            'reciept_amount' => 'required|numeric',
            'business_id' => 'required',
            'account' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()]);
        }
        $user_id = Auth::id();
        return $this->transactionService->rewardPoints($request, $user_id);
    }

    public function checkBalance (Request $request) {
        $validatedData = $request->validate([
            'account_number' => 'required',
        ]);

        
        try {
            $acc = Account::where('account_number', $validatedData['account_number'])->firstOrFail();
            return response()->json([
                'message' => 'Account found',
                'balance' => $acc->current_balance,
                'name' => $acc->client->first_name .' '. ($acc->client->middle_name !== null ? $acc->client->middle_name.' ' : null). $acc->client->last_name . ' '. ($acc->client->extension_name ? $acc->clients->extension_name: null)
               
            ]);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => "Account Not Found"], 404);
        }

    }

    public function claimPoints (Request $request) {
        $validatedData = $request->validate([
            'account_number' => 'required',
            'points_to_claim' => 'required'
        ]);
        
        try {
            $acc = Account::where('account_number', $validatedData['account_number'])->firstOrFail();
            
            $request->reciept_number == null ? $reciept_number = $acc->id . '_' . time() : $reciept_number = $request->reciept_number;
            $request->reciept_amount == null ? $reciept_amount = 0.00 : $reciept_amount = $request->reciept_amount;
            $rewardPoint = $validatedData['points_to_claim'];
            
            if($acc->current_balance < $validatedData['points_to_claim']) {
                return response()->json([
                    'error' => 'Not enough points.',
                    'current_balance' => $acc->current_balance
                ]);
            }
            $newPoints = ($acc->current_balance - $validatedData['points_to_claim']);
            
            DB::beginTransaction();

        
            try {
                $newTransaction = Transaction::create([
                    'reference_id' => $acc->id . '_' . time(),
                    'reciept_number' => $reciept_number,
                    'reciept_amount' => $reciept_amount,
                    'points' => $rewardPoint,
                    'user_id' => Auth::id(),
                    'account_id' => $acc->id,
                    'transaction_type' => 'Claim Points',
                    'previous_balance' => $acc->current_balance,
                    'void' => 0
                ]);

                $acc ->update(['current_balance' => $newPoints]);

                DB::commit();

                return response()->json([
                    'message' => 'Point successfully recorded.',
                    'name' => $acc->client->first_name .' '. ($acc->client->middle_name !== null ? $acc->client->middle_name.' ' : null). $acc->client->last_name . ' '. ($acc->client->extension_name ? $acc->clients->extension_name: null),
                    'account_number' => $acc->account_number,
                    'transaction_reference_id' => $newTransaction->reference_id,
                    'transaction_points' => $newTransaction->points,
                    'transaction_type' => $newTransaction->transaction_type,
                    'reciept_amount' => $reciept_amount,
                    'previous_balance' => $newTransaction->previous_balance,
                    'new_balance' => $acc->current_balance,
                ], 200);

            }catch(Exception $e){
                DB::rollback();
                return $e;
                return response()->json(['error' => $e], 400);
            }
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => "Account Not Found"], 404);
        }






    }
    
    public function index()
    {
        $transanctions = Transaction::all();
        return response()->json([
            'message' => 'transactions found',
            'transanctions' => $transanctions
        ],200);
    }

    public function viewAllTransactions(Request $request)
    {
        if($request->business_id != null){

            $transactions = Transaction::select('transactions.*','client_types.client_type','clients.first_name','clients.middle_name','clients.last_name', 'businesses.id as business_id', 'businesses.business_name as business_name')
                            ->join('accounts', 'transactions.account_id', '=', 'accounts.id')
                            ->join('clients', 'accounts.client_id', '=', 'clients.id')
                            ->join('client_types', 'client_types.id', '=', 'clients.client_type_id')
                            ->join('businesses', 'clients.business_id', '=', 'businesses.id')
                            ->where('businesses.id', $request->business_id)
                            ->orderBy('transactions.created_at', 'desc')
                            ->get();
            
        }else{
            $transactions = Transaction::select('transactions.*','client_types.client_type', 'clients.first_name','clients.middle_name','clients.last_name','businesses.id as business_id', 'businesses.business_name as business_name')
                            ->join('accounts', 'transactions.account_id', '=', 'accounts.id')
                            ->join('clients', 'accounts.client_id', '=', 'clients.id')
                            ->join('client_types', 'client_types.id', '=', 'clients.client_type_id')
                            ->join('businesses', 'clients.business_id', '=', 'businesses.id')
                            ->orderBy('transactions.created_at', 'desc')
                            ->get();
            
        }

        

        if($transactions->isEmpty()){
            return response()->json(['message' => 'No transactions found'], 200);
        }else{
            
            return response()->json(['message' => 'transactions found', 'transactions' => $transactions], 200);
        }
        
    }

    
    public function store(Request $request)
    {
        //
    }

    
    public function show($id)
    {
        $transactions = Transaction::where('account_id', '=', $id)->orderBy('transactions.created_at', 'desc')->get();

        if($transactions->isEmpty()){
            return response()->json(['message' => 'No transactions found'], 200);
        }else{
            return response()->json(['message' => 'transactions found', 'transactions' => $transactions], 200);
        }

        

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
