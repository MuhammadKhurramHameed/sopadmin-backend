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

class programController extends Controller
{
	public $emptyarray = array();
	public function saveprogram(Request $request){
		$validate = Validator::make($request->all(), [
	    	'name'  		=> 'required',
	    	'description'  	=> 'required',
	    	'code'  		=> 'required',
	    	'startYear'  	=> 'required',
	    	'endYear'  		=> 'required',
	    	'status_id'  	=> 'required',
	    ]);
		if ($validate->fails()) {    
			return response()->json($validate->errors(), 400);
		}
		$adds = array(
			'name' 			=> $request->name,
			'description' 	=> $request->description,
			'code' 			=> $request->code,
			'startYear'		=> $request->startYear,
			'endYear'		=> $request->endYear,
			'status_id'		=> $request->status_id,
			'creator'		=> $request->id,
			'createdAt'		=> date('Y-m-d h:i:s'),
		);
		$save = DB::table('program')->insert($adds);
		if($save){
			return response()->json(['message' => 'Program Saved Successfully'],200);
		}else{
			return response()->json("Oops! Something Went Wrong", 400);
		}
	}
	public function updateprogram(Request $request){
		$validate = Validator::make($request->all(), [
	    	'program_id'  	=> 'required',
	    	'name'  		=> 'required',
	    	'description'  	=> 'required',
	    	'code'  		=> 'required',
	    	'startYear'  	=> 'required',
	    	'endYear'  		=> 'required',
	    	'status_id'  	=> 'required',
	    ]);
		if ($validate->fails()) {    
			return response()->json($validate->errors(), 400);
		}
		$adds = array(
			'name' 			=> $request->name,
			'description' 	=> $request->description,
			'code' 			=> $request->code,
			'startYear'		=> $request->startYear,
			'endYear'		=> $request->endYear,
			'status_id'		=> $request->status_id,
		);
		$save = DB::table('program')
		->where('id','=',$request->program_id)
		->update($adds); 
		if($save){
			return response()->json(['message' => 'Program Updated Successfully'],200);
		}else{
			return response()->json("Oops! Something Went Wrong", 400);
		}
	}
	public function deleteprogram(Request $request){
		$validate = Validator::make($request->all(), [
	    	'program_id'  	=> 'required',
	    ]);
		if ($validate->fails()) {    
			return response()->json($validate->errors(), 400);
		}
		$adds = array(
			'status_id'		=> 3,
		);
		$save = DB::table('program')
		->where('id','=',$request->program_id)
		->update($adds); 
		if($save){
			return response()->json(['message' => 'Program Deleted Successfully'],200);
		}else{
			return response()->json("Oops! Something Went Wrong", 400);
		}
	}
	public function programlist(Request $request){
		$daat = DB::table('program')
		->select('*')
		->whereIn('status_id',[1,2])
		->get();		
		if($daat){
			return response()->json(['data' => $daat,'message' => 'Program List'],200);
		}else{
			$emptyarray = array();
			return response()->json(['data' => $emptyarray,'message' => 'Program List'],200);
		}
	}
}