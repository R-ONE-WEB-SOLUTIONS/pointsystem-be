<?php

namespace App\Http\Controllers\API;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

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
            return response()->json(['message' => 'No users found'], 200);
        }
        
        return response()->json(['message' => 'Users found', 'data' => $this->mapuser($users)],200);
    }


    public function store(Request $request)
    {
        
    }

    
    public function show($id)
    {
        
    }

    
    public function update(Request $request, $id)
    {
        
    }

    
    public function destroy($id)
    {
        
    }

    //mapping user to attach business and usertype
    private function mapuser($users) {

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
}
