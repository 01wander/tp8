<?php
declare(strict_types=1);

namespace app\service;

use EasyWeChat\Factory;
use think\facade\Config;

class WechatService
{
    protected $app;
    
    public function __construct()
    {
        $config = [
            'app_id' => Config::get('wechat.app_id'),
            'secret' => Config::get('wechat.secret'),
            'token' => Config::get('wechat.token'),
            'response_type' => 'array',
        ];
        
        $this->app = Factory::officialAccount($config);
    }
    
    /**
     * 发送生日祝福模板消息
     * @param string $openid 用户openid
     * @param array $data 用户数据
     * @return array
     */
    public function sendBirthdayMessage(string $openid, array $data): array
    {
        return $this->app->template_message->send([
            'touser' => $openid,
            'template_id' => Config::get('wechat.birthday_template_id'),
            'url' => '', // 可以设置点击跳转的链接
            'data' => [
                'first' => [
                    'value' => '🎂 生日快乐！',
                    'color' => '#FF0000'
                ],
                'user_name' => [
                    'value' => $data['username'],
                    'color' => '#173177'
                ],
                'birthday' => [
                    'value' => date('Y年m月d日', strtotime($data['birthday'])),
                    'color' => '#173177'
                ],
                'remark' => [
                    'value' => "愿您度过一个愉快的生日！\n祝您生日快乐，心想事成！",
                    'color' => '#FF0000'
                ]
            ],
        ]);
    }

    public function getApp()
    {
        return $this->app;
    }
} 