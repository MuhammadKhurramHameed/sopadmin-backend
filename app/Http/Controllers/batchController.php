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

class batchController extends Controller
{
	public $emptyarray = array();
	public function savebatch(Request $request){
		$validate = Validator::make($request->all(), [
	    	'name'  		=> 'required',
	    	'description'  	=> 'required',
	    	'code'  		=> 'required',
	    	'startDate'  	=> 'required',
	    	'endDate'  		=> 'required',
	    	'programId'  	=> 'required',
	    	'status_id'  	=> 'required',
	    ]);
		if ($validate->fails()) {    
			return response()->json($validate->errors(), 400);
		}
		$adds = array(
			'name' 			=> $request->name,
			'description' 	=> $request->description,
			'code' 			=> $request->code,
			'startDate'		=> $request->startDate,
			'endDate'		=> $request->endDate,
			'programId'		=> $request->programId,
			'status_id'		=> $request->status_id,
			'creator'		=> $request->id,
			'createdAt'		=> date('Y-m-d h:i:s'),
		);
		$save = DB::table('batch')->insert($adds);
		if($save){
			return response()->json(['message' => 'Batch Saved Successfully'],200);
		}else{
			return response()->json("Oops! Something Went Wrong", 400);
		}
	}
	public function updatebatch(Request $request){
		$validate = Validator::make($request->all(), [
	    	'batch_id'  	=> 'required',
	    	'name'  		=> 'required',
	    	'description'  	=> 'required',
	    	'code'  		=> 'required',
	    	'startDate'  	=> 'required',
	    	'endDate'  		=> 'required',
	    	'programId'  	=> 'required',
	    	'status_id'  	=> 'required',
	    ]);
		if ($validate->fails()) {    
			return response()->json($validate->errors(), 400);
		}
		$adds = array(
			'name' 			=> $request->name,
			'description' 	=> $request->description,
			'code' 			=> $request->code,
			'startDate'		=> $request->startDate,
			'endDate'		=> $request->endDate,
			'programId'		=> $request->programId,
			'status_id'		=> $request->status_id,
		);
		$save = DB::table('batch')
		->where('id','=',$request->batch_id)
		->update($adds); 
		if($save){
			return response()->json(['message' => 'Batch Updated Successfully'],200);
		}else{
			return response()->json("Oops! Something Went Wrong", 400);
		}
	}
	public function batchlist(Request $request){
		$daat = DB::table('batchdetails')
		->select('*')
		->whereIn('status_id',[1,2])
		->get();		
		if($daat){
			return response()->json(['data' => $daat,'message' => 'Batch List'],200);
		}else{
			$emptyarray = array();
			return response()->json(['data' => $emptyarray,'message' => 'Batch List'],200);
		}
	}
	public function deletebatch(Request $request){
		$validate = Validator::make($request->all(), [
	    	'batch_id'  	=> 'required',
	    ]);
		if ($validate->fails()) {    
			return response()->json($validate->errors(), 400);
		}
		$adds = array(
			'status_id'		=> 3,
		);
		$save = DB::table('batch')
		->where('id','=',$request->batch_id)
		->update($adds); 
		if($save){
			return response()->json(['message' => 'Batch Deleted Successfully'],200);
		}else{
			return response()->json("Oops! Something Went Wrong", 400);
		}
	}
}