<?php

namespace App\Http\Controllers;

use DB;
use Illuminate\Http\Request;
use Validator;
use Carbon\Carbon;

class employeeAttandance extends Controller
{
    private function generateDateRange(Carbon $start_date, Carbon $end_date){

        $dates = [];

        for($date = $start_date; $date->lte($end_date); $date->addDay()) {

            $dates[] = $date->format('Y-m-d');

        }

        return $dates;

    }
    public function markAttandance(Request $request){
        $validate = Validator::make($request->all(), [
            'employeeId' => 'required',
            'month' => 'required',
            'status' => 'required'
        ]);
        if($validate->failed()){
            return response()->json($validate->errors(), 400);
        }
        $attandanceData =array(
            'employeeId' => $request->employeeId,
            'month' => $request->month,
            'date' => date('Y-m-d'),
            'status' => $request->status
        );
        $attandance=DB::table('employeeattandance')->insert($attandanceData);
        if($attandance){
            return response()->json(['message' => 'Attandance marked Successfully'], 200);
        }else{
            return response()-> json(['oops! Something went wrong'], 400);
        }
    }

    public function employeelist(Request $request){
		$data=DB::table('users')
		->select('id  as user_id', 'email', 'role_id', 'image',  'name' , 'username', 'phone', 'address', 'gender', 'dob')
		->whereIn('role_id', [3,4])
		->where('status_id', '=', 1)
		->get();
		$path = 'http://146.71.76.22/sopstudentnewbackend/public/userimage/';
		if($data){
			return response()->json(['data' => $data,'message' => 'Employee List'],200);
		}else{
			$emptyarray = array();
			return response()->json(['data' => $emptyarray,'message' => 'Employee List'],200);
		}
	}

    public function isMarked(Request $request){
        $isMarked=DB::table('employeeattandance')->where('employeeId', $request->employeeId)->where('date', date('Y-m-d'))->first();
        if($isMarked){
            return response()->json(['ismarked' => true], 200);
        }else{
            return response()-> json(['ismarked' => false], 200);
        }
    }

    public function attandanceListForUser(Request $request){
        $validate = Validator::make($request->all(), [
            'month' => 'required',
            'userId' => 'required'
	    ]);
		if ($validate->fails()) {    
			return response()->json($validate->errors(), 400);
		}
        $empname = DB::table('users')
           ->where('id', $request->userId)->first();
        $month = explode('-',$request->month);
        $lastdate=cal_days_in_month(CAL_GREGORIAN,$month[1],$month[0]);
        $from = Carbon::parse($request->month.'-01');
        $to = Carbon::parse($request->month.'-'.$lastdate);
        $dates = $this->generateDateRange($from, $to);
        $sortdata = array();
        $index = 0;
        foreach ($dates as $dt) {
            $todaydata = DB::table('empattendancedetails')
            ->where('date', $dt)->where('employeeId', $request->userId)->first();
            if(isset($todaydata->status)){
                $sortdata[$index]['status'] = $todaydata->status;
                $sortdata[$index]['date'] = $dt;
                $sortdata[$index]['username'] = $empname;
            }else{
                $sortdata[$index]['status'] = "Absent";
                $sortdata[$index]['date'] = $dt;
                $sortdata[$index]['username'] = $empname;
            }
            $index++;
        }
        if($sortdata){
            return response()->json(['attandanceList' => $sortdata], 200);
        }else{
            return response()->json('Oops! something went wrong.', 400);
        }
    }

    public function markLeave(Request $request){
        $validate= Validator::make($request->all(), [
            'employeeId' => 'required',
            'month' => 'required',
            'date' => 'required',
            'status' => 'required'
        ]);
        if($validate->failed()){
            return response()->json($validate->errors(), 400);
        }

        $attandanceData = array(
            'employeeId' => $request->employeeId,
            'month' => $request->month,
            'date' => $request->date,
            'status' => $request->status
        );
        $saveData= DB::table('employeeattandance')->insert($attandanceData);
        if($saveData){
            return response()->json("Leave Application marked successfully", 200);
        }else{
            return response()->json('Ooops! something went wrong', 400);
        }
    }
}
