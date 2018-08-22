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

// 注册1 -> 登录1 -> 建造1 -> 建筑队列启动与结束1 -> 资源生产与消耗2
Route::get('/', function () {
    if (\Illuminate\Support\Facades\Auth::check()) {
        return view('kingdom');
    } else {
        return view('outside');
    }
});
Route::get('/kingdom', function () {
    return view('kingdom');
})->middleware('auth');

Route::get('/building', function () {
    return view('building');
})->middleware('auth');

// 通用
Route::post('/register', 'Common\UserController@register');
Route::post('/login', 'Common\UserController@login')->middleware('resource.auto');
Route::get ('/logout', 'Common\UserController@logout');

// 领地
Route::get ('/user/get-resource', 'Common\ResourceController@getMeResource')->middleware('resource.auto');

// 建筑
Route::get ('/building/list', 'Building\BuildingController@buildingList');
Route::get ('/building/schedule', 'Building\BuildingController@schedule')->middleware('resource.auto');

Route::post('/building/build', 'Building\BuildingController@build')->middleware('resource.auto');
Route::get ('/building/recall/{name}', 'Building\BuildingController@recall')->middleware('resource.auto');
Route::get ('/building/destroy/{name}', 'Building\BuildingController@destroy')->middleware('resource.auto');

// 初始化
Route::get ('/reset/redis', 'Common\InitializeController@resetRedis');
