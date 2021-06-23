<?php

namespace App\Http\Controllers;

use App\Http\Resources\FullPlans;
use App\Plane;
use App\Section;
use Illuminate\Http\Request;

class plansCont extends Controller
{
    public function index(){
        $plans = Plane::where('main_id' ,0)->get();
        return response()->json([
            'success' => true,
            'plans' => $plans,
        ]);
    }


    // create plane
    public function create(Request $request){

        Plane::create([
            'title' => $request->title,
            'price' => $request->price,
        ]);

        return response()->json([
            'success' => true,
        ]);
    }


    /////// get plane
    public function get(Request $request){

        $plane = Plane::where('id' ,$request -> id)
                -> with('sections') -> get();

        return response()->json([
           'success' => true,
           'plan' => $plane[0]
        ]);
    }


    /////delete plane
    public function delete(Request $request){

        Section::where('plan_id',$request->id)->delete();
        Plane::destroy($request->id);

        return response()->json([
           'success' => true,
        ]);
    }


//    update plane
    public function update(Request $request){

        Plane::find($request->id)->update([
           'title' => $request->title ,
            'price' => $request->price
        ]);

        Section::where('plan_id',$request->id)->delete();

        $keys = $request->request->keys();

        for ($i = 2 ;$i < count($request->request) - 1 ;$i++){
            if(str_split($keys[$i] ,4)[0] == 'sect'){
                $sec = $keys[$i];
                $title = $request->$sec;
                $available = 0;

                if (str_split($keys[$i + 1] ,4)[0] == 'Csec'){
                    $available = 1;
                }
                if ($title != ''){
                    Section::create([
                        'title' => $title,
                        'active' => $available,
                        'plan_id' => $request->id,
                    ]);
                }
            }
        }

        return response()->json([
            'success' => true,
        ]);

    }


    public function fullPlans(){
        try{
            $plans = FullPlans::collection(Plane::all());
            return response() -> json([
                'success' => true,
                'plans' => $plans
            ]);
        }catch (\Exception $e){
            return response() ->json([
                'success' => false,
                'message' => $e->getMessage(),
            ]);
        }

    }

}// end of the class
