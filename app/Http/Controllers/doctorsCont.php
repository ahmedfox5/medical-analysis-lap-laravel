<?php

namespace App\Http\Controllers;

use App\Doctor;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\File;
use Illuminate\Http\Request;

class doctorsCont extends Controller
{

    protected function uploadImg($img ,$path){
        $extension = $img->getClientOriginalExtension();
        $img_name = time() . str_replace(['.jp' ,'.pn' ,'.'] ,'' ,$img->getClientOriginalName()) . "." . $extension;
        $img->move($path ,$img_name);
        return $img_name;
    }

    public function index(){
        $doctors = Doctor::all();
        return response()->json([
            'success' => true,
            'doctors' => $doctors,
        ]);
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

        $img_name = $this->uploadImg($request->img ,'imgs/doctors');

        Doctor::create([
            'name' => $request->name,
            'job' => $request->job,
            'description' => $request->description,
            'img' => $img_name,
        ]);

        return response()->json([
            'success' => true,
        ]);
    }

//    delete doctor
    public function delete(Request $request){
        $doctor = Doctor::find($request->id);

        if(File::exists(public_path('imgs/doctors/' . $doctor->img))){
            File::delete(public_path('imgs/doctors/' . $doctor->img));
        }

        Doctor::destroy($request->id);

        return response()->json([
            'success' => true
        ]);
    }


//    get doctor
    public function get(Request $request){

        $doctor = Doctor::find($request->id);

        return response() -> json([
            'success' => true,
            'doctor' => $doctor
        ]);
    }


// update doctor
    public function update(Request $request){

        $validator = Validator::make($request->all() ,[
            'img' => 'mimes:jpg,jpeg,png'
        ]);

        if ($validator->fails()){
            return response()->json([
                'error' => true
            ]);
        }

        $doctor = Doctor::find($request->id);
        $img_name = $doctor->img;
        if ($request->hasFile('img')){
            if (File::exists(public_path('imgs/doctors/' . $img_name))){
                File::delete(public_path('imgs/doctors/' . $img_name));
            }
            $img_name = $this->uploadImg($request->img ,'imgs/doctors');
        }

        $doctor->update([
            'name' => $request->name,
            'job' => $request->job,
            'description' => $request->description,
            'img' => $img_name
        ]);

        return response() -> json([
            'success' => true
        ]);
    }



}// end of the class
