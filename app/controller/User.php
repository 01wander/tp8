<?php
declare(strict_types=1);

namespace app\controller;

use app\BaseController;
use app\service\UserService;
use think\Exception;

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
            $data = request()->put();
            $user = $this->userService->update($id, $data);
            return json(['code' => 200, 'msg' => '更新成功', 'data' => $user]);
        } catch (Exception $e) {
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
        try {
            $this->userService->delete($id);
            return json(['code' => 200, 'msg' => '删除成功']);
        } catch (Exception $e) {
            return json(['code' => $e->getCode() ?: 500, 'msg' => $e->getMessage()]);
        }
    }

    /**
     * 获取单个用户信息
     * @param int $id 用户ID
     * @return \think\Response\Json
     * @route GET /user/:id
     */
    public function read($id)
    {
        try {
            $user = $this->userService->get($id);
            return json(['code' => 200, 'msg' => '获取成功', 'data' => $user]);
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
} 