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

class userController extends Controller
{
	public $emptyarray = array();
	public function userlist(Request $request){
		$data=DB::table('users')
		->select('id  as user_id', 'email', 'role_id', 'image',  'name' , 'phone', 'address', 'gender', 'dob', 'username')
		->where('status_id', '=', 1)
		->where('role_id', '!=', 1)
		->paginate(30);
		$path = URL::to( '/' ).'/public/userimage/';
		if($data){
			return response()->json(['data' => $data, 'path' =>  $path, 'message' => 'User List'],200);
		}else{
			$emptyarray = array();
			return response()->json(['data' => $emptyarray,'message' => 'User List'],200);
		}
	}
	public function userlistdropdown(Request $request){
		$data=DB::table('users')
		->select('id  as user_id', 'name')
		->where('status_id', '=', 1)
		->where('role_id', '!=', 1)
		->get();
		if($data){
			return response()->json(['data' => $data, 'message' => 'Dropdown User List'],200);
		}else{
			$emptyarray = array();
			return response()->json(['data' => $emptyarray,'message' => 'Dropdown User List'],200);
		}
	}
	public function controllerlist(Request $request){
		$data=DB::table('users')
		->select('id  as user_id', 'name')
		->where('status_id', '=', 1)
		->where('role_id', '=', 5)
		->get();
		if($data){
			return response()->json(['data' => $data, 'message' => 'Controller List'],200);
		}else{
			$emptyarray = array();
			return response()->json(['data' => $emptyarray,'message' => 'Controller List'],200);
		}
	}
	public function invigilatorist(Request $request){
		$data=DB::table('users')
		->select('id  as user_id', 'name')
		->where('status_id', '=', 1)
		->where('role_id', '=', 6)
		->get();
		if($data){
			return response()->json(['data' => $data, 'message' => 'Invigilator List'],200);
		}else{
			$emptyarray = array();
			return response()->json(['data' => $emptyarray,'message' => 'Invigilator List'],200);
		}
	}
	public function saveuser(Request $request){
		$validate = Validator::make($request->all(), [
	    	'name'  	=> 'required',
	    	'email'  	=> 'required',
	    	'phone'  	=> 'required',
	    	'gender'  	=> 'required',
	    	'dob'  		=> 'required',
	    	'image'  	=> 'required',
	    	'address'  	=> 'required',
	    	'role_id'  	=> 'required',
	    	'password'  => 'required',
	    ]);
		if ($validate->fails()) {    
			return response()->json($validate->errors(), 400);
		}
		if($request->image != "-"){
			if ($request->has('image')) {
				if( $request->image->isValid()){
					$number = rand(1,999);
					$numb = $number / 7 ;
					$name = "image";
					$extension = $request->image->extension();
					$userimage  = date('Y-m-d')."_".$numb."_".$name."_.".$extension;
					$request->image->move(public_path('userimage/'),$userimage);
				}
			}
		}
 		$adds = array(
			'name' 			=> $request->name,
			'email' 		=> $request->email,
			'phone' 		=> $request->phone,
			'gender' 		=> $request->gender,
			'dob'			=> $request->dob,
			'image' 		=> $userimage,
			'address' 		=> $request->address,
			'role_id' 		=> $request->role_id,
			'password' 		=> bcrypt( $request->password ),
		);
		$save = DB::table('users')->insert($adds);
		if($save){
			return response()->json(['message' => 'User Created Successfully'],200);
		}else{
			return response()->json("Oops! Something Went Wrong", 400);
		}	
	}
	public function deleteuser(Request $request){
		$validate = Validator::make($request->all(), [
	    	'userId'  	=> 'required',
	    ]);
		if ($validate->fails()) {    
			return response()->json($validate->errors(), 400);
		}
		$adds = array(
			'status_id'		=> 3,
		);
		$save = DB::table('users')
		->where('id','=',$request->userId)
		->update($adds); 
		if($save){
			return response()->json(['message' => 'User Deleted Successfully'],200);
		}else{
			return response()->json("Oops! Something Went Wrong", 400);
		}
	}
	public function updateuser(Request $request){
		$validate = Validator::make($request->all(), [
	    	'userId'  	=> 'required',
	    	'name'  	=> 'required',
	    	'email'  	=> 'required',
	    	'phone'  	=> 'required',
	    	'gender'  	=> 'required',
	    	'dob'  		=> 'required',
	    	'image'  	=> 'required',
	    	'address'  	=> 'required',
	    	'role_id'  	=> 'required',
	    ]);
		if ($validate->fails()) {    
			return response()->json($validate->errors(), 400);
		}
		if($request->image != "-"){
			if ($request->has('image')) {
				if( $request->image->isValid()){
					$number = rand(1,999);
					$numb = $number / 7 ;
					$name = "image";
					$extension = $request->image->extension();
					$userimage  = date('Y-m-d')."_".$numb."_".$name."_.".$extension;
					$request->image->move(public_path('userimage/'),$userimage);
					DB::table('users')
					->where('id','=',$request->userId)
					->update([
						'image' 	=> $userimage,
					]);
				}
			}
		}
 		$adds = array(
			'name' 			=> $request->name,
			'email' 		=> $request->email,
			'phone' 		=> $request->phone,
			'gender' 		=> $request->gender,
			'dob'			=> $request->dob,
			'address' 		=> $request->address,
			'role_id' 		=> $request->role_id,
		);
		$save = DB::table('users')
		->where('id','=',$request->userId)
		->update($adds);
		if($save){
			return response()->json(['message' => 'User Updated Successfully'],200);
		}else{
			return response()->json("Oops! Something Went Wrong", 400);
		}	
	}
	public function userdetail(Request $request){
		$validate = Validator::make($request->all(), [
	    	'userId'  	=> 'required',
	    ]);
		if ($validate->fails()) {    
			return response()->json($validate->errors(), 400);
		}
		$data=DB::table('users')
		->select('id  as user_id', 'email', 'role_id', 'image',  'name' , 'phone', 'address', 'gender', 'dob', 'username')
		->where('status_id', '=', 1)
		->where('id', '=', $request->userId)
		->where('role_id', '!=', 1)
		->first();
		$path = URL::to( '/' ).'/public/userimage/';
		if($data){
			return response()->json(['data' => $data, 'message' => 'User Details'],200);
		}else{
			$emptyarray = array();
			return response()->json(['data' => $emptyarray,'message' => 'User Details'],200);
		}
	}
}