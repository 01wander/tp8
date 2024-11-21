<?php

namespace app\service;

use app\model\User;
use think\facade\Validate;
use think\Exception;

class UserService
{
    /**
     * 用户登录
     * @param array $data
     * @return array
     */
    public function login(array $data): array
    {
        // 验证规则
        $validate = Validate::rule([
            'username' => 'require|length:3,50',
            'password' => 'require|length:6,20'
        ]);
        
        // 验证失败返回错误信息
        if (!$validate->check($data)) {
            throw new Exception($validate->getError(), 400);
        }
        
        // 查询用户
        $user = User::where('username', $data['username'])->find();
        if (!$user) {
            throw new Exception('用户不存在', 400);
        }
        
        // 验证密码
        if (!password_verify($data['password'], $user->password)) {
            throw new Exception('密码错误', 400);
        }
        
        // 更新登录时间
        $user->last_login_time = date('Y-m-d H:i:s');
        $user->save();
        
        // 生成token
        $token = $this->generateToken($user->id);
        
        return [
            'token' => $token,
            'user' => [
                'id' => $user->id,
                'username' => $user->username,
                'email' => $user->email
            ]
        ];
    }

    /**
     * 创建用户
     * @param array $data
     * @return User
     */
    public function create(array $data): User
    {
        $validate = Validate::rule([
            'username' => 'require|length:3,50|unique:user',
            'password' => 'require|length:6,20',
            'email' => 'require|email|unique:user',
            'birthday' => 'date'
        ]);

        if (!$validate->check($data)) {
            throw new Exception($validate->getError(), 400);
        }

        $user = new User;
        $user->username = $data['username'];
        $user->password = password_hash($data['password'], PASSWORD_DEFAULT);
        $user->email = $data['email'];
        $user->birthday = $data['birthday'] ?? null;
        $user->save();

        return $user;
    }

    /**
     * 更新用户信息
     * @param int $id
     * @param array $data
     * @return User
     */
    public function update(int $id, array $data): User
    {
        $user = User::find($id);
        if (!$user) {
            throw new Exception('用户不存在', 404);
        }

        $validate = Validate::rule([
            'email' => 'email|unique:user,email,' . $id,
            'password' => 'length:6,20',
            'birthday' => 'date'
        ]);

        if (!$validate->check($data)) {
            throw new Exception($validate->getError(), 400);
        }

        if (isset($data['password'])) {
            $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
        }

        $user->save($data);
        return $user;
    }

    /**
     * 删除用户
     * @param int $id
     * @return bool
     */
    public function delete(int $id): bool
    {
        $user = User::find($id);
        if (!$user) {
            throw new Exception('用户不存在', 404);
        }

        return $user->delete();
    }

    /**
     * 获取用户信息
     * @param int $id
     * @return User
     */
    public function get(int $id): User
    {
        $user = User::find($id);
        if (!$user) {
            throw new Exception('用户不存在', 404);
        }

        return $user;
    }

    /**
     * 获取用户列表
     * @param array $params
     * @return \think\Paginator
     */
    public function list(array $params = [])
    {
        $query = User::order('id', 'desc');
        
        // 搜索条件
        if (!empty($params['keyword'])) {
            $query->where('username|email', 'like', "%{$params['keyword']}%");
        }

        return $query->paginate($params['per_page'] ?? 15);
    }

    /**
     * 生成Token
     * @param int $userId
     * @return string
     */
    private function generateToken(int $userId): string
    {
        // 这里使用简单的示例，实际项目建议使用JWT
        return md5($userId . time() . rand(1000, 9999));
    }
} 