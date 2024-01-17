<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use DB;
use Illuminate\Http\Request;
use Validator;

class resultController extends Controller
{
    public function ResultEntry(Request $request){
        $validate = Validator::make($request->all(),[
            "block_id"    => 'required',
        ]);
        if ($validate->fails()) {    
            return response()->json($validate->errors(), 400);
        } 
        $presentCandidates = DB::table('programregistrationdetails')
        ->select('*')
        ->where('block_id', '=', $request->block_id)
        ->where('is_present', '=', 1)
        ->where('obtainedMarks', '=', NULL)
        ->get();
        if($presentCandidates){
            return response()->json(['data' => $presentCandidates, 'message' => 'Exam Appear Student List'],200);
        }else{
            return response()->json(['data' => array(), 'message' => 'Exam Appear Student List'],200);
        }
    }
    public function markResult(Request $request){
        $validate = Validator::make($request->all(), [
            'obtainedMarks' => 'required',
            'user_id'       => 'required'
        ]);
        if ($validate->fails()) {    
            return response()->json($validate->errors(), 400);
        }
        $obtained=($request->obtainedMarks);
        $gradedata= DB::table('resultgrade')->where('maxPercentage', '>=', $obtained)->first();
        $grade=$gradedata->gradeId;
        $enterResult=DB::table('programregistrations')->where('user_id', '=', $request->user_id)->update(['obtainedMarks'=>$request->obtainedMarks, 'gradeAchieved'=>$grade ]);
        if($enterResult){
            return response()->json(['data' => $enterResult,  'message' => 'Result Has Been Uploaded'],200);
        }else{
            return response()->json(['data' => array(), 'message' => 'Result Has Not Been Uploaded'],200);
        }

    }
    public function meritList(Request $request){
        $validate = Validator::make($request->all(), [
            "block_id"    => 'required'
        ]);      
        if ($validate->fails()) {    
            return response()->json($validate->errors(), 400);
        }
        $presentCandidates = DB::table('resultdetails')
        ->select('*')
        ->where('block_id', '=', $request->block_id)
        ->where('is_present', '=', 1)
        ->orderBy("obtainedMarks", 'desc')
        ->get();
        $path = 'http://146.71.76.22/sopstudentnewbackend/public/userimage/';
        if($presentCandidates){
            return response()->json(['data' => $presentCandidates, 'path'=> $path,  'message' => 'Student Result'],200);
        }else{
            return response()->json(['data' => array(), 'message' => 'Student Result'],200);
        }
    }
    public function ResultList(Request $request){
        $validate = Validator::make($request->all(), [
            "block_id"    => 'required'
        ]);
        
        if ($validate->fails()) {    
            return response()->json($validate->errors(), 400);
        }
        $presentCandidates = DB::table('resultdetails')
        ->select('*')
        ->where('block_id', '=', $request->block_id)
        ->where('is_present', '=', 1)
        ->get();
        $path = 'http://146.71.76.22/sopstudentnewbackend/public/userimage/';
        if($presentCandidates){
            return response()->json(['data' => $presentCandidates, 'path'=> $path,  'message' => 'Student Result List'],200);
        }else{
            return response()->json(['data' => array(), 'message' => 'Student Result List'],200);
        }
    }
    //Online Exam Result
    public function onlineResultgenerator(Request $request){
        $validate= Validator::make($request->all(), [
            // 'user_id' => 'required',
            'exam_id' => 'required'
        ]);
        if($validate->fails()){
            return response()->json($validate->errors(), 400);
        }
        //get all candidates who attempted the exam
        $candidates=DB::table('answer')->where('exam_id', '=', $request->exam_id)->get();
        foreach($candidates as $candidate){
            //list of selected correct options
            $ListOfSelectedOptions=DB::table('option')->where('question_id', '=', $candidate->question_id)
            ->where('option_id', '=', $candidate->option_id)->where('option_iscorrect', '=', 1)->first();
            if($ListOfSelectedOptions){
                $totalMarks=DB::table('option')->where('question_id','=', $candidate->question_id)
                ->sum('option_iscorrect');
                $marks=($ListOfSelectedOptions->option_iscorrect)/$totalMarks;
                $saveMarks=DB::table('answer')->where('answer_id', '=', $candidate->answer_id)
                ->update(['marks'=> $marks]);
            }
        }
        $users=DB::table('examfinish')->where('exam_slug', '=',  $request->exam_id)->get();
        foreach($users as $user){
            $obtainedMarks=DB::table('answer')->where('exam_id', '=', $request->exam_id)
            ->where('user_id', '=', $user->user_id)->sum('marks');
            $totalMarks=DB::table('exam')->where('exam_id', '=', $request->exam_id)->first();
            $result=$obtainedMarks/(int)($totalMarks->exam_totalmarks)*100;
            $gradedata= DB::table('resultgrade')->where('maxPercentage', '>=', $result)->first();

            $saveresult=DB::table('examfinish')->where('exam_slug', '=',  $request->exam_id)
            ->where('user_id', '=', $user->user_id)->update(['totalMarks'=> $obtainedMarks,'totalPercentage'=>$result, 'resultGrade'=>$gradedata->gradeId]);
        }
        return response()->json(['message'=> 'marks has been calculated!']);
    }
    public function getResult(Request $request){
        $validate= Validator::make($request->all(), [
            'exam_id' => 'required'
        ]);
        $saveresult=DB::table('examfinish')->join('users', 'examfinish.user_id', '=', 'users.id')
        ->select(['users.id as user_id', 'users.name', 'users.image', 'examfinish.exam_slug', 
        'examfinish.totalPercentage', 'examfinish.resultGrade', 'examfinish.totalmarks'])->where('examfinish.exam_slug', '=',  
        $request->exam_id)->get();
        

        $path = 'http://146.71.76.22/sopstudentnewbackend/public/userimage/';
        return response()->json(["userData"=>$saveresult,  "path"=>$path, "message" => "successfully shown results"]);
    }

    public function getPrograms(){
        $programs=DB::table('program')->select(['id', 'name'])->get();
        return response()->json(["program"=>$programs],200);
    }
    public function getExamByProgramId(Request $request){
        if($request->examLevelId==null){
            $exam=DB::table('exam')->where('program_id', '=', $request->program_id)->get();
            return response()->json(["exam"=>$exam], 200);
        }else{
            $exam=DB::table('exam')->where('program_id', '=', $request->program_id)->where('examLevel', $request->examLevelId)->get();
            return response()->json(["exam"=>$exam], 200);
        }
    }
    public function getExamLevel(){
        $examLevel=DB::table('examLevel')->get();
        return response()->json(['examLevel'=> $examLevel], 200);
    }
}
