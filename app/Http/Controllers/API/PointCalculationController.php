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
        //
    }

    
    public function destroy($id)
    {
        //
    }
}
