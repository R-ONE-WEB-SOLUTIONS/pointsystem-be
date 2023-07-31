<?php

namespace App\Services;

use Exception;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;


class AuthService {

    public function __construct() {


    }

    public function login($request) {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email|max:255',
            'password' => 'required|string|min:8',
        ]);
        
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        $credentials = $request->only('email', 'password');
       
        if (!Auth::attempt($credentials)) {
            return response()->json(['message' => 'Credentials do not match'], 401);
        }

        $user = User::where('email', $request->email)
                ->leftJoin('user_types', 'users.user_type_id', '=', 'user_types.id')
                ->leftJoin('businesses', 'users.business_id', '=', 'businesses.id')
                ->select('users.*', 'user_types.user_type', 'businesses.business_name')
                ->first();

        $token = $user->createToken('Api Token for ' . $user->email)->plainTextToken;

        return response()->json([
            'user' => $user,
            'token' => $token
        ]);
    }

    public function logout() {
        Auth::user()->currentAccessToken()->delete();

        return response()->json([
            'message' => "You have successfully logout"
        ]);
    }

}

