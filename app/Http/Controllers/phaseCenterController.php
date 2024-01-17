<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;

class phaseCenterController extends Controller
{
    public function savePhaseCenter(Request $request){
        $validate= Validator::make($request->all(), [
            'name'          => 'required',
            'address'       => 'required',
            'shortCode'     => 'required',
            'provinceId'    => 'required',
            'districtId'    => 'required',
            'controllerId'  => 'required',
            'status_id'     => 'required',
        ]);
        if ($validate->fails()) {    
            return response()->json($validate->errors(), 400);
        }
        $adds = array(
            'name' 		    => $request->name,
            'address' 		=> $request->address,
            'shortCode' 	=> $request->shortCode,
            'provinceId'	=> $request->provinceId,
            'districtId' 	=> $request->districtId,
            'controllerId' 	=> $request->controllerId,
            'status_id' 	=> $request->status_id,
            'created_by' 	=> $request->id,
            'created_at' 	=> date("Y-m-d h:i:s"),
        );
        $save = DB::table('phasecenter')->insert($adds);
        if($save){
            return response()->json(['message' => 'Phase Center Created Successfully'],200);
        }else{
            return response()->json("Oops! Something Went Wrong", 400);
        }	
    }
    public function updatePhaseCenter(Request $request){
        $validate= Validator::make($request->all(), [
            'phasecenter_id' => 'required',
            'name'           => 'required',
            'address'        => 'required',
            'shortCode'      => 'required',
            'provinceId'     => 'required',
            'districtId'     => 'required',
            'controllerId'   => 'required',
            'status_id'      => 'required',
        ]);
        if ($validate->fails()) {    
            return response()->json($validate->errors(), 400);
        }
        $adds = array(
            'name' 		    => $request->name,
            'address' 		=> $request->address,
            'shortCode' 	=> $request->shortCode,
            'provinceId'	=> $request->provinceId,
            'districtId' 	=> $request->districtId,
            'controllerId' 	=> $request->controllerId,
            'status_id' 	=> $request->status_id,
            'created_at' 	=> date("Y-m-d h:i:s"),
        );
        $save = DB::table('phasecenter')
        ->where('id','=',$request->phasecenter_id)
        ->update($adds); 
        if($save){
            return response()->json(['message' => 'Phase Center Updated Successfully'],200);
        }else{
            return response()->json("Oops! Something Went Wrong", 400);
        }	
   }
    public function getPhaseCenterList(){
        $phaseCenter=DB::table('phasecenterdetails')->where('status_id', '=', 1)->orderBy('id','DESC')->get();
        if($phaseCenter){
            return response()->json(['data' => $phaseCenter, 'message' => 'Phase Center List'],200);
        }else{
            return response()->json(['data' => array(), 'message' => 'Phase Center List'],200);
        }
    }
    public function deletePhaseCenter(Request $request){
        $validate= Validator::make($request->all(), [
            'phasecenter_id' => 'required',
         ]);
        if ($validate->fails()) {    
            return response()->json($validate->errors(), 400);
        }
        $delete = DB::table('phasecenter')->where('id', '=', $request->phasecenter_id)->update(['status_id' => 3]);
        if($delete){
            return response()->json(['message' => 'Phase Center Deleted Successfully'],200);
       }else{
            return response()->json(['message' => 'Phase Center Deleted Successfully'],200);
       }
    }

    public function phaseCenterByDistrict(Request $request){
        $validate=Validator::make($request->all(), [
            'districtId' => 'required'
        ]);
        
        if($validate->fails()){
            return response()->json($validate->errors(), 400);
        }

        $phasecenter=DB::table('phasecenter')->where('districtId', $request->districtId)->get();
        if($phasecenter){
            return response()->json(["phaseCenter"=> $phasecenter], 200);
        }else{
            return response()->json("Oops! something went wrong", 400);
        }
    }
}