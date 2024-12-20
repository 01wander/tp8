<?php
declare(strict_types=1);

namespace app\controller;

use app\BaseController;
use app\model\User as ModelUser;
use app\service\UserService;
use app\service\WechatService;
use think\Exception;
use think\facade\Config;

/**
 * 用户控制器
 * Class User
 * @package app\controller
 */
class User extends BaseController
{
    /**
     * 用户服务类实例
     * @var UserService
     */
    protected $userService;

    /**
     * 构造函数，注入用户服务类
     * @param UserService $userService
     */
    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    /**
     * 用户登录
     * @return \think\Response\Json
     * @route POST /user/login
     */
    public function login()
    {
        try {
            $data = request()->post();
            $result = $this->userService->login($data);
            return json(['code' => 200, 'msg' => '登录成功', 'data' => $result]);
        } catch (Exception $e) {
            return json(['code' => $e->getCode() ?: 500, 'msg' => $e->getMessage()]);
        }
    }

    /**
     * 用户注册
     * @return \think\Response\Json
     * @route POST /user/register
     */
    public function register()
    {
        try {
            $data = request()->post();
            $user = $this->userService->create($data);
            return json(['code' => 200, 'msg' => '注册成功', 'data' => $user]);
        } catch (Exception $e) {
            return json(['code' => $e->getCode() ?: 500, 'msg' => $e->getMessage()]);
        }
    }

    /**
     * 更新用户信息
     * @param int $id 用户ID
     * @return \think\Response\Json
     * @route PUT /user/:id
     */
    public function update($id)
    {
        try {
            error_log("Received ID: " . $id);
            $id = (int)$id;
            $data = request()->put();

            if (empty($data)) {
                throw new Exception('更新数据不能为空', 400);
            }

            error_log("Update data: " . print_r($data, true));

            $user = $this->userService->update($id, $data);
            return json(['code' => 200, 'msg' => '更新成功', 'data' => $user]);
        } catch (Exception $e) {
            error_log("Error updating user: " . $e->getMessage());
            return json(['code' => $e->getCode() ?: 500, 'msg' => $e->getMessage()]);
        }
    }

    /**
     * 删除用户
     * @param int $id 用户ID
     * @return \think\Response\Json
     * @route DELETE /user/:id
     */
    public function delete($id)
    {
        $id = (int)$id;

        try {
            $this->userService->delete($id);
            return json(['code' => 200, 'msg' => '删除成功']);
        } catch (Exception $e) {
            return json(['code' => $e->getCode() ?: 500, 'msg' => $e->getMessage()]);
        }
    }


    /**
     * 获取用户列表
     * @return \think\Response\Json
     * @route GET /user
     */
    public function index()
    {
        try {
            $params = request()->get();
            $users = $this->userService->list($params);
            return json(['code' => 200, 'msg' => '获取成功', 'data' => $users]);
        } catch (Exception $e) {
            return json(['code' => $e->getCode() ?: 500, 'msg' => $e->getMessage()]);
        }
    }

    /**
     * 检查今天是否有用户过生日并发送祝福消息
     *
     * 该方法首先确定今天的日期，然后查询数据库中今天过生日的用户
     * 如果没有用户过生日，则返回相应消息
     * 如果有用户过生日，则调用发送消息的方法，向指定的OpenID发送生日祝福
     *
     * @return \Illuminate\Http\JsonResponse|\think\response\Json
     */
    public function checkBirthdays()
    {
        try {
            // 获取今天的月份和日
            $today = date('m-d');

            // 查询今天过生日的用户，忽略年份
            $users = ModelUser::whereRaw('DATE_FORMAT(birthday, "%m-%d") = ?', [$today])->select()->toArray();

            // 检查是否有用户今天过生日
            if (empty($users)) {
                // 如果没有用户过生日，返回相应的消息
                return json(['code' => 200, 'msg' => '今天没有过生日的用户']);
            }

            // 推送消息到指定的 OpenID
            $this->sendBirthdayMessage($users, 'ob6qz6i3KhO3uq6I4WOXbeuNfzoQ');

            // 生日祝福发送成功，返回相应的消息
            return json(['code' => 200, 'msg' => '生日祝福已发送']);
        } catch (Exception $e) {
            // 捕获异常，返回错误代码和错误信息
            return json(['code' => $e->getCode() ?: 500, 'msg' => $e->getMessage()]);
        }
    }

    private function sendBirthdayMessage($users, $openid)
    {

        foreach ($users as $user) {
            $message = [
                'first' => ['value' => '祝您生日快乐！', 'color' => '#173177'],
                'user_name' => ['value' => $user['username'], 'color' => '#173177'],
                'birthday' => ['value' => date('Y-m-d', strtotime($user['birthday'])), 'color' => '#173177'],
                'remark' => ['value' => '祝您生活愉快！', 'color' => '#173177'],
            ];

            // 调用发送消息的函数
            $this->sendTemplateMessage($openid, $message);
            error_log("Sending message to $openid: " . json_encode($message)); // 调试信息
        }
    }

    private function sendTemplateMessage($openid, $data)
    {
        
        $weChatService = new WeChatService();
        $accessToken = $weChatService->getAccessToken();

        $url = "https://api.weixin.qq.com/cgi-bin/message/template/send?access_token={$accessToken}";
        $templateId = Config::get('wechat.birthday_template_id'); // 替换为您的模板 ID

        $postData = [
            'touser' => $openid,
            'template_id' => $templateId,
            'data' => $data,
        ];

        $response = file_get_contents($url, false, stream_context_create([
            'http' => [
                'header' => "Content-Type: application/json\r\n",
                'method' => 'POST',
                'content' => json_encode($postData),
            ],
        ]));

        return json_decode($response, true);
    }
} 