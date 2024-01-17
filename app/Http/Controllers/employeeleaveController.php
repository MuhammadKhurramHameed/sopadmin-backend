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

class employeeleaveController extends Controller
{
	public $emptyarray = array();
	public function saveempleave(Request $request){
		$validate = Validator::make($request->all(), [
	    	'empleave_startdate'  	=> 'required',
	    	'empleave_enddate'  	=> 'required',
	    	'empleave_comment'  	=> 'required',
	    	'empleave_totaldays'  	=> 'required',
	    	'empleavetype_id'  		=> 'required',
	    	'emp_id'  				=> 'required',
	    ]);
		if ($validate->fails()) {    
			return response()->json($validate->errors(), 400);
		}
		$adds = array(
			'empleave_startdate' 	=> $request->empleave_startdate,
			'empleave_enddate' 		=> $request->empleave_enddate,
			'empleave_comment' 		=> $request->empleave_comment,
			'empleave_totaldays' 	=> $request->empleave_totaldays,
			'empleavetype_id' 		=> $request->empleavetype_id,
			'empleavestatus_id'		=> 1,
			'status_id'				=> 1,
			'created_by'			=> $request->emp_id,
			'created_at'			=> date('Y-m-d h:i:s'),
		);
		$save = DB::table('empleave')->insert($adds);
		if($save){
			return response()->json(['message' => 'Employee Leave Saved Successfully'],200);
		}else{
			return response()->json("Oops! Something Went Wrong", 400);
		}
	}
	public function empleavelist(Request $request){
		$validate = Validator::make($request->all(), [
	    	'empleavestatus_id'  	=> 'required',
	    ]);
		if ($validate->fails()) {    
			return response()->json($validate->errors(), 400);
		}
		$daat = DB::table('empleavelist')
		->select('*')
		->where('empleavestatus_id','=',$request->empleavestatus_id)
		->where('status_id','=',1)
		->paginate(30);		
		if($daat){
			return response()->json(['data' => $daat,'message' => 'Employee Leave List'],200);
		}else{
			$emptyarray = array();
			return response()->json(['data' => $emptyarray,'message' => 'Employee Leave List'],200);
		}
	}
	public function proceedempleave(Request $request){
        $validate= Validator::make($request->all(), [
            'empleave_id' 		=> 'required',
            'empleavestatus_id' => 'required',
         ]);
        if ($validate->fails()) {    
            return response()->json($validate->errors(), 400);
        }
        $delete = DB::table('empleave')->where('empleave_id', '=', $request->empleave_id)->update(['empleavestatus_id' => $request->empleavestatus_id]);
        if($delete){
            return response()->json(['message' => 'Employee Leave Proceed Successfully'],200);
       }else{
            return response()->json(['message' => 'Employee Leave Proceed Successfully'],200);
       }
    }
}