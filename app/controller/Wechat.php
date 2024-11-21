<?php
declare(strict_types=1);

namespace app\controller;

use app\BaseController;
use think\Request;

class Wechat extends BaseController
{
    public function index(Request $request)
    {
        $token = 'ZZ69Vm4P6C79S7MQ'; // 您的 Token
        $query = $request->get();

        // 验证请求
        if ($request->isGet()) {
            $signature = $query['signature'];
            $timestamp = $query['timestamp'];
            $nonce = $query['nonce'];
            $echostr = $query['echostr'];

            // 进行签名验证
            $tmpArr = [$token, $timestamp, $nonce];
            sort($tmpArr);
            $tmpStr = implode($tmpArr);
            $tmpStr = sha1($tmpStr);

            if ($tmpStr === $signature) {
                return $echostr; // 返回 echostr 以验证成功
            } else {
                return 'Invalid signature';
            }
        }

        // 处理 POST 请求
        $data = $request->getContent();
        // 处理消息和事件
        return 'success'; // 微信要求返回 success
    }
} 