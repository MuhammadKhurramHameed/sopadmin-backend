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

class lmsController extends Controller
{
	public $emptyarray = array();
	public function uploadlms(Request $request){
		$validate = Validator::make($request->all(), [
	    	'lms_title'  		=> 'required',
	    	'lms_type'  		=> 'required',
	    	'lms_document'  	=> 'required',
	    	'program_id'  	    => 'required',
	    ]);
		if ($validate->fails()) {    
			return response()->json($validate->errors(), 400);
		}
		if($request->lms_document != "-"){
			if ($request->has('lms_document')) {
				if( $request->lms_document->isValid()){
					$number = rand(1,999);
					$numb = $number / 7 ;
					$name = "lms_document";
					$extension = $request->lms_document->extension();
					$lms_document  = date('Y-m-d')."_".$numb."_".$name."_.".$extension;
					$request->lms_document->move(public_path('lms_document/'),$lms_document);
				}
			}
		}
		$adds = array(
			'lms_title' 		=> $request->lms_title,
			'lms_type' 			=> $request->lms_type,
			'lms_document' 		=> $lms_document,
			'program_id' 		=> $request->program_id,
			'lms_extension' 	=> $extension,
			'status_id'			=> 1,
			'created_by'		=> $request->id,
			'created_at'		=> date('Y-m-d h:i:s'),
		);
		$save = DB::table('lms')->insert($adds);
		if($save){
			return response()->json(['message' => 'LMS Uploaded Successfully'],200);
		}else{
			return response()->json("Oops! Something Went Wrong", 400);
		}
	}
	public function lmslist(Request $request){
		$daat = DB::table('lms')
		->select('*')
		->paginate(30);		
		if($daat){
			return response()->json(['data' => $daat,'message' => 'LMS List'],200);
		}else{
			$emptyarray = array();
			return response()->json(['data' => $emptyarray,'message' => 'LMS List'],200);
		}
	}
}