<?php
declare(strict_types=1);

namespace app\controller;

use app\BaseController;

/**
 * 首页控制器
 * Class Index
 * @package app\controller
 */
class Index extends BaseController
{
    /**
     * 首页
     * @return string
     */
    public function index()
    {
        return '<style>*{ padding: 0; margin: 0; }</style><iframe src="https://www.thinkphp.cn/welcome?version=' . \think\facade\App::version() . '" width="100%" height="100%" frameborder="0" scrolling="auto"></iframe>';
    }

    /**
     * 测试接口
     * @param string $name 名称
     * @return string
     */
    public function hello($name = 'ThinkPHP8')
    {
        return 'hello,' . $name;
    }
}
