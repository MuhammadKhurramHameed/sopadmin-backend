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

class notificationController extends Controller
{
	public $emptyarray = array();
	public function savenotification(Request $request){
		$validate = Validator::make($request->all(), [
	    	'title'  		=> 'required',
	    	'description'  	=> 'required',
	    	'program_id'  	=> 'required',
	   ]);
		if ($validate->fails()) {    
			return response()->json($validate->errors(), 400);
		}
		$adds = array(
			'title' 		=> $request->title,
			'description' 	=> $request->description,
			'program_id' 	=> $request->program_id,
			'status_id'		=> 1,
			'created_by'	=> $request->id,
			'created_at'	=> date('Y-m-d h:i:s'),
		);
		$save = DB::table('notifications')->insert($adds);
		if($save){
			return response()->json(['message' => 'Notification Saved Successfully'],200);
		}else{
			return response()->json("Oops! Something Went Wrong", 400);
		}
	}
	public function notificationlist(Request $request){
		$daat = DB::table('notifications')
		->select('*')
		->get();		
		if($daat){
			return response()->json(['data' => $daat,'message' => 'Notification List'],200);
		}else{
			$emptyarray = array();
			return response()->json(['data' => $emptyarray,'message' => 'Notification List'],200);
		}
	}
}