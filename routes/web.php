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
    return view('index');
});

// 通用
Route::post('/register', 'Common\UserController@register');
Route::post('/login', 'Common\UserController@login')->middleware('resource.auto');
Route::get ('/logout', 'Common\UserController@logout');

// 领地
Route::get ('/user/get-resource', 'Common\ResourceController@getMeResource');

// 建筑
Route::get ('/building/list/{version}', 'Building\BuildingController@buildingList')->middleware('resource.auto');

// 建筑
Route::get ('/init/redis', 'Common\InitializeController@initRedis')->middleware('resource.auto');