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
    
    public function viewPointCalculationByBusiness(Request $request){
        
        if($request->business_id != null){

            $pointCalculationsWithBusinessName = PointCalculation::with('business:id,business_name')
            ->where('business_id', $request->business_id)
            ->get();


            return response()->json($pointCalculationsWithBusinessName);
        }else{
            $pointCalculationsWithBusinessName = PointCalculation::with('business:id,business_name')
            ->get();


            return response()->json($pointCalculationsWithBusinessName);
        }

        

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
        $point_calculation = PointCalculation::findOrFail($id);
        $validatedData = $request->validate([
            "base_amount" => 'integer',
            "points_per_base_amount" => 'integer'
        ]);
        $baseAmount = $validatedData['base_amount'];
        $pointsPerBaseAmount = $validatedData['points_per_base_amount'];

        // Calculate the multiplier
        $multiplier = round($pointsPerBaseAmount / $baseAmount, 2);

        // Update the multiplier in the database
        $point_calculation->update([
            "base_amount" => $baseAmount,
            "points_per_base_amount" => $pointsPerBaseAmount,
            "multiplier" => $multiplier
        ]);
        
        return response()->json($point_calculation);

        
    }

    
    public function destroy($id)
    {
        //
    }
}
