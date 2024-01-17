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

class programphaseController extends Controller
{
	public $emptyarray = array();
	public function saveprogramphase(Request $request){
		$validate = Validator::make($request->all(), [
	    	'name'  		=> 'required',
	    	'program_id'  	=> 'required',
	    	'batch_id'  	=> 'required',
	     ]);
		if ($validate->fails()) {    
			return response()->json($validate->errors(), 400);
		}
		$adds = array(
			'name' 			=> $request->name,
			'program_id' 	=> $request->program_id,
			'batch_id' 		=> $request->batch_id,
			'status_id'		=> 1,
			'created_by'	=> $request->id,
			'created_at'	=> date('Y-m-d h:i:s'),
		);
		$save = DB::table('program_phase')->insert($adds);
		if($save){
			return response()->json(['message' => 'Program Phase Saved Successfully'],200);
		}else{
			return response()->json("Oops! Something Went Wrong", 400);
		}
	}
	public function updateprogramphase(Request $request){
		$validate = Validator::make($request->all(), [
	    	'programphase_id'  	=> 'required',
	    	'name'  			=> 'required',
	    	'program_id'  		=> 'required',
	    	'batch_id'  		=> 'required',
	     ]);
		if ($validate->fails()) {    
			return response()->json($validate->errors(), 400);
		}
		$adds = array(
			'name' 			=> $request->name,
			'program_id' 	=> $request->program_id,
			'batch_id' 		=> $request->batch_id,
			'updated_at'	=> date('Y-m-d h:i:s'),
		);
		$save = DB::table('program_phase')
		->where('id','=',$request->programphase_id)
		->update($adds); 
		if($save){
			return response()->json(['message' => 'Program Phase Updated Successfully'],200);
		}else{
			return response()->json("Oops! Something Went Wrong", 400);
		}
	}
	public function programphaselist(Request $request){
		$daat = DB::table('programphasedetail')->where('status_id', '=', 1)
		->select('*')
		->where('status_id','=',1)
		->get();		
		if($daat){
			return response()->json(['data' => $daat,'message' => 'Program Phase List'],200);
		}else{
			$emptyarray = array();
			return response()->json(['data' => $emptyarray,'message' => 'Program Phase List'],200);
		}
	}
	public function deleteprogramphase(Request $request){
		$validate = Validator::make($request->all(), [
	    	'programphase_id'  	=> 'required',
	    ]);
		if ($validate->fails()) {    
			return response()->json($validate->errors(), 400);
		}
		$adds = array(
			'status_id' 	=> 3,
		);
		$save = DB::table('program_phase')
		->where('id','=',$request->programphase_id)
		->update($adds); 
		if($save){
			return response()->json(['message' => 'Program Phase Deleteds Successfully'],200);
		}else{
			return response()->json("Oops! Something Went Wrong", 400);
		}
	}
}