<?php

namespace App\Services;

use App\Models\Client;
use App\Models\Account;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;
use App\Rules\CreateClientValidation;
use App\Rules\UpdateClientValidation;


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
            ->select('clients.*', 'client_types.client_type', 'businesses.business_name')
            ->get();

            if ($clients->isEmpty()) {
                return response()->json(['message' => 'No Clients found'], 200);
            }

            return response()->json($clients, 200);
        }else{
            $clients = Client::leftJoin('client_types', 'clients.client_type_id', '=', 'client_types.id')
            ->leftJoin('businesses', 'clients.business_id', '=', 'businesses.id')
            ->select('clients.*', 'client_types.client_type', 'businesses.business_name')
            ->get();

            if ($clients->isEmpty()) {
                return response()->json(['message' => 'No Clients found'], 200);
            }

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
                'required',
                'string',
                'email',
                'max:255',
                new CreateClientValidation($request->business_id),
            ],
            'phone_number' => 'required|string|max:11',
            'address' => 'required|string|max:255',
            'client_type_id' => 'required',
            'business_id' => 'required',
            'active' => 'required',
        ]);
    
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }
    
        $client = Client::create($request->all());

        $newClientId = $client->id;

        // Generate a random account number based on client_id, timestamp, and current balance (initially set to 0)
        $accountNumber =  date("Ymd",time()) . '_'. $client->id;

        $account = Account::create([
            'client_id' => $client->id,
            'account_number' => $accountNumber,
            'current_balance' => 0,
        ]);
    
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
            'client_type_id' => 'integer'
        ]);

        // Filter out only the columns that are present in the validated data
        // $fillableData = array_filter($validatedData, function ($value) {
        //     return $value !== null;
        // });

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
}