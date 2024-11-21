<?php
declare(strict_types=1);

namespace app\model;

use think\Model;

class User extends Model
{
    protected $table = 'user';
    
    // 设置字段自动转换
    protected $type = [
        'birthday' => 'date',
        'last_login_time' => 'datetime',
    ];
    
    // 设置json输出时隐藏的字段
    protected $hidden = ['password'];
    
    // 密码加密
    public function setPasswordAttr($value)
    {
        return password_hash($value, PASSWORD_DEFAULT);
    }

    /**
     * 自定义 whereRaw 方法
     *
     * @param string $query
     * @param array $bindings
     * @return \think\db\Query
     */
    public static function whereRaw($query, $bindings = [])
    {
        return self::whereRaw($query, $bindings);
    }
} 