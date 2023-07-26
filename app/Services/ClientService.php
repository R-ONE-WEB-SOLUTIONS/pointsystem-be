<?php

namespace App\Services;

use App\Models\Client;
use App\Models\Account;
use Illuminate\Support\Facades\Validator;


class ClientService {


    public function __construct() {

    }

    public function viewClients() {
        $client = Client::all();

            if ($client->isEmpty()) {
                return response()->json(['message' => 'No Client found'], 200);
            }
    
            return response()->json($client, 200);
    }

    public function createClient($request) {
        
        $validator = Validator::make($request->all(), [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'extension_name' => 'nullable|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
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

    public function viewUser($id) {

        $client = Client::findOrFail($id);

        if (!$client) {
            return response()->json(['error' => 'Client not found'], 404);
        }

        return response()->json(['client' => $client], 200);
    }

    public function editClient($request, $id) {
        
        $client = Client::findOrFail($id);

        $validatedData = $request->validate([
            'first_name' => 'string|max:255',
            'last_name' => 'string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'extension_name' => 'nullable|string|max:255',
            'email' => 'email',
            'phone_number' => 'string|max:20',
            'address' => 'string|max:255',
            'active' => 'boolean',
            'client_type_id' => 'integer'
        ]);

        // Filter out only the columns that are present in the validated data
        $fillableData = array_filter($validatedData, function ($value) {
            return $value !== null;
        });

        $client->update($fillableData);

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