<?php

namespace App\Http\Controllers\API;

use App\Models\ClientType;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ClientTypeController extends Controller
{
    
    public function index()
    {
        $client_type = ClientType::all();
        return response()->json([
            'client_type' => $client_type
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
