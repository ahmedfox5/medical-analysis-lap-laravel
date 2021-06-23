<?php

namespace App\Http\Controllers;

use App\About;
use App\Http\Resources\AboutsResource;
use App\Http\Resources\AdminResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;

class aboutsCont extends Controller
{
    protected function uploadImg($img ,$path){
        $extension = $img->getClientOriginalExtension();
        $img_name = time() . str_replace(['.jp' ,'.pn' ,'.'] ,'' ,$img->getClientOriginalName()) . "." . $extension;
        $img->move($path ,$img_name);
        return $img_name;
    }

    public function index(){
        try{
            $abouts = AboutsResource::collection(About::all());
            return response()->json([
                'success' => true,
                'abouts' => $abouts,
            ]);
        }catch (\Exception $e){
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ]);
        }
    }

    public function create(Request $request){

        $validator = Validator::make($request->all(),[
           'img' => 'required|mimes:jpg,png,jpeg',
        ]);

        if ($validator->fails()){
            return response()->json([
                'error' => true
            ]);
        }

        $img_name = $this->uploadImg($request->img ,'imgs/blogs');

        About::create([
            'title' => $request->title,
            'description' => $request->description,
            'img' => $img_name,
        ]);

        return response()->json([
           'success' => true,
        ]);
    }

//    delete about
    public function delete(Request $request){
        $about = About::find($request->id);

        if(File::exists(public_path('imgs/about/' . $about->img))){
            File::delete(public_path('imgs/about/' . $about->img));
        }

        About::destroy($request->id);

        return response()->json([
           'success' => true
        ]);
    }


//    get about
    public function get(Request $request){
        try{
            $admin = new AdminResource(auth()->guard('api')->user());
            if ($admin){
                $about = new AboutsResource(About::find($request->id));
                return response() -> json([
                    'success' => true,
                    'about' => $about
                ]);
            }else{
                throw new \Exception('This available only for admin !');
            }
        }catch (\Exception $e){
            return response() -> json([
                'success' => false,
                'message' => $e->getMessage(),
            ]);
        }
    }


// update about
    public function update(Request $request){

        $validator = Validator::make($request->all() ,[
           'img' => 'mimes:jpg,jpeg,png'
        ]);

        if ($validator->fails()){
            return response()->json([
                'error' => true
            ]);
        }

        $about = About::find($request->id);
        $img_name = $about->img;
        if ($request->hasFile('img')){
            if (File::exists(public_path('imgs/about/' . $img_name))){
                File::delete(public_path('imgs/about/' . $img_name));
            }
            $img_name = $this->uploadImg($request->img ,'imgs/about');
        }

        $about->update([
           'title' => $request->title,
           'description' => $request->description,
           'img' => $img_name
        ]);

        return response() -> json([
           'success' => true
        ]);
    }

}// end of the class
