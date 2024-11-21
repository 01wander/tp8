<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2018 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------
use think\facade\Route;

// 允许跨域
Route::allowCrossDomain([
    'Access-Control-Allow-Origin' => '*',
    'Access-Control-Allow-Methods' => 'GET,POST,PUT,DELETE,OPTIONS',
    'Access-Control-Allow-Headers' => 'Content-Type,Authorization,X-Requested-With'
]);

Route::group('api', function () {
    // 登录接口
    Route::post('user/login', 'Auth/login');
    
    // 需要登录验证的接口
    Route::group('user', function () {
        Route::get('list', 'User/index');
        Route::post('create', 'User/create');
        Route::put('update/:id', 'User/update');
        Route::delete('delete/:id', 'User/delete');
    })->middleware('CheckLogin');
});

Route::get('think', function () {
    return 'hello,ThinkPHP6!';
});

Route::get('hello/:name', 'index/hello');


