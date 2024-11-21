<?php
use think\facade\Route;

/**
 * 用户模块路由配置
 */
Route::group('user', function () {
    // 用户登录
    Route::post('login', 'User/login');
    // 用户注册
    Route::post('register', 'User/register');
    // 更新用户信息
    Route::put(':id', 'User/update');
    // 删除用户
    Route::delete(':id', 'User/delete');

    // 获取用户列表
    Route::get('', 'User/index');
    // 检查生日
    Route::get('check-birthdays', 'User/checkBirthdays');
});

// 后台路由
Route::group('admin', function () {
    Route::get('login', function () {
        return view('admin/login');
    });
    Route::get('users', function () {
        return view('admin/users');
    });
});

Route::post('wechat', 'Wechat/index');
