<?php

namespace App\Http\Controllers\API;

use App\Models\UserType;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class UserTypeController extends Controller
{
    
    public function index()
    {
        $user_type = UserType::all();
        return response()->json([
            'user_type' => $user_type
        ]);
    }

    
    public function store(Request $request)
    {
        //
    }

    
    public function show($id)
    {
        //
    }

    
    public function update(Request $request, $id)
    {
        //
    }

    
    public function destroy($id)
    {
        //
    }
}
