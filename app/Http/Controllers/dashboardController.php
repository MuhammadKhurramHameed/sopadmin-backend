<?php

namespace App\Http\Controllers;

use DB;

class dashboardController extends Controller
{
    public function admindashboard(){
        $studentCount=DB::table('users')->where('role_id', '=', 1)->count();
        
        $usersCount=DB::table('users')->where('role_id', '!=', 1)->count();
       
        $examCount=DB::table('exam')->count();
      
        $requestefPayment=DB::table('payment')->where('paymentstatus_id', '=', 1)->sum('payment_amount');
        $approvedPayment=DB::table('payment')->where('paymentstatus_id', '=', 2)->sum('payment_amount');
        $declinedPayment=DB::table('payment')->where('paymentstatus_id', '=', 3)->sum('payment_amount');

        $activeticket=DB::table('contacts')->where('contact_status', '=', 1)->count();
        $closeticket=DB::table('contacts')->where('contact_status', '=', 2)->count();
        $openticket=DB::table('contacts')->where('contact_status', '=', 3)->count();
        $allicket=DB::table('contacts')->count();
      
        $requestpaymentgraph;
        $approvepaymentgraph;
        $declinepaymentgraph;
        for($i = 1; $i <= 12; $i++){
            if($i <= 9){
                $month = date('Y').'-0'.$i;
            }else{
                $month = date('Y').'-'.$i;
            }
            $requestpaymentgraph[] = DB::table('payment')->where('paymentstatus_id', '=', 1)
            ->where('payment_date', 'like', $month.'%')->sum('payment_amount');
            $approvepaymentgraph[] = DB::table('payment')->where('paymentstatus_id', '=', 2)
            ->where('payment_date', 'like', $month.'%')->sum('payment_amount');
            $declinepaymentgraph[] = DB::table('payment')->where('paymentstatus_id', '=', 3)
            ->where('payment_date', 'like', $month.'%')->sum('payment_amount');
        }
        $monthlystudents = DB::table('users')->where('role_id', '=', 1)->where('created_at', 'like', date('Y-m-d').'%')->get();
        $monthlypayments = DB::table('paymentdetails')->where('paymentstatus_id', '=', 2)->where('payment_date', 'like', date('Y-m').'%')->get();
        $data = array(
            'studentCount'      => $studentCount,
            'usersCount'        => $usersCount,
            'examCount'         => $examCount,
            'requestefPayment'  => $requestefPayment,
            'approvedPayment'   => $approvedPayment,
            'declinedPayment'   => $declinedPayment,
            'activeticket'      => $activeticket,
            'closeticket'       => $closeticket,
            'openticket'        => $openticket,
            'allicket'          => $allicket,
        );
        $studentimagepath = 'http://146.71.76.22/sopstudentnewbackend/public/userimage/';
        return response()->json(['data' => $data, 'requestpaymentgraph' => $requestpaymentgraph,
         'approvepaymentgraph' => $approvepaymentgraph, 'declinepaymentgraph' => $declinepaymentgraph,
          'monthlystudents' => $monthlystudents, 'monthlypayments' => $monthlypayments,
           'studentimagepath' => $studentimagepath, 'message' => 'Admin Dashboard'],200);
   }
}
