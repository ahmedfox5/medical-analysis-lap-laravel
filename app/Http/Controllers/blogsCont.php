<?php

namespace App\Http\Controllers;

use App\Blog;
use App\Http\Resources\AdminResource;
use App\Http\Resources\BlogsResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\File;

class blogsCont extends Controller
{
    protected function uploadImg($img ,$path){
        $extension = $img->getClientOriginalExtension();
        $img_name = time() . str_replace(['.jp' ,'.pn' ,'.'] ,'' ,$img->getClientOriginalName()) . "." . $extension;
        $img->move($path ,$img_name);
        return $img_name;
    }

    public function index(){
        try{
            $blogs = BlogsResource::collection(Blog::all());
            return response()->json([
                'success' => true,
                'blogs' => $blogs,
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

        Blog::create([
            'title' => $request->title,
            'description' => $request->description,
            'img' => $img_name,
        ]);

        return response()->json([
            'success' => true,
        ]);
    }


    //    delete blog
    public function delete(Request $request){
        $blog = Blog::find($request->id);

        if(File::exists(public_path('imgs/blogs/' . $blog->img))){
            File::delete(public_path('imgs/blogs/' . $blog->img));
        }

        Blog::destroy($request->id);

        return response()->json([
            'success' => true
        ]);
    }

    //    get blog
    public function get(Request $request){
        try{
            $admin = new AdminResource(auth()->guard('api')->user());
            if ($admin){
                $blog = new BlogsResource(Blog::find($request->id));
                return response() -> json([
                    'success' => true,
                    'blog' => $blog
                ]);
            }else{
                throw new \Exception('This available only for admin');
            }
        }catch(\Exception $e){
            return response() -> json([
                'success' => false,
                'message' => $e->getMessage(),
            ]);
        }
    }



    // update blog
    public function update(Request $request){

        $validator = Validator::make($request->all() ,[
            'img' => 'mimes:jpg,jpeg,png'
        ]);

        if ($validator->fails()){
            return response()->json([
                'error' => true
            ]);
        }

        $blog = Blog::find($request->id);
        $img_name = $blog->img;
        if ($request->hasFile('img')){
            if (File::exists(public_path('imgs/blogs/' . $img_name))){
                File::delete(public_path('imgs/blogs/' . $img_name));
            }
            $img_name = $this->uploadImg($request->img ,'imgs/blogs');
        }

        $blog->update([
            'title' => $request->title,
            'description' => $request->description,
            'img' => $img_name
        ]);

        return response() -> json([
            'success' => true
        ]);
    }


}// end of the class
