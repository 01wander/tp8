<?php
declare(strict_types=1);

namespace app\service;

use EasyWeChat\Factory;
use think\facade\Config;
use think\facade\Cache;

class WechatService
{
    protected $app;
    
    /**
     * 构造方法：初始化微信公众号实例
     *
     * 本方法从配置文件中读取微信公众号的配置信息，
     * 包括app_id、secret和token等参数，并将其与响应类型一起
     * 传递给EasyWeChat的官方账号工厂方法，以创建并初始化
     * 一个微信公众号实例，该实例在类中用于后续的微信接口调用
     */
    public function __construct()
    {
        // 配置微信公众号的参数
        $config = [
            'app_id' => Config::get('wechat.app_id'), // 微信公众号的APP ID
            'secret' => Config::get('wechat.secret'), // 微信公众号的APP密钥
            'token' => Config::get('wechat.token'), // 微信公众号的Token
            'response_type' => 'array', // 指定API请求的返回类型为数组
        ];

        // 使用配置信息创建微信公众号实例
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

    public function getAccessToken()
    {
        // 尝试从缓存中获取 access_token
        $accessToken = Cache::get('wechat_access_token');
        if (!$accessToken) {
            // 如果缓存中没有，调用微信API获取
            $url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid={$this->app->config->get('app_id')}&secret={$this->app->config->get('secret')}";
            $response = file_get_contents($url);
            $data = json_decode($response, true);
            if (isset($data['access_token'])) {
                $accessToken = $data['access_token'];
                // 将 access_token 存入缓存，设置过期时间为7200秒
                Cache::set('wechat_access_token', $accessToken, 7200);
            } else {
                throw new \Exception('获取access_token失败: ' . $data['errmsg']);
            }
        }

        return $accessToken;
    }
} 