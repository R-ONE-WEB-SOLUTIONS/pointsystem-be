<?php

namespace App\Http\Controllers\API;

use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\TransactionService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

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
            'account' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()]);
        }
        // $user_id = Auth::id();
        $user_id = 1;
        return $this->transactionService->rewardPoints($request, $user_id);
    }
    
    public function index()
    {
        $transanctions = Transaction::all();
        return response()->json([
            'transanctions' => $transanctions
        ]);
    }

    
    public function store(Request $request)
    {
        //
    }

    
    public function show($id)
    {
        $transactions = Transaction::where('account_id', '=', $id)->get();

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
