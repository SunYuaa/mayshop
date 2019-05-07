<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

//api
Route::get('/api/getM','Api\apiController@getM');     //get方式
Route::post('/api/postM','Api\apiController@postM');  //post方式
Route::post('/api/test','Api\apiController@test');


//全局中间件
Route::get('/api/reqMid','Api\apiController@reqMid')->middleware('request10Times');

//用户登录注册
Route::post('/user/register','User\userController@register');
Route::post('/user/login','User\userController@login');
Route::get('/user/userCenter','User\userController@userCenter')->middleware(['checkLogin','request10Times']);  //个人中心
