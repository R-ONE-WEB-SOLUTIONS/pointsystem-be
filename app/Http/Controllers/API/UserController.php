<?php

namespace App\Http\Controllers\API;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class UserController extends Controller
{
    
    public function viewAllUsers(Request $request) 
    {   
        if($request->business_id != null){

            // search user by business id
            $users = User::where('business_id', '=', $request->business_id)
                    ->with('business:id,business_name')
                    ->with('userType:id,user_type')
                    ->get();
        }else{

            // search all user
            $users = User::with('business:id,business_name')
                    ->with('userType:id,user_type')
                    ->get();
        }
        
        //check if user is not empty
        if ($users->isEmpty()) {
            return response()->json(['error' => 'No user(s) found'], 200);
        }
        
        return response()->json(['message' => 'User(s) found', 'data' => $this->mapusers($users)],200);
    }


    public function store(Request $request)
    {
        // Validate user input
        $validated = $request->validate([
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'middle_name' => ['nullable', 'string', 'max:255'],
            'extension_name' => ['nullable', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8'],
            'phone_number' => ['required', 'string', 'max:11'],
            'address' => ['required', 'string', 'max:255'],
            'user_type_id' => ['required'],
            'business' => ['nullable'],
            'roles' => ['nullable', 'json'],
        ]);

        $user = User::create($request->all());
        
        try {
            $user = User::create($request->all());
            return response()->json(['message' => 'User created successfully', 'data' => $this->mapusers($users)], 200);
        } catch (\Exception $e) {
            // An error occurred during user creation.
            return response()->json(['error' => $e->getMessage()], 500);
        }
            
        
    }

    
    public function show($id)
    {
        
    }

    
    public function update(Request $request, $id)
    {
        try {

            $user = User::findOrFail($id);

            // Validate user input
            $validated = $request->validate([
                'first_name' => ['required', 'string', 'max:255'],
                'last_name' => ['required', 'string', 'max:255'],
                'middle_name' => ['nullable', 'string', 'max:255'],
                'extension_name' => ['nullable', 'string', 'max:255'],
                'email' => [
                    'required'
                    , 'email'
                    , Rule::unique('users')->ignore($user, 'id')
                ],
                'phone_number' => ['required', 'string', 'max:11'],
                'address' => ['required', 'string', 'max:255'],
                'user_type_id' => ['required'],
                'business' => ['nullable'],
                'roles' => ['nullable'],
            ]);

            try {

                $user->update($validated);
                return response()->json(['message' => 'User updated successfully', 'data' => $this->mapuser($user)], 200);

            } catch (\Exception $e) {

                // An error occurred during user creation.
                return response()->json(['error' => $e->getMessage()], 500);
                
            }

        } catch (ModelNotFoundException $e) {

            // An error occurred during user creation.
            
            return response()->json(['error' => "No User Found"], Response::HTTP_NOT_FOUND);
            

        }
        
        
    }

    
    public function destroy($id)
    {
        
    }

    //mapping users to attach business and usertype
    private function mapusers($users) {

        return $users->map(function ($user) {

            return [
                'id' => $user->id,
                'first_name' => $user->first_name,
                'last_name' => $user->last_name,
                'middle_name' => $user->last_name,
                'extension_name' => $user->last_name,
                'address' => $user->address,
                'email' => $user->email,
                'phone_number' => $user->phone_number,
                'roles' => $user->roles,
                'business_name' => $user->business ? $user->business->business_name : null,
                'user_type' => $user->userType ? $user->userType->user_type : null,
            ];
        });
    }

    //mapping user to attach business and usertype
    private function mapuser($user) {

        return [
            'id' => $user->id,
            'first_name' => $user->first_name,
            'last_name' => $user->last_name,
            'middle_name' => $user->middle_name,
            'extension_name' => $user->extension_name,
            'address' => $user->address,
            'email' => $user->email,
            'phone_number' => $user->phone_number,
            'roles' => $user->roles,
            'business_name' => $user->business ? $user->business->business_name : null,
            'user_type' => $user->userType ? $user->userType->user_type : null,
        ];
    }
}
