<?php

namespace App\Services;

use App\Models\Client;
use App\Models\Account;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;
use App\Rules\CreateClientValidation;
use App\Rules\UpdateClientValidation;
use Illuminate\Support\Facades\Hash;

use Illuminate\Support\Facades\DB;
class ClientService {


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

    public function viewAllClients($request) {
        if($request->business_id != null){
            $clients = Client::where('business_id', '=', $request->business_id)
            ->leftJoin('client_types', 'clients.client_type_id', '=', 'client_types.id')
            ->leftJoin('businesses', 'clients.business_id', '=', 'businesses.id')
            ->join('accounts', 'clients.id', '=', 'accounts.client_id')
            ->select('accounts.account_number','clients.*', 'client_types.client_type', 'businesses.business_name')
            ->get();

            if ($clients->isEmpty()) {
                return response()->json(['message' => 'No Clients found'], 200);
            }
            // $clients['account_number'] = Hash::make($clients['account_number']);
            return response()->json($clients, 200);
        }else{
            $clients = Client::leftJoin('client_types', 'clients.client_type_id', '=', 'client_types.id')
            ->leftJoin('businesses', 'clients.business_id', '=', 'businesses.id')
            ->join('accounts', 'clients.id', '=', 'accounts.client_id')
            ->select('accounts.account_number','clients.*', 'client_types.client_type', 'businesses.business_name')
            ->get();

            if ($clients->isEmpty()) {
                return response()->json(['message' => 'No Clients found'], 200);
            }
            // $clients['account_number']  = Hash::make($clients['account_number']);
            return response()->json($clients, 200);
        }




    }

    public function createClient($request) {

        $validator = Validator::make($request->all(), [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'extension_name' => 'nullable|string|max:255',
            'email' => [
                'string',
                'email',
                'max:255',
                new CreateClientValidation($request->business_id),
            ],
            'phone_number' => 'required|string|max:11',
            'address' => 'required|string|max:255',
            // 'client_type_id' => 'required',
            'business_id' => 'required',
            'active' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        $client = Client::create($request->all());

        $newClientId = $client->id;

        // Generate a random account number based on client_id, timestamp, and current balance (initially set to 0)
        $accountNumber =  date("Ymd",time()). $client->id;

        $account = Account::create([
            'client_id' => $client->id,
            'account_number' => $accountNumber,
            'current_balance' => 0,
        ]);



            if ($client->client_type_id == 1 && $client->business_id == 1) {

                $client->update(['expiry_date' => now()->addYears(3)->setTime(23, 59, 59)]);
            } elseif ($client->client_type_id == 2 && $client->business_id == 1) {

                $client->update(['expiry_date' => now()->addYears(6)->setTime(23, 59, 59)]);
            }
            else{
                $client->update(['expiry_date' => now()->addYears(3)->setTime(23, 59, 59)]);

            }

        return response()->json(['message' => 'client created successfully', 'client' => $client], 200);


    }

    public function searchClients($request) {

        $clients = Client::where('first_name', 'LIKE', '%' . $request->search . '%')
                    ->orWhere('last_name', 'LIKE', '%' . $request->search . '%')
                    ->orWhere('email', 'LIKE', '%' . $request->search . '%')
                    ->orWhere('phone_number', 'LIKE', '%' . $request->search . '%')
                    ->orWhere('id', 'LIKE', '%' . $request->search . '%')
                    ->orWhere('middle_name', 'LIKE', '%' . $request->search . '%')
                    ->orWhere('extension_name', 'LIKE', '%' . $request->search . '%')
                    ->get();

        if (!$clients) {
            return response()->json(['error' => 'Clients not found'], 404);
        }

        return response()->json(['clients' => $clients], 200);

    }

    public function editClient($request, $id) {

        $client = Client::findOrFail($id);

        $validatedData = $request->validate([
            'first_name' => 'string|max:255',
            'last_name' => 'string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'extension_name' => 'nullable|string|max:255',
            'email' => [
                'email',
                new UpdateClientValidation($request->id,$request->business_id),
            ],
            'phone_number' => 'string|max:20',
            'address' => 'string|max:255',
            'active' => 'boolean',
            // 'client_type_id' => 'integer',
            'business_id' => 'required',
        ]);

        // Filter out only the columns that are present in the validated data
        // $fillableData = array_filter($validatedData, function ($value) {
        //     return $value !== null;
        // });
        if ($client->client_type_id == 1 && $client->business_id == 1) {

            $client->update(['expiry_date' => now()->addYears(3)->setTime(23, 59, 59)]);
        } elseif ($client->client_type_id == 2 && $client->business_id == 1) {

            $client->update(['expiry_date' => now()->addYears(6)->setTime(23, 59, 59)]);
        }
        else{
            $client->update(['expiry_date' => now()->addYears(3)->setTime(23, 59, 59)]);

        }
        $client->update($validatedData);

        return response()->json([
            'message' => 'Client updated successfully.',
            'client' => $client
        ]);
    }

    public function deactivateClient($id) {
        $client = Client::findOrFail($id);

        $client->update(['active' => 0]);

        return response()->json([
            'message' => 'Client updated successfully.',
            'client' => $client
        ]);
    }

    public function activateClient($id) {
        $client = Client::findOrFail($id);

        $client->update(['active' => 1]);

        return response()->json([
            'message' => 'Client updated successfully.',
            'client' => $client
        ]);
    }

    public function renewClient($id) {
        $client = Client::findOrFail($id);
        $expiryDate = new \DateTime($client->expiry_date); // Assuming expiry_date is a field in your Client model
        $today = new \DateTime();


        if ($expiryDate > $today) {

            // Client's expiry date is more than today, update the client's active status
            if ($client->client_type_id == 1 && $client->business_id == 1) {

                $client->update(['active' => 1, 'expiry_date' => date('Y-m-d', strtotime($client->expiry_date . ' + ' . 3 . ' years'))]);
            } elseif ($client->client_type_id == 2 && $client->business_id == 1) {

                $client->update(['active' => 1, 'expiry_date' => date('Y-m-d', strtotime($client->expiry_date . ' + ' . 6 . ' years'))]);
            }


            return response()->json([
                'message' => 'Client updated successfully.',
                'client' => $client
            ]);
        } else {
            if ($client->client_type_id == 1 && $client->business_id == 1) {

                $client->update(['active' => 1, 'expiry_date' => now()->addYears(3)->setTime(23, 59, 59)]);
            } elseif ($client->client_type_id == 2 && $client->business_id == 1) {

                $client->update(['active' => 1, 'expiry_date' => now()->addYears(3)->setTime(23, 59, 59)]);
            }
            return response()->json([
                'message' => 'Client updated successfully.',
                'client' => $client
            ]);

        }
    }
}
