<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;

class attendanceController extends Controller
{
    public function blockwiseattendance(Request $request){
        $validate = Validator::make($request->all(),[
            "block_id"    => 'required',
        ]);
        if ($validate->fails()) {    
            return response()->json($validate->errors(), 400);
        }
        $reguser = DB::table('programregistrations')->where('batchId', '=', $request->block_id)->get();
        $users = array();
        $index=0;
        foreach($reguser as $regusers){
            $users[$index] = DB::table('users')->where('id', '=', $regusers->user_id)->select('id','name','image')->first();
            $users[$index]->programreg_id = $regusers->id;
            $users[$index]->registrationno = $regusers->registration_no;
            $users[$index]->is_present = $regusers->is_present;
            $index++;
        }
        $path = 'https://studentofpakistan.com/sopstudentnewbackend/public/userimage/';
        if($users){
            return response()->json(['data' => $users, 'path' => $path, 'message' => 'Student Attendance List'],200);
        }else{
            return response()->json(['data' => array(), 'message' => 'Student Attendance List'],200);
        }
    }
    public function markattendance(Request $request){
		$validate = Validator::make($request->all(), [
	    	'programreg_id'  	=> 'required',
	   ]);
		if ($validate->fails()) {    
			return response()->json($validate->errors(), 400);
		}
		$adds = array(
			'is_present' 			=> 1,
		);
		$save = DB::table('programregistrations')
		->where('id','=',$request->programreg_id)
		->update($adds); 
		if($save){
			return response()->json(['message' => 'Mark Successfully'],200);
		}else{
			return response()->json("Oops! Something Went Wrong", 400);
		}
	}
}
