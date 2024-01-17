<?php

namespace App\Http\Controllers;

use DB;
use Illuminate\Http\Request;
use Validator;
use URL;
use Image;

class BlogAndAlbumController extends Controller
{
    public function SaveBlog(Request $request){
        $validate = Validator::make($request->all(), [
            "blogTitle" => 'required',
            "blogImage" => 'required|image',
            "content" => 'required',
            "status" =>'required'
        ]);
        if($validate->fails()){
            return response()->json($validate->errors(), 400);
        }
                $number = rand(1,999)/7;
                $name = "blogImage";
                $extension = $request->blogImage->extension();
                $blogImage  = date('Y-m-d')."_".$number."_".$name."_.".$extension;
                $image  = $request->blogImage->move(public_path('blogImage/'),$blogImage);
                $img = Image::make($image)->resize(800,800, function($constraint) {
                    $constraint->aspectRatio();
                });
                $img->save($image);

        $blogData= array(
            "blogTitle" => $request->blogTitle,
            "blogImage" => $blogImage,
            "content" => $request->content,
            "status" => $request->staus,
            "created_at" => date('y-m-d')
        );
        $saveBlog = DB::table('blogs')->insert($blogData);
        if($saveBlog){
            return response()->json(["message"=>"Blog have been saved"], 200);
        }else{
            return response()->json('Ooop! something went wrong');
        }
    }
    public function ActiveOrDeactiveBlog(Request $request){
        $validate =Validator::make($request->all(),[
            "status" => "required",
            "blogId" => 'required'
        ]);
        if($validate->fails()){
            return response()->json($validate->errors(), 400);
        }
        $changeStatus= DB::table('blogs')->where('blogId', $request->blogId)->update(['status'=>$request->status]);
        if($changeStatus){
            if($request->status==1){
                return response()->json(["message"=> "Blog Has Been Active"],200);
            }else if($request->status==2){
                return response()->json(["message"=> "Blog Has Been De-active"],200);

            }else if($request->status==3){
                return response()->json(["message"=> "Blog Has Been Deleted"],200);

            }
        }else{
            return response()->json("Ooops! something went wrong", 200);
        }
    }
    public function blogList(){
        $blogList= DB::table('blogs')->whereIn('status', [1,2])->orderBydesc('blogId')->get();
        $path = URL::to( '/' ).'/public/blogImage/';
        if($blogList){
            return response()->json(["blogList" => $blogList, 'path' => $path], 200);
        }else{
            return response()->json("Oops! something went wrong", 400);
        }
    }
    public function updateBlog(Request $request){
        $validate = Validator::make($request->all(), [
            "blogId"        => "required",
            "blogTitle"     => 'required',
            "content"       => 'required',
            "blogImage"     => 'required',
        ]);
        if($validate->fails()){
            return response()->json($validate->errors(), 400);
        }
        if($request->blogImage != "-"){
			if ($request->has('blogImage')) {
				if( $request->blogImage->isValid()){
					$number = rand(1,999);
					$numb = $number / 7 ;
					$name = "blogImage";
					$extension = $request->blogImage->extension();
					$blogImage  = date('Y-m-d')."_".$numb."_".$name."_.".$extension;
					$blogImage  = $request->blogImage->move(public_path('blogImage/'),$blogImage);
                    $img = Image::make($blogImage)->resize(800,800, function($constraint) {
                        $constraint->aspectRatio();
                    });
                    $img->save($blogImage);
                    $blogImage  = date('Y-m-d')."_".$numb."_".$name."_.".$extension;
					$saveBlog = DB::table('blogs')
					->where('blogId','=',$request->blogId)
					->update([
						'blogImage' 	=> $blogImage,
					]);
				}
			}
		}
        $blogData= array(
            "blogTitle"     => $request->blogTitle,
            "content"       => $request->content,
            "status"        => $request->status,
            "updated_at"    => date('y-m-d')
        );
        $saveBlog = DB::table('blogs')->where('blogId', $request->blogId)->update($blogData);
        if($saveBlog){
            return response()->json(["message"=>"Blog have been updated"], 200);
        }else{
            return response()->json('Ooop! something went wrong',400);
        }
    }  
}
