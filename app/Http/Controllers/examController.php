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

class examController extends Controller
{
	public $emptyarray = array();
	public function saveexam(Request $request){
		$validate = Validator::make($request->all(), [
	    	'exam_name'  		=> 'required',
			'examLevel'         => 'required',
	    	'exam_totalmarks'  	=> 'required',
	    	'exam_instructions' => 'required',
	    	'exam_syllabus'  	=> 'required',
	    	'exam_startdate'  	=> 'required',
	    	'exam_enddate'  	=> 'required',
	    	'exam_duration'  	=> 'required',
	    	'program_id'  		=> 'required',
	    	'batch_id'  		=> 'required',
	    	'grade_id'  		=> 'required',
	    	'programphase_id'  	=> 'required',
	    	'subject_id'  		=> 'required',
	    	'status_id'  		=> 'required',
	    ]);
		if ($validate->fails()) {    
			return response()->json($validate->errors(), 400);
		}
		$exam_slug = str_slug($request->exam_name);
		$adds = array(
			'examLevel'         =>$request->examLevel,
			'exam_name' 		=> $request->exam_name,
			'exam_slug' 		=> $exam_slug,
			'exam_totalmarks' 	=> $request->exam_totalmarks,
			'exam_instructions' => $request->exam_instructions,
			'exam_syllabus'		=> $request->exam_syllabus,
			'exam_startdate'	=> $request->exam_startdate,
			'exam_enddate'		=> $request->exam_enddate,
			'exam_duration'		=> $request->exam_duration,
			'program_id'		=> $request->program_id,
			'batch_id'			=> $request->batch_id,
			'grade_id'			=> $request->grade_id,
			'programphase_id'	=> $request->programphase_id,
			'subject_id'		=> $request->subject_id,
			'status_id'			=> $request->status_id,
			'created_by'		=> $request->id,
			'created_at'		=> date('Y-m-d h:i:s'),
		);
		$save = DB::table('exam')->insert($adds);
		if($save){
			return response()->json(['message' => 'Exam Saved Successfully'],200);
		}else{
			return response()->json("Oops! Something Went Wrong", 400);
		}
	}
	public function updateexam(Request $request){
		$validate = Validator::make($request->all(), [
			'exam_id'  			=> 'required',
			'examLevel'         => 'required',
			'exam_name'  		=> 'required',
	    	'exam_totalmarks'  	=> 'required',
	    	'exam_instructions' => 'required',
	    	'exam_syllabus'  	=> 'required',
	    	'exam_startdate'  	=> 'required',
	    	'exam_enddate'  	=> 'required',
	    	'exam_duration'  	=> 'required',
	    	'program_id'  		=> 'required',
	    	'batch_id'  		=> 'required',
	    	'grade_id'  		=> 'required',
	    	'programphase_id'  	=> 'required',
	    	'subject_id'  		=> 'required',
	    	'status_id'  		=> 'required',
	    ]);
		if ($validate->fails()) {    
			return response()->json($validate->errors(), 400);
		}
		$exam_slug = str_slug($request->exam_name);
		$adds = array(
			'examLevel'         =>$request->examLevel,
			'exam_name' 		=> $request->exam_name,
			'exam_slug' 		=> $exam_slug,
			'exam_totalmarks' 	=> $request->exam_totalmarks,
			'exam_instructions' => $request->exam_instructions,
			'exam_syllabus'		=> $request->exam_syllabus,
			'exam_startdate'	=> $request->exam_startdate,
			'exam_enddate'		=> $request->exam_enddate,
			'exam_duration'		=> $request->exam_duration,
			'program_id'		=> $request->program_id,
			'batch_id'			=> $request->batch_id,
			'grade_id'			=> $request->grade_id,
			'programphase_id'	=> $request->programphase_id,
			'subject_id'		=> $request->subject_id,
			'status_id'			=> $request->status_id,
			'updated_by'		=> $request->id,
			'updated_at'		=> date('Y-m-d h:i:s'),
		);
		$save = DB::table('exam')
		->where('exam_id','=',$request->exam_id)
		->update($adds); 
		if($save){
			return response()->json(['message' => 'Exam Updated Successfully'],200);
		}else{
			return response()->json("Oops! Something Went Wrong", 400);
		}
	}
	public function examlist(Request $request){
		$daat = DB::table('exam')->join('examLevel', 'exam.examLevel', '=', 'examLevel.examLevelId')
		->select('*')
		->where('status_id','=',1)
		->get();		
		if($daat){
			return response()->json(['data' => $daat,'message' => 'Exam List'],200);
		}else{
			$emptyarray = array();
			return response()->json(['data' => $emptyarray,'message' => 'Exam List'],200);
		}
	}
	public function deleteexam(Request $request){
		$validate = Validator::make($request->all(), [
	    	'exam_id'  	=> 'required',
	    ]);
		if ($validate->fails()) {    
			return response()->json($validate->errors(), 400);
		}
		$adds = array(
			'status_id' 	=> 3,
		);
		$save = DB::table('exam')
		->where('exam_id','=',$request->exam_id)
		->update($adds); 
		if($save){
			return response()->json(['message' => 'Exam Deleteds Successfully'],200);
		}else{
			return response()->json("Oops! Something Went Wrong", 400);
		}
	}
	public function savequestion(Request $request){
		$validate = Validator::make($request->all(), [
	    	'question_name'  			=> 'required',
	    	'question_explanation'  	=> 'required',
	    	'exam_slug'  				=> 'required',
	    	'option_correctone'  		=> 'required',
	    	'option_correcttwo'  		=> 'required',
	    	'option_correctthree'  		=> 'required',
	    	'option_correctfour'  		=> 'required',
	    	'option_nameone'  			=> 'required',
	    	'option_nametwo'  			=> 'required',
	    	'option_namethree'  		=> 'required',
	    	'option_namefour'  			=> 'required',
	    	'questiontype_id'  			=> 'required',
	    	'questionlevel_id'  		=> 'required',
	    ]);
		if ($validate->fails()) {    
			return response()->json($validate->errors(), 400);
		}
		$adds = array(
			'question_name' 		=> $request->question_name,
			'question_explanation' 	=> $request->question_explanation,
			'questiontype_id' 		=> $request->questiontype_id,
			'questionlevel_id' 		=> $request->questionlevel_id,
			'exam_slug' 			=> $request->exam_slug,
			'status_id'				=> 1,
			'created_by'			=> $request->id,
			'created_at'			=> date('Y-m-d h:i:s'),
		);
		$save = DB::table('question')->insert($adds);
		$question_id = DB::getPdo()->lastInsertId();
		$option_nameone;
		$option_nametwo;
		$option_namethree;
		$option_namefour;
		if($request->questiontype_id == 3){
			if ($request->has('option_nameone')) {
				if( $request->option_nameone->isValid()){
					$number = rand(1,999);
					$numb = $number / 7 ;
					$name = "image";
					$extension = $request->option_nameone->extension();
					$option_nameone  = date('Y-m-d')."_".$numb."_".$name."_.".$extension;
					$image_name = $request->option_nameone->move(public_path('questionimage/'.$question_id),$option_nameone);
					$img = Image::make($image_name)->resize(800,800, function($constraint) {
						$constraint->aspectRatio();
					});
					$img->save($image_name);
				}
			}
			if ($request->has('option_nametwo')) {
				if( $request->option_nametwo->isValid()){
					$number = rand(1,999);
					$numb = $number / 7 ;
					$name = "image";
					$extension = $request->option_nametwo->extension();
					$option_nametwo  = date('Y-m-d')."_".$numb."_".$name."_.".$extension;
					$image_name = $request->option_nametwo->move(public_path('questionimage/'.$question_id),$option_nametwo);
					$img = Image::make($image_name)->resize(800,800, function($constraint) {
						$constraint->aspectRatio();
					});
					$img->save($image_name);
				}
			}
			if ($request->has('option_namethree')) {
				if( $request->option_namethree->isValid()){
					$number = rand(1,999);
					$numb = $number / 7 ;
					$name = "image";
					$extension = $request->option_namethree->extension();
					$option_namethree  = date('Y-m-d')."_".$numb."_".$name."_.".$extension;
					$image_name = $request->option_namethree->move(public_path('questionimage/'.$question_id),$option_namethree);
					$img = Image::make($image_name)->resize(800,800, function($constraint) {
						$constraint->aspectRatio();
					});
					$img->save($image_name);
				}
			}
			if ($request->has('option_namefour')) {
				if( $request->option_namefour->isValid()){
					$number = rand(1,999);
					$numb = $number / 7 ;
					$name = "image";
					$extension = $request->option_namefour->extension();
					$option_namefour  = date('Y-m-d')."_".$numb."_".$name."_.".$extension;
					$image_name = $request->option_namefour->move(public_path('questionimage/'.$question_id),$option_namefour);
					$img = Image::make($image_name)->resize(800,800, function($constraint) {
						$constraint->aspectRatio();
					});
					$img->save($image_name);
				}
			}
		}else{
			$option_nameone = $request->option_nameone;
			$option_nametwo = $request->option_nametwo;
			$option_namethree = $request->option_namethree;
			$option_namefour = $request->option_namefour;
		}
		if($request->questiontype_id == 1 || $request->questiontype_id == 3){
			$optionone = array(
				'option_name' 		=> $option_nameone,
				'option_iscorrect' 	=> $request->option_correctone,
				'question_id' 		=> $question_id,
				'status_id'			=> 1,
			);
			DB::table('option')->insert($optionone);
			$optiontwo = array(
				'option_name' 		=> $option_nametwo,
				'option_iscorrect' 	=> $request->option_correcttwo,
				'question_id' 		=> $question_id,
				'status_id'			=> 1,
			);
			DB::table('option')->insert($optiontwo);
			$optionthree = array(
				'option_name' 		=> $option_namethree,
				'option_iscorrect' 	=> $request->option_correctthree,
				'question_id' 		=> $question_id,
				'status_id'			=> 1,
			);
			DB::table('option')->insert($optionthree);
			$optionfour = array(
				'option_name' 		=> $option_namefour,
				'option_iscorrect' 	=> $request->option_correctfour,
				'question_id' 		=> $question_id,
				'status_id'			=> 1,
			);
			DB::table('option')->insert($optionfour);
		}else{
			$optionone = array(
				'option_name' 		=> $option_nameone,
				'option_iscorrect' 	=> $request->option_correctone,
				'question_id' 		=> $question_id,
				'status_id'			=> 1,
			);
			DB::table('option')->insert($optionone);
		}
		if($save){
			return response()->json(['exam_slug' => $request->exam_slug, 'message' => 'Question Saved Successfully'],200);
		}else{
			return response()->json("Oops! Something Went Wrong", 400);
		}
	}
	public function questionlist(Request $request){
		$daat = DB::table('questtionlist')
		->select('*')
		->where('status_id','=',1)
		->paginate(30);		
		if($daat){
			return response()->json(['data' => $daat,'message' => 'Question List'],200);
		}else{
			$emptyarray = array();
			return response()->json(['data' => $emptyarray,'message' => 'Question List'],200);
		}
	}
	public function deletequestion(Request $request){
		$validate = Validator::make($request->all(), [
	    	'question_id'  	=> 'required',
	    ]);
		if ($validate->fails()) {    
			return response()->json($validate->errors(), 400);
		}
		$adds = array(
			'status_id' 	=> 3,
		);
		$save = DB::table('question')
		->where('question_id','=',$request->question_id)
		->update($adds); 
		if($save){
			return response()->json(['message' => 'Question Deleteds Successfully'],200);
		}else{
			return response()->json("Oops! Something Went Wrong", 400);
		}
	}
	public function filterquestionlist(Request $request){
		$exam_id = (int)$request->exam_id;
		$grade_id = (int)$request->grade_id;
		$program_id = (int)$request->program_id;
		$subject_id = (int)$request->subject_id;
		$daat=DB::select(DB::raw('SELECT * FROM questtionlist WHERE (case when ' . ($exam_id) . ' =0 THEN exam_id in (SELECT exam_id FROM exam) ELSE exam_id=' . ($exam_id) . ' END) AND (case when ' . ($grade_id) . ' =0 THEN grade_id in (SELECT id FROM grade) ELSE grade_id=' . ($grade_id) . ' END) AND (case when ' . ($program_id) . ' =0 THEN program_id in (SELECT id FROM program) ELSE program_id=' . ($program_id) . ' END) AND (case when ' . ($subject_id) . ' =0 THEN subject_id in (SELECT id FROM subjects) ELSE subject_id=' . ($subject_id) . ' END)'
		));
		if($daat){
			return response()->json(['data' => $daat,'message' => 'Filter Question List'],200);
		}else{
			$emptyarray = array();
			return response()->json(['data' => $emptyarray,'message' => 'Filter Question List'],200);
		}
	}
	public function questionapprovallist(Request $request){
		$validate = Validator::make($request->all(), [
	    	'exam_slug'  		=> 'required',
	    	'questionlevel_id'  => 'required',
	    ]);
		if ($validate->fails()) {    
			return response()->json($validate->errors(), 400);
		}
		$daat = DB::table('question')
		->select('*')
		->where('exam_slug','=',$request->exam_slug)
		->where('questionlevel_id','=',$request->questionlevel_id)
		->where('questionstatus_id','=',1)
		->where('status_id','=',1)
		->get();		
		if($daat){
			return response()->json(['data' => $daat,'message' => 'Question Approval List'],200);
		}else{
			$emptyarray = array();
			return response()->json(['data' => $emptyarray,'message' => 'Question Approval List'],200);
		}
	}
	public function approvequestion(Request $request){
		$validate = Validator::make($request->all(), [
	    	'question_id'  	=> 'required',
	    ]);
		if ($validate->fails()) {    
			return response()->json($validate->errors(), 400);
		}
		$adds = array(
			'questionstatus_id' 	=> 2,
		);
		$save = DB::table('question')
		->where('question_id','=',$request->question_id)
		->update($adds); 
		if($save){
			return response()->json(['message' => 'Question Approve Successfully'],200);
		}else{
			return response()->json("Oops! Something Went Wrong", 400);
		}
	}
}