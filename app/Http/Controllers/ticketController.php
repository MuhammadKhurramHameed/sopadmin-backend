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

class ticketController extends Controller
{
	public $emptyarray = array();
	public function ticketlist(Request $request){
		$validate = Validator::make($request->all(), [
	    	'contact_status'  	=> 'required',
	    ]);
		if ($validate->fails()) {    
			return response()->json($validate->errors(), 400);
		}
		$data=DB::table('contacts')
		->select('*')
		->where('contact_status', '=', $request->contact_status)
		->paginate(30);
		$total=DB::table('contacts')
		->select('contact_id')
		->count();
		$active=DB::table('contacts')
		->select('contact_id')
		->where('contact_status', '=', 1)
		->count();
		$open=DB::table('contacts')
		->select('contact_id')
		->where('contact_status', '=', 3)
		->count();
		$close=DB::table('contacts')
		->select('contact_id')
		->where('contact_status', '=', 2)
		->count();
		$stats = array();
		$stats['total'] = $total;
		$stats['active'] = $active;
		$stats['open'] = $open;
		$stats['close'] = $close;
		if($data){
			return response()->json(['data' => $data, 'stats' => $stats, 'message' => 'Ticket List'],200);
		}else{
			$emptyarray = array();
			return response()->json(['data' => $emptyarray,'message' => 'Ticket List'],200);
		}
	}
	public function proceedticket(Request $request){
		$validate = Validator::make($request->all(), [
	    	'contact_id'  		=> 'required',
	    	'contact_status'  	=> 'required',
	    ]);
		if ($validate->fails()) {    
			return response()->json($validate->errors(), 400);
		}
		$adds = array(
			'contact_status' 	=> $request->contact_status,
		);
		$save = DB::table('contacts')
		->where('contact_id','=',$request->contact_id)
		->update($adds); 
		if($save){
			return response()->json(['message' => 'Request Proceed Successfully'],200);
		}else{
			return response()->json("Oops! Something Went Wrong", 400);
		}
	}
}