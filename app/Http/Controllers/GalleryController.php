<?php

namespace App\Http\Controllers;

use DB;
use URL;
use Illuminate\Http\Request;
use Image;
use Validator;

class GalleryController extends Controller
{
    public function createAlbum(Request $request){
        // dd($request->all());
        $validate = Validator::make($request->all(),[
            "coverTitle" => "required",
            "imageName.*" => "required|image|mimes:webp,jpeg,png,jpg,gif,svg"
        ]);
        if($validate->fails()){
            return response()->json($validate->errors(), 400);
        }
        $cover=null;
        $coverData=array(
            "coverTitle"=> $request->coverTitle,
            "status"=> 1,
            "created_at"=> date("y-m-d")
        );
        if($request->has('imageName')){
            $saveCover=DB::table('cover')->insert($coverData);
            if($saveCover){
                $id=DB::getPDO()->lastInsertId();
                $saveData=null;
                foreach($request->file('imageName') as $image){ 
                    $fileName=null;
                    if($cover==null){
                        $fileName="CoverImage".date("y-m-d")."_".(rand(1,999)/7).".".$image->getClientOriginalExtension();
                        $updateCover=DB::table('cover')->where('coverId', $id)->update(['coverImage'=>$fileName]);
                        $cover=1;
                    }else{
                        $fileName=date("y-m-d")."_".(rand(1,999)/7)."_albumImage.".$image->getClientOriginalExtension();
                    }
                    $saveImage=$image->move(public_path("/Gallery/albumNo-".$id), $fileName);
                    $comImg=Image::make($saveImage)->resize(800,800, function($constraint){
                        $constraint->aspectRatio();
                    });
                    $comImg->save($saveImage);
                    $albumData=array(
                        "coverId" => $id,
                        "imageName" => $fileName
                    );

                    $saveData=DB::table('album')->insert($albumData);
                }
                // dd($saveData);
                if($saveData){
                    return response()->json(["message"=> "album created successfully"], 200);
                }else{
                    return response()->json("Something went wrong", 400);
                }
            }else{
                return response()->json("Something went wrong!", 400);
            }

        }else{
            return response()->json(["message"=> "Please select image"], 400);
        }
    }
    public function listOfAlbums(){
        $albums=DB::table('cover')->where('status', 1)->get();
        $path = URL::to( '/' ).'/public/Gallery/albumNo-';
        if($albums){
            return response()->json(["Albums"=>$albums, "imagePath"=>$path], 200);
        }else{
            return response()->json("OOps! something went wrong", 400);
        }
    }
    public function listOfGalleryImages(Request $request){
        $validate= Validator::make($request->all(), [
            "coverId" => "required"
        ]);
        if($validate->fails()){
            return response()->json($validate->errors(), 400);
        }
        $gallery=DB::table('album')->where('coverId', $request->coverId)->get();
        $path = URL::to( '/' ).'/public/Gallery/albumNo-'.$request->coverId;
        if($gallery){
            return response()->json(["gallery"=>$gallery, "imagePath"=>$path], 200);
        }else{
            return response()->json("OOps! something went wrong", 400);
        }
    }

    public function addMoreImages(Request $request){
        $validate= Validator::make($request->all(), [
            "coverId"=> "required",
            "imageName.*" => "required|image|mimes:webp,jpeg,png,jpg,gif,svg"
        ]);
        if($validate->fails()){
            return response()->json($validate->errors(), 400);
        }
        if($request->has('imageName')){
            foreach($request->imageName as $image){
                $filenName= date('y-m-d')."_".(rand(1, 999)/7)."_albumImage.".$image->getClientOriginalExtension();
                $saveImage=$image->move(public_path("/Gallery/albumNo-".$request->coverId), $filenName);
                $comImage=Image::make($saveImage)->resize(800, 800, function($constraint){
                    $constraint->aspectRatio();
                });
                $comImage->save($saveImage);
        
                $saveImage=array(
                    "coverId"=> $request->coverId,
                    "imageName" =>$filenName
                );
    
                $saveData=DB::table('album')->insert($saveImage);
            }
            if($saveData){
                return response()->json(["message"=> "Images successfully added to Gallery"], 200);
            }else{
                return response()->json("Oops! something went wrong", 400);
            }
        }else{
            return response()->json(["imageName"=>"Please choose an image"], 400);
        }
    }
    public function deleteImages(Request $request){
        $validate = Validator::make($request->all(), [
            "albumId" => "required"
        ]);
        if($validate->fails()){
            return response()->json($validate->errors(), 400);
        }
        $image=DB::table('album')->where('albumId', $request->albumId)->first();
        if($image==null){
            return response()->json(["message"=>"Image not found with albumId=".$request->albumId], 400);
        }
        $cover=DB::table('cover')->where('coverId', $image->coverId)->first();
        $removeImage=DB::table('album')->where('albumId', $request->albumId)->delete();
        if($image->imageName==$cover->coverImage){
            $newCover=DB::table('album')->where('coverId', $image->coverId)->first();
            if($newCover){
                $updateCover=DB::table('cover')->where('coverId', $image->coverId)->update(["coverImage" => $newCover->imageName,
                "updated_at" => date('y-m-d')]);
            }else if($newCover==null){
                $updateCover=DB::table('cover')->where('coverId', $image->coverId)->update(["status" => 0,
                "updated_at" => date('y-m-d')]);
            }
            
        }
        if($removeImage){
            return response()->json(["message"=> "Image deleted successfully"], 200);
        }else{
            return response()->json("Ooops! image can not be deleted", 400);
        }
    }
    
}
