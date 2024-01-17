<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Validator;
use App\Http\Controllers\Controller;

class accountsController extends Controller
{
    public function paymentlist(Request $request){
        $validate = Validator::make($request->all(),[
            "program_id"        => 'required',
            "paymentstatus_id"  => 'required',
        ]);
        if ($validate->fails()) {    
            return response()->json($validate->errors(), 400);
        }
        $data = DB::table('paymentdetails')->where('program_id', '=', $request->program_id)->where('paymentstatus_id', '=', $request->paymentstatus_id)->where('paymentstatus_id', '=', $request->paymentstatus_id)->get();
        $path = 'http://146.71.76.22/sopstudentnewbackend/public/userimage/';
        if($data){
            return response()->json(['data' => $data, 'path' => $path, 'message' => 'Payment List'],200);
        }else{
            return response()->json(['data' => array(), 'message' => 'Payment List'],200);
        }
    }
    public function updatepaymentstatus(Request $request){
		$validate = Validator::make($request->all(), [
	    	'payment_id'  	    => 'required',
	    	'paymentstatus_id'  => 'required',
	   ]);
		if ($validate->fails()) {    
			return response()->json($validate->errors(), 400);
		}
		$adds = array(
			'paymentstatus_id' 			=> $request->paymentstatus_id,
		);
		$save = DB::table('payment')
		->where('payment_id','=',$request->payment_id)
		->update($adds); 
		if($save){
			return response()->json(['message' => 'Updated Successfully'],200);
		}else{
			return response()->json("Oops! Something Went Wrong", 400);
		}
	}
	public function Totaldebits(Request $request){
        $validate = Validator::make($request->all(), [
            'year' => 'required',
            'month' => 'required'
        ]);
        if($validate->failed()){
            return response()-> json($validate->errors(), 400);
        }

        $debits = DB::table('expenses')->where('date','like','%'.$request->year.'-'.$request->month.'%')->get();
        $totalDebitAmount =0;
        foreach($debits as $debit){
            $totalDebitAmount += $debit->expenseAmount;
        }
        return response()->json(['TotalDebitAmount'=> $totalDebitAmount], 200);
    }
    public function debitList(Request $request){
        $validate = Validator::make($request->all(), [
            'year' => 'required',
            'month' => 'required'
        ]);
        if($validate->failed()){
            return response()-> json($validate->errors(), 400);
        }

        $debits = DB::table('expenses')->where('date','like','%'.$request->year.'-'.$request->month.'%')->get();
        if($debits){
            return response()->json(['debitList'=> $debits], 200);
        }else{
            return response()->json('Ooops! something went wrong', 400);
        }
    }

    public function Totalcredits(Request $request){
        $validate = Validator::make($request->all(), [
            'month' => 'required'
        ]);
        if($validate->failed()){
            return response()->json($validate->errors(), 400);
        }
        $credits =DB::table('payment')->where('Payment_date','like',$request->month.'%')->get();
        $totalCredit=0;
        foreach($credits as $credit){
            $totalCredit += $credit->payment_amount;
        }
        return response() -> json(['TotalCredits' => $totalCredit], 200);
    }
    public function creditList(Request $request){
        $validate = Validator::make($request->all(), [
           'month' => 'required'
	    ]);
		if ($validate->fails()) {    
			return response()->json($validate->errors(), 400);
		}
        $credits =DB::table('payment')->where('Payment_date','like',$request->month.'%')->get();
        if($credits){
            return response()->json(['creditList'=> $credits], 200);
        }else{
            return response()->json('Ooops! something went wrong', 400);
        }
    }
    public function balancesheet(Request $request){
        $validate = Validator::make($request->all(), [
           'month' => 'required'
	    ]);
		if ($validate->fails()) {    
			return response()->json($validate->errors(), 400);
		}
        $firstdate = '01-'.$request->month;
        $data =DB::table('balancesheet')->where('balancesheet_date','like',$request->month.'%')->where('status_id','=',1)->get();
        $prevcredit =DB::table('balancesheet')->where('balancesheet_date','<',$firstdate)->where('status_id','=',1)->sum('balancesheet_credit');
        $prevdebit =DB::table('balancesheet')->where('balancesheet_date','<',$firstdate)->where('status_id','=',1)->sum('balancesheet_debit');
        $openingbalance = $prevcredit-$prevdebit;
        $creditsum =DB::table('balancesheet')->where('balancesheet_date','like',$request->month.'%')->where('status_id','=',1)->sum('balancesheet_credit');
        $debitsum =DB::table('balancesheet')->where('balancesheet_date','like',$request->month.'%')->where('status_id','=',1)->sum('balancesheet_debit');
        if($data){
            return response()->json(['data'=> $data, 'openingbalance' => $openingbalance, 'creditsum' => $creditsum, 'debitsum' => $debitsum], 200);
        }else{
            return response()->json('Ooops! something went wrong', 400);
        }
    }
}
