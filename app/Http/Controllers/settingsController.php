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

class settingsController extends Controller
{
	public $emptyarray = array();
	public function rolelist(Request $request){
		$data=DB::table('role')
		->select('*')
		->where('role_id', '!=', 1)
		->get();
		if($data){
			return response()->json(['data' => $data, 'message' => 'Role List'],200);
		}else{
			$emptyarray = array();
			return response()->json(['data' => $emptyarray,'message' => 'Role List'],200);
		}
	}
	public function subjectlist(Request $request){
		$data=DB::table('subjects')
		->select('id','subject_title')
		->where('status_id', '=', 1)
		->get();
		if($data){
			return response()->json(['data' => $data, 'message' => 'Subject List'],200);
		}else{
			$emptyarray = array();
			return response()->json(['data' => $emptyarray,'message' => 'Subject List'],200);
		}
	}
	public function gradelist(Request $request){
		$data=DB::table('grade')
		->select('id','name')
		->where('status', '=', "active")
		->get();
		if($data){
			return response()->json(['data' => $data, 'message' => 'Grade List'],200);
		}else{
			$emptyarray = array();
			return response()->json(['data' => $emptyarray,'message' => 'Grade List'],200);
		}
	}
	public function programbatchlist(Request $request){
		$validate = Validator::make($request->all(), [
	    	'programId'  		=> 'required',
	    ]);
		if ($validate->fails()) {    
			return response()->json($validate->errors(), 400);
		}
		$data=DB::table('batch')
		->select('*')
		->where('status_id', '=', 1)
		->where('programId', '=', $request->programId)
		->get();
		if($data){
			return response()->json(['data' => $data, 'message' => 'Batch Program List'],200);
		}else{
			$emptyarray = array();
			return response()->json(['data' => $emptyarray,'message' => 'Batch Program List'],200);
		}
	}
	public function programphaselist(Request $request){
		$validate = Validator::make($request->all(), [
	    	'programId'  	=> 'required',
	    	'batchId'  		=> 'required',
	    ]);
		if ($validate->fails()) {    
			return response()->json($validate->errors(), 400);
		}
		$data=DB::table('program_phase')
		->select('*')
		->whereIn('status_id',[1,2])
		->where('program_id', '=', $request->programId)
		->where('batch_id', '=', $request->batchId)
		->get();
		if($data){
			return response()->json(['data' => $data, 'message' => 'Program Phase List'],200);
		}else{
			$emptyarray = array();
			return response()->json(['data' => $emptyarray,'message' => 'Program Phase List'],200);
		}
	}
	public function deleteprogramphase(Request $request){
		$validate = Validator::make($request->all(), [
	    	'programphaseId'  	=> 'required',
	    ]);
		if ($validate->fails()) {    
			return response()->json($validate->errors(), 400);
		}
		$adds = array(
			'status_id'		=> 3,
		);
		$save = DB::table('program_phase')
		->where('id','=',$request->programphaseId)
		->update($adds); 
		if($save){
			return response()->json(['message' => 'Program Phase Deleted Successfully'],200);
		}else{
			return response()->json("Oops! Something Went Wrong", 400);
		}
	}
	public function saveprovince(Request $request){
		$validate = Validator::make($request->all(), [
			'name'  		=> 'required',
			'description'  	=> 'required',
			'initial'  		=> 'required',
	   ]);
		if ($validate->fails()) {    
			return response()->json($validate->errors(), 400);
		}
		$adds = array(
			'name' 			=> $request->name,
			'description' 	=> $request->description,
			'initial' 		=> $request->initial,
			'status'		=> "active",
			'creator'		=> $request->id,
			'createdAt'		=> date('Y-m-d h:i:s'),
		);
		$save = DB::table('province')->insert($adds);
		if($save){
			return response()->json(['message' => 'Province Saved Successfully'],200);
		}else{
			return response()->json("Oops! Something Went Wrong", 400);
		}
	}
	public function savedistrict(Request $request){
		$validate = Validator::make($request->all(), [
	    	'name'  		=> 'required',
	    	'description'  	=> 'required',
	    	'provinceId'  	=> 'required',
	   ]);
		if ($validate->fails()) {    
			return response()->json($validate->errors(), 400);
		}
		$adds = array(
			'name' 			=> $request->name,
			'description' 	=> $request->description,
			'provinceId' 	=> $request->provinceId,
			'status'		=> "active",
			'creator'		=> $request->id,
			'createdAt'	=> date('Y-m-d h:i:s'),
		);
		$save = DB::table('district')->insert($adds);
		if($save){
			return response()->json(['message' => 'District Saved Successfully'],200);
		}else{
			return response()->json("Oops! Something Went Wrong", 400);
		}
	}
}