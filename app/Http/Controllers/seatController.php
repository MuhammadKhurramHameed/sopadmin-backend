<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;

class seatController extends Controller
{
    public function blockwiseseting(Request $request){
        $validate =Validator::make($request->all(),[
            "block_id"    => 'required',
         ]);
        if ($validate->fails()) {    
            return response()->json($validate->errors(), 400);
        }
        $reguser = DB::table('programregistrations')->where('block_id', '=', $request->block_id)->get();
        $users = array();
        $index=0;
        foreach($reguser as $regusers){
            $users[$index] = DB::table('users')->where('id', '=', $regusers->user_id)->select('id','name','image')->first();
            $users[$index]->registrationno = $regusers->registration_no;
            $index++;
        }
        $path = 'http://146.71.76.22/sopstudentnewbackend/public/userimage/';
        if($users){
            return response()->json(['data' => $users, 'path' => $path, 'message' => 'Student Seating Arrangements'],200);
        }else{
            return response()->json(['data' => array(), 'message' => 'Student Seating Arrangements'],200);
        }
    }
}
