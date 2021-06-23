<?php

namespace App\Http\Controllers;

use App\Http\Resources\LoginResource;
use App\Http\Resources\UserResource;
use App\Result;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class usersCont extends Controller
{
    public function index(){
        $users = User::select('id','first_name' ,'last_name','email' ,'phone')->get();
        return response()->json([
            'success' => true,
            'users' => $users,
        ]);
    }

    public function register(Request $request){

        $validator = Validator::make($request->all(),[
            'email' => 'required|unique:users',
            'phone' => 'required|unique:users',
        ]);

        if ($validator->fails()){
            return response()->json([
                'errors' => $validator->getMessageBag(),
            ]);
        }

        User::create([
            'first_name' => $request->firstName,
            'last_name' => $request->lastName,
            'phone' => $request->phone,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        return response() -> json([
           'success' => true,
        ]);
    }

    public function login(Request $request){

        $job = '';
        $checkEmail = User::where('email' ,$request->email)->first();
        if($checkEmail && Hash::check($request->password ,$checkEmail->password)){
            $user = $checkEmail;
            $user->api_token = $this->generateRandomString(60);
            $user->save();

            if($checkEmail->id == 1){
                $job = 'admin';
            }

            return response() -> json([
                'success' => true,
                'user' => [
                    'signedin' => true,
                    'job' => $job,
                    'data' => $checkEmail
                ]
            ]);

        }else{
            return response() -> json([
               'success' => false,
            ]);
        }




    }//// end of function

    protected function generateRandomString($length = 10) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }


    public function get(Request $request){

        try{
            $user = new UserResource(auth()->guard('api')->user());
            return response()->json([
                'success' => true,
                'user' => $user
            ]);
        }catch (\Exception $e){
            return response()->json([
                'server_error' => true,
                'message' => $e->getMessage(),
            ]);
        }

    } // end of get user data and results

    //// create result
    public function createResult(Request $request){
        $validator = Validator::make($request->all(),[
           'file' => 'required|mimes:pdf',
        ]);
        if($validator->fails()){
            return response()->json([
               'error' => true
            ]);
        }

        $extension = $request->file->getClientOriginalExtension();
        $fileName = time() . $request->file->getSize() . '.' . $extension;
        $request->file->move('results' ,$fileName);

        Result::create([
            'user_id' => $request->user_id,
            'name' => $fileName,
            'title' => $request->title,
        ]);

        return response()->json([
            'success' => true
        ]);
    }

}// end of the class
