<?php
namespace app\controller;

use app\model\User;
use think\facade\Request;

class Auth
{
    public function login()
    {
        $data = Request::post();
        
        // 这里处理登录逻辑
        return json([
            'code' => 200,
            'msg' => '登录成功',
            'data' => [
                'token' => 'xxx', // 生成token
                'userInfo' => [
                    'username' => $data['username']
                ]
            ]
        ]);
    }
} 