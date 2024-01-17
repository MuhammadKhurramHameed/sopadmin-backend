<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\File;
use Image;
use DB;
use Input;
use App\Item;
use Session;
use Response;
use Validator;
use URL;

class employeetimingController extends Controller
{
	public $emptyarray = array();
	public function saveemptiming(Request $request){
		$validate = Validator::make($request->all(), [
	    	'emptiming_arrivaltime'  	=> 'required',
	    	'emptiming_departuretime'  	=> 'required',
	    	'emptiming_arrivaldate'  	=> 'required',
	    	'emptiming_departuredate'  	=> 'required',
	    	'emp_id'  					=> 'required',
	    ]);
		if ($validate->fails()) {    
			return response()->json($validate->errors(), 400);
		}
		$adds = array(
			'emptiming_arrivaltime' 	=> $request->emptiming_arrivaltime,
			'emptiming_departuretime' 	=> $request->emptiming_departuretime,
			'emptiming_arrivaldate' 	=> $request->emptiming_arrivaldate,
			'emptiming_departuredate' 	=> $request->emptiming_departuredate,
			'emp_id' 					=> $request->emp_id,
			'status_id'					=> 1,
			'created_by'				=> $request->id,
			'created_at'				=> date('Y-m-d h:i:s'),
		);
		$save = DB::table('emptiming')->insert($adds);
		if($save){
			return response()->json(['message' => 'Employee Timing Saved Successfully'],200);
		}else{
			return response()->json("Oops! Something Went Wrong", 400);
		}
	}
	public function emptiminglist(Request $request){
		$daat = DB::table('emptiminglist')
		->select('*')
		->where('status_id','=',1)
		->paginate(30);		
		if($daat){
			return response()->json(['data' => $daat,'message' => 'Employee Timing List'],200);
		}else{
			$emptyarray = array();
			return response()->json(['data' => $emptyarray,'message' => 'Employee Timing List'],200);
		}
	}
	public function deleteemptiming(Request $request){
        $validate= Validator::make($request->all(), [
            'emptiming_id' => 'required',
         ]);
        if ($validate->fails()) {    
            return response()->json($validate->errors(), 400);
        }
        $delete = DB::table('emptiming')->where('emptiming_id', '=', $request->emptiming_id)->update(['status_id' => 3]);
        if($delete){
            return response()->json(['message' => 'Employee Timing Deleted Successfully'],200);
       }else{
            return response()->json(['message' => 'Employee Timing Deleted Successfully'],200);
       }
    }
}