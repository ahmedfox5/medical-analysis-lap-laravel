<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

//abouts
Route::group(['prefix' => 'abouts'] ,function(){
    Route::post('/' ,'aboutsCont@index');
    Route::post('/create' ,'aboutsCont@create');
    Route::post('/delete' ,'aboutsCont@delete');
    Route::post('/get' ,'aboutsCont@get');
    Route::post('/update' ,'aboutsCont@update');
});


//blogs
Route::group(['prefix' => 'blogs'] ,function(){
    Route::post('/' ,'blogsCont@index');
    Route::post('/create' ,'blogsCont@create');
    Route::post('/delete' ,'blogsCont@delete');
    Route::post('/get' ,'blogsCont@get');
    Route::post('/update' ,'blogsCont@update');
});


//doctors
Route::group(['prefix' => 'doctors'] ,function(){
    Route::post('/' ,'doctorsCont@index');
    Route::post('/create' ,'doctorsCont@create');
    Route::post('/delete' ,'doctorsCont@delete');
    Route::post('/get' ,'doctorsCont@get');
    Route::post('/update' ,'doctorsCont@update');
});


//plans
Route::group(['prefix' => 'plans'] ,function(){
    Route::post('/' ,'plansCont@index');
    Route::post('/create' ,'plansCont@create');
    Route::post('/delete' ,'plansCont@delete');
    Route::post('/get' ,'plansCont@get');
    Route::post('/update' ,'plansCont@update');
    Route::post('/fullPlans' ,'plansCont@fullPlans');
});


//users
Route::group(['prefix' => 'users'] ,function(){
    Route::post('/' ,'usersCont@index');
    Route::post('/create' ,'usersCont@create');
    Route::post('/delete' ,'usersCont@delete');
    Route::post('/update' ,'usersCont@update');
    Route::post('/login' ,'usersCont@login');
    Route::post('/register' ,'usersCont@register');
    Route::post('/get' ,'usersCont@get');
    Route::post('/create-result' ,'usersCont@createResult');
});
