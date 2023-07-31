<?php

namespace App\Http\Controllers\API;

use App\Models\PreReg;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PreRegController extends Controller
{
    
    public function index()
    {
        $pre_reg = PreReg::all();
        return response()->json([
            'pre_reg' => $pre_reg
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
