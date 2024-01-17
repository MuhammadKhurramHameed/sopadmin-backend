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

class studentController extends Controller
{
	public $emptyarray = array();
	public function paidstudentlist(Request $request){
		$register=DB::table('paymentdetails')
		->select('user_id')
		->get();
		$sortregister = array();
		foreach($register as $registers){
			if(isset($registers->user_id)){
				$sortregister[] = $registers->user_id;
			}
		}
		$data=DB::table('paidstudent')
		->select('id  as user_id', 'email', 'role_id', 'image',  'name' , 'username', 'phone', 'address', 'gender', 'dob', 'programname')
		->where('paymentstatus_id', '=', 2)
		->where('paymentstatus', '=', 1)
// 		->whereIn('id',$sortregister)
		->where('role_id', '=', 1)
		->where('status_id', '=', 1)
		->paginate(30);
		$path = 'https://studentofpakistan.com/sopstudentnewbackend/public/userimage/';
		if($data){
			return response()->json(['data' => $data,'message' => 'Student List'],200);
		}else{
			$emptyarray = array();
			return response()->json(['data' => $emptyarray,'message' => 'Document List'],200);
		}
	}
	public function studentlist(Request $request){
		$data=DB::table('users')
		->select('id  as user_id', 'email', 'role_id', 'image',  'name' , 'username', 'phone', 'address', 'gender', 'dob')
		->where('role_id', '=', 1)
		->where('status_id', '=', 1)
		->paginate(30);
		$path = 'https://studentofpakistan.com/sopstudentnewbackend/public/userimage/';
		if($data){
			return response()->json(['data' => $data,'message' => 'Student List'],200);
		}else{
			$emptyarray = array();
			return response()->json(['data' => $emptyarray,'message' => 'Document List'],200);
		}
	}
	public function deletestudent(Request $request){
		$validate = Validator::make($request->all(), [
	    	'student_id'  	=> 'required',
	    ]);
		if ($validate->fails()) {    
			return response()->json($validate->errors(), 400);
		}
		$adds = array(
			'status_id'		=> 3,
		);
		$save = DB::table('users')
		->where('id','=',$request->student_id)
		->update($adds); 
		if($save){
			return response()->json(['message' => 'Student Deleted Successfully'],200);
		}else{
			return response()->json("Oops! Something Went Wrong", 400);
		}
	}
	public function studentdetails(Request $request){
		$validate = Validator::make($request->all(), [
	    	'user_id'  	=> 'required',
	    ]);
		if ($validate->fails()) {    
			return response()->json($validate->errors(), 400);
		}
		$data=DB::table('users')
		->select('*')
		->where('role_id', '=', 1)
		->where('status_id', '=', 1)
		->where('id', '=', $request->user_id)
		->first();
		$certificate=DB::table('certificate')
		->select('*')
		->where('status_id', '=', 1)
		->where('created_by', '=', $request->user_id)
		->get();
		$education=DB::table('education')
		->select('*')
		->where('status_id', '=', 1)
		->where('created_by', '=', $request->user_id)
		->get();
		$path = 'http://146.71.76.22/sopstudentnewbackend/public/userimage/';
		if($data){
			return response()->json(['personal' => $data, 'certificate' => $certificate, 'education' => $education,'message' => 'Student Details'],200);
		}else{
			$emptyarray = array();
			return response()->json(['data' => $emptyarray,'message' => 'Document Details'],200);
		}
	}
}