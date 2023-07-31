<?php

namespace App\Http\Controllers\API;

use App\Models\Client;
use App\Models\PreReg;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class PreRegController extends Controller
{
    
    public function index(){

        $pre_reg = PreReg::leftJoin('client_types', 'pre_regs.client_type_id', '=', 'client_types.id')
                    ->leftJoin('businesses', 'pre_regs.business_id', '=', 'businesses.id')
                    ->select('pre_regs.*', 'client_types.client_type', 'businesses.business_name')
                    ->get();

        if ($pre_reg->isEmpty()) {
            return response()->json(['message' => 'No PreClient found'], 200);
        }
        return response()->json([
            'pre_reg' => $pre_reg
        ]);
    }

    public function viewAllPreReg(Request $request){
        
        if($request->business_id != null){
           
            $pre_reg = PreReg::where('business_id', '=', $request->business_id)
                ->leftJoin('client_types', 'pre_regs.client_type_id', '=', 'client_types.id')
                ->leftJoin('businesses', 'pre_regs.business_id', '=', 'businesses.id')
                ->select('pre_regs.*', 'client_types.client_type', 'businesses.business_name')
                ->get();

            if ($pre_reg->isEmpty()) {
                return response()->json(['message' => 'No Pre Client found'], 200);
            }
            return response()->json([
                'pre_reg' => $pre_reg
            ]);

        }else{
            
            $pre_reg = PreReg::leftJoin('client_types', 'pre_regs.client_type_id', '=', 'client_types.id')
                    ->leftJoin('businesses', 'pre_regs.business_id', '=', 'businesses.id')
                    ->select('pre_regs.*', 'client_types.client_type', 'businesses.business_name')
                    ->get();
            if ($pre_reg->isEmpty()) {
                return response()->json(['message' => 'No Pre Client found'], 200);
            }
            return response()->json([
                'pre_reg' => $pre_reg
            ]);
        }
    }

    
    public function store(Request $request){

        $validator = Validator::make($request->all(), [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'extension_name' => 'nullable|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'phone_number' => 'required|string|max:11',
            'address' => 'required|string|max:255',
            'client_type_id' => 'required',
            'business_id' => 'required',
        ]);
    
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }
    
        $preClient = PreReg::create($request->all());
    
        return response()->json(['message' => 'Client has been pre registered', 'preClient' => $preClient], 200);
    }

    
    public function show($id){
        //
    }

    public function update(Request $request, $id){
        $pre_reg = PreReg::findOrFail($id);

        $validatedData = $request->validate([
            'first_name' => 'string|max:255',
            'last_name' => 'string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'extension_name' => 'nullable|string|max:255',
            'email' => 'email',
            'phone_number' => 'string|max:20',
            'address' => 'string|max:255',
            'client_type_id' => 'integer'
        ]);

        // Filter out only the columns that are present in the validated data
        $fillableData = array_filter($validatedData, function ($value) {
            return $value !== null;
        });

        $pre_reg->update($fillableData);

        return response()->json([
            'message' => 'Pre Reg info updated successfully.',
            'pre_reg' => $pre_reg
        ]);
    }
    
    public function approvePreReg(Request $request, $id){
        $preClient = PreReg::findOrFail($id);
        

        if($request->registered == 'decline' || $request->registered == 'Decline'){
            
            $preClient->update([
                'registered' => false
            ]);

            return response()->json(['message' => 'Clients pre registration has been declined', 'preClient' => $preClient], 200);

        }else if($request->registered == 'register' || $request->registered == 'Register'){
            $data = $preClient->toArray();
            
            unset($data['created_at']);
            unset($data['updated_at']);
            
            $data['active'] = 1;
            
            $client = Client::create($data);;

            if ($client) {
                $preClient->update([
                    'registered' => true
                ]);
                return response()->json(['message' => 'Clients pre registration has been confirmed', 'preClient' => $preClient], 200);
            } else {
                return response()->json(['message' => 'something went wrong']);
            }


        }

    }

    
    public function destroy($id){
        $pre_reg = PreReg::findOrFail($id);
    
        $pre_reg->delete();

        return response()->json([
            'message' => 'Pre Register Client deleted successfully.'
        ],200);
    }
}