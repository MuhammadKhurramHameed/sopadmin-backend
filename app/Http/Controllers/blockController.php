<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;

class blockController extends Controller
{
    public function getStartRollNo($programId, $PhaseCenterid){
        $phaseCenter = DB::table('phasecenter')->where('id', '=', $PhaseCenterid)->first();
        $districtId=$phaseCenter->districtId;
        $startRollNo = DB::table('programregistrations')->where('districtId', '=', $districtId)->where('programId', '=', $programId)->where("block_id", '=', 0)->orderBy('registration_no')->get();
        return $startRollNo;
    }

    public function calculateEndRollNo($capacity, $programId, $PhaseCenterId){
        $getRollNos=$this->getStartRollNo($programId, $PhaseCenterId);
        $endRollNo=0;
        $index=1;
        foreach($getRollNos as $rollNo){
            if($index<=$capacity){
                $endRollNo=$rollNo;
            }
            $index++;
        }
        return $endRollNo->registration_no;
    }

    public function AssignBlockToStudents($blockId, $capacity, $programId, $PhaseCenterId){
        $getRollNos=$this->getStartRollNo($programId, $PhaseCenterId);
        foreach($getRollNos as $rollNo){
            $id=$rollNo->id;
            $updateblockAssigned=DB::table('programregistrations')->where("id", "=", $id)->update(['block_id'=> $blockId]);
        }
   }

    public function createBlock(Request $request){
        $validate =Validator::make($request->all(),[
            "name"              => 'required',
            "capacity"          => 'required',
            "startRollNo"       => 'required',
            "invigilator_id"    => 'required',
            "PhaseCenterid"     => 'required',
            "programId"         => 'required'
        ]);
        if ($validate->fails()) {    
            return response()->json($validate->errors(), 400);
        }
        $adds = array(
            'name' 		    => $request->name,
            'capacity' 		=> $request->capacity,
            'startRollNo' 	=> $request->startRollNo,
            'endRollNo' 	=> $this->calculateEndRollNo($request->capacity, $request->programId, $request->PhaseCenterid),
            'phaseCenterId'	=> $request->PhaseCenterid,
            'invigilator_id'=> $request->invigilator_id,
            'status_id' 	=> 1,
            'created_by' 	=> $request->id,
            'created_at' 	=> date("Y-m-d h:i:s"),
        );
        $save = DB::table('block')->insert($adds);
        $blockidDB=DB::getPdo()->lastInsertId();
        $this->AssignBlockToStudents($blockidDB, $request->capacity, $request->programId, $request->PhaseCenterid);
        if($save){
            return response()->json(['message' => 'Block Created Successfully'],200);
        }else{
            return response()->json("Oops! Something Went Wrong", 400);
        }	
    }
    public function updateBlock(Request $request){
        $validate =Validator::make($request->all(),[
            "bloack_id"     => 'required',
            "name"          => 'required',
            "capacity"      => 'required',
            "startRollNo"   => 'required',
            "phaseCenterId" => 'required'
        ]);
        if ($validate->fails()) {    
            return response()->json($validate->errors(), 400);
        }
        $adds = array(
            'name' 		    => $request->name,
            'capacity' 		=> $request->capacity,
            'startRollNo' 	=> $request->startRollNo,
            'phaseCenterId'	=> $request->phaseCenterId,
            'updated_at' 	=> date("Y-m-d h:i:s"),
        );
        $save = DB::table('block')
        ->where('id','=',$request->bloack_id)
        ->update($adds); 
        if($save){
            return response()->json(['message' => 'Block Updated Successfully'],200);
        }else{
            return response()->json("Oops! Something Went Wrong", 400);
        }	
    }
    public function getBlockList(){
        $BlockList= DB::table('blocklist')->where('status_id', '=', 1)->orderBy('id','DESC')->get();
        if($BlockList){
            return response()->json(['data' => $BlockList, 'message' => 'Bloack List'],200);
        }else{
            return response()->json(['data' => array(), 'message' => 'Bloack List'],200);
        }
    }
    public function deleteBlock(Request $request){
        $validate= Validator::make($request->all(), [
            'bloack_id' => 'required',
         ]);
        if ($validate->fails()) {    
            return response()->json($validate->errors(), 400);
        }
        $delete = DB::table('block')->where('id', '=', $request->bloack_id)->update(['status_id' => 3]);
        if($delete){
            return response()->json(['message' => 'Block Deleted Successfully'],200);
       }else{
            return response()->json(['message' => 'Block Deleted Successfully'],200);
       }
    }

    public function blockListByPhaseCenter(Request $request){
        $validate= Validator::make($request->all(), [
            'phaseCenterId' =>'required'
        ]);
        if($validate->fails()){
            return response()->json($validate->errors(), 400);
        }
        $blockList= DB::table('block')->where('phaseCenterId', $request->phaseCenterId)->get();
        if($blockList){
            return response()->json(['blockList'=> $blockList], 200);
        }else{
            return response()->json("Oops! something went wrong", 400);
        }
    }
}
