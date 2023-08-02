<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    
    public function login(Request $request)
    {
        // Validate user input
        $validated = $request->validate([
            'email' => ['required', 'max:255', 'email'],
            'password' => ['required', 'string', 'min:8'],
        ]);

        // Validation passed, attempt to authenticate the user
        $credentials = $request->only('email', 'password');

        if (auth()->attempt($credentials)) {
            // Authentication successful, generate a token or perform any other logic
            $user = auth()->user();
            $token = $user->createToken('loginToken')->plainTextToken;
            return response()->json([
                'message' => 'Welcome '. $user->first_name,
                'token' => $token,
                'id' => $user->id
            ]);
        } else {
            // Authentication failed, return error response
            return response()->json(['error' => 'Invalid credentials'], 401);
        }
        
    }

    public function logout()
    {
        Auth::user()->currentAccessToken()->delete();

        return response()->json([
            'message' => "You have successfully logout"
        ]);
    }

}
