<?php

namespace App\Services;

use Exception;
use App\Models\User;
use Illuminate\Http\Request;
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
            'roles' => 'nullable|json',
        ]);
    
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }
    
        $user = User::create($request->all());
    
        return response()->json(['message' => 'User created successfully', 'user' => $user], 200);

    
    }

    public function viewAllUsers() {

            $users = User::all();

            if ($users->isEmpty()) {
                return response()->json(['message' => 'No users found'], 200);
            }
    
            return response()->json($users, 200);
      
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

        $user = User::find($id);

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
            'password' => 'nullable|string|min:6',
            'phone_number' => 'string|max:20',
            'address' => 'string|max:255',
            'roles' => 'nullable|json',
        ]);

        // Filter out only the columns that are present in the validated data
        $fillableData = array_filter($validatedData, function ($value) {
            return $value !== null;
        });

        $user->update($fillableData);

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



}