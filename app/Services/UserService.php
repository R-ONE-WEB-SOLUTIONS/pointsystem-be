<?php

namespace App\Services;

use Exception;
use App\Models\User;
use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;


class UserService {

    public function __construct() {

    }

    public function createUser($request) {
        
        $validator = Validator::make($request->all(), [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'extension_name' => 'nullable|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'phone_number' => 'required|string|max:11',
            'address' => 'required|string|max:255',
            'user_type_id' => 'required',
            'business' => 'nullable',
            'roles' => 'nullable',
        ]);
    
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        $data = $request->all();
        $data['password'] = Hash::make($data['password']);
    
        $user = User::create($data);
    
        return response()->json(['message' => 'User created successfully', 'user' => $user], 200);

    
    }

    public function viewAllUsers($request) {

        if($request->business_id != null){
            $users = User::where('business_id', '=', $request->business_id)
            ->leftJoin('user_types', 'users.user_type_id', '=', 'user_types.id')
            ->leftJoin('businesses', 'users.business_id', '=', 'businesses.id')
            ->select('users.*', 'user_types.user_type', 'businesses.business_name')
            ->get();

            if ($users->isEmpty()) {
                return response()->json(['message' => 'No users found'], 200);
            }

            return response()->json($users, 200);
        }else{
            $users = User::leftJoin('user_types', 'users.user_type_id', '=', 'user_types.id')
            ->leftJoin('businesses', 'users.business_id', '=', 'businesses.id')
            ->select('users.*', 'user_types.user_type', 'businesses.business_name')
            ->get();

            if ($users->isEmpty()) {
                return response()->json(['message' => 'No users found'], 200);
            }

            return response()->json($users, 200);
        }
        
      
    }

    

    public function searchUsers($request) {

        $users = User::where('first_name', 'LIKE', '%' . $request->search . '%')
                    ->orWhere('last_name', 'LIKE', '%' . $request->search . '%')
                    ->orWhere('email', 'LIKE', '%' . $request->search . '%')
                    ->get();

        if (!$users) {
            return response()->json(['error' => 'User not found'], 404);
        }

        return response()->json(['users' => $users], 200);
       
    }
    
    public function viewUser($id) {

        $user = User::where('users.id', '=', $id)
        ->leftJoin('user_types', 'users.user_type_id', '=', 'user_types.id')
        ->leftJoin('businesses', 'users.business_id', '=', 'businesses.id')
        ->select('users.*', 'user_types.user_type', 'businesses.business_name')
        ->first();;

        if (!$user) {
            return response()->json(['error' => 'User not found'], 404);
        }

        return response()->json(['user' => $user], 200);
    }

    public function editUser($request, $id) {
        
        $user = User::findOrFail($id);

        $validatedData = $request->validate([
            'first_name' => 'string|max:255',
            'last_name' => 'string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'extension_name' => 'nullable|string|max:255',
            'email' => [
                'email',
                Rule::unique('users')->ignore($user, 'id'),
            ],
            'password' => 'nullable|string|min:6',
            'phone_number' => 'string|max:20',
            'user_type_id' => 'nullable',
            'business_id' => 'nullable',
            'address' => 'string|max:255',
            'roles' => 'nullable',
        ]);

        // Filter out only the columns that are present in the validated data
        // $fillableData = array_filter($validatedData, function ($value) {
        //     return $value !== null;
        // });

        $user->update($validatedData);

        return response()->json([
            'message' => 'User updated successfully.',
            'user' => $user
        ]);
    }

    public function deleteUser($id) {
        $user = User::findOrFail($id);
    
        $user->delete();

        return response()->json([
            'message' => 'User deleted successfully.'
        ],200);
    }


    public function getGraphDetails($request) {
        if($request->business_id != null){
            $clients = Client::where('business_id', '=', $request->business_id)
            ->leftJoin('client_types', 'clients.client_type_id', '=', 'client_types.id')
            ->leftJoin('businesses', 'clients.business_id', '=', 'businesses.id')
            ->join('accounts', 'clients.id', '=', 'accounts.client_id')
            ->select('accounts.account_number','clients.*', 'client_types.client_type', 'businesses.business_name')
            ->get();

            $users = User::where('business_id', '=', $request->business_id)
            ->leftJoin('user_types', 'users.user_type_id', '=', 'user_types.id')
            ->leftJoin('businesses', 'users.business_id', '=', 'businesses.id')
            ->select('users.*', 'user_types.user_type', 'businesses.business_name')
            ->get();
            
            $data = [
                'clients' => $clients,
                'users' => $users
            ];

            // $clients['account_number'] = Hash::make($clients['account_number']);
            return response()->json($data, 200);
        }else{
            $clients = Client::leftJoin('client_types', 'clients.client_type_id', '=', 'client_types.id')
            ->leftJoin('businesses', 'clients.business_id', '=', 'businesses.id')
            ->join('accounts', 'clients.id', '=', 'accounts.client_id')
            ->select('accounts.account_number','clients.*', 'client_types.client_type', 'businesses.business_name')
            ->get();

            $users = User::leftJoin('user_types', 'users.user_type_id', '=', 'user_types.id')
            ->leftJoin('businesses', 'users.business_id', '=', 'businesses.id')
            ->select('users.*', 'user_types.user_type', 'businesses.business_name')
            ->get();

            $data = [
                'clients' => $clients,
                'users' => $users
            ];
          
            // $clients['account_number']  = Hash::make($clients['account_number']);
            return response()->json($data, 200);
        }
        
      
    }



}