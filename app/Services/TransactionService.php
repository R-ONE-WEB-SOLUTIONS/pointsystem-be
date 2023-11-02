<?php

namespace App\Services;

use App\Models\Account;
use App\Models\Transaction;
use App\Models\PointCalculation;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\ModelNotFoundException;


class TransactionService {

    public function __construct() {

    }

    public function rewardPoints($request, $user_id){


        $acc = $this->getAccountInfo($request->account);

        $pointMultiplier = $this->getPointMultiplier($request->business_id);

        $multiplier = $pointMultiplier->multiplier;
        $reciept_amount = (int)$request->reciept_amount;
        $rewardPoint = ($reciept_amount * $multiplier);
        $newPoints = $acc->current_balance + $rewardPoint;


        DB::beginTransaction();


        try {
            $newTransaction = Transaction::create([
                'reference_id' => $acc->id  . time(),
                'reciept_number' => $request->reciept_number,
                'reciept_amount' => $request->reciept_amount,
                'points' => $rewardPoint,
                'user_id' => $user_id,
                'account_id' => $acc->id,
                'transaction_type' => 'Reward Points',
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
                'date_created' => $newTransaction->created_at
            ], 200);

        }catch(\Exception $e){
            DB::rollback();
            return $e;
            return response()->json(['error' => $e], 400);
        }


    }


    private function getAccountInfo($account){

        try {
            return Account::with('client')->where('account_number', $account)->firstOrFail();
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => "Account Not Found"], 404);
        }

    }

    private function getPointMultiplier($business_id){

        try {
            return PointCalculation::where('business_id', '=', $business_id)->first();
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => $e], 404);
        }


    }
}
