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

class faqController extends Controller
{
	public $emptyarray = array();
	public function savefaq(Request $request){
		$validate = Validator::make($request->all(), [
	    	'question'  => 'required',
	    	'answer'  	=> 'required',
	   ]);
		if ($validate->fails()) {    
			return response()->json($validate->errors(), 400);
		}
		$adds = array(
			'question' 		=> $request->question,
			'answer' 		=> $request->answer,
			'status_id'		=> 1,
			'created_by'	=> $request->id,
			'created_at'	=> date('Y-m-d h:i:s'),
		);
		$save = DB::table('faqs')->insert($adds);
		if($save){
			return response()->json(['message' => 'FAQ Saved Successfully'],200);
		}else{
			return response()->json("Oops! Something Went Wrong", 400);
		}
	}
	public function faqlist(Request $request){
		$daat = DB::table('faqs')
		->select('*')
		->get();		
		if($daat){
			return response()->json(['data' => $daat,'message' => 'FAQ List'],200);
		}else{
			$emptyarray = array();
			return response()->json(['data' => $emptyarray,'message' => 'FAQ List'],200);
		}
	}
}