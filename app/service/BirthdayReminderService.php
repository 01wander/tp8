<?php
declare(strict_types=1);

namespace app\service;

use app\model\User;
use think\facade\Log;

class BirthdayReminderService
{
    protected $wechatService;
    
    public function __construct(WechatService $wechatService)
    {
        $this->wechatService = $wechatService;
    }
    
    /**
     * 检查并发送生日提醒
     * @return void
     */
    public function checkAndSendReminders(): void
    {
        try {
            // 获取今天生日的用户
            $birthdayUsers = $this->getTodayBirthdayUsers();
            
            foreach ($birthdayUsers as $user) {
                // 这里需要用户表中有存储微信openid字段
                if (!empty($user->openid)) {
                    $this->wechatService->sendBirthdayMessage($user->openid, [
                        'username' => $user->username,
                        'birthday' => $user->birthday
                    ]);
                    
                    Log::info('生日提醒发送成功', [
                        'user_id' => $user->id,
                        'username' => $user->username
                    ]);
                }
            }
        } catch (\Exception $e) {
            Log::error('生日提醒发送失败', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
        }
    }
    
    /**
     * 获取今天生日的用户
     * @return \think\Collection
     */
    protected function getTodayBirthdayUsers()
    {
        return User::whereRaw('DATE_FORMAT(birthday, "%m-%d") = ?', [date('m-d')])
            ->where('status', 1)
            ->select();
    }
} 