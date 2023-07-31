<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Models\PointCalculation;
use App\Http\Controllers\Controller;

class PointCalculationController extends Controller
{
    
    public function index()
    {
        $point_calculation = PointCalculation::first();
        return response()->json([
            'point_calculation' => $point_calculation
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
