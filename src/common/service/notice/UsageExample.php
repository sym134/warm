<?php

namespace warm\common\service\notice;

use warm\common\enum\NoticeEnum;

/**
 * 通知服务使用示例
 * 
 * 展示如何使用Notice服务发送各种通知
 */
class UsageExample
{
    /**
     * 用户注册通知示例
     * 
     * @return void
     */
    public function userRegisterExample(): void
    {
        $notice = new Notice();
        
        // 发送用户注册通知
        $params = [
            'username' => 'john_doe',
            'code' => '123456',
            'mobile' => '13800138000',
            'email' => 'john@example.com'
        ];
        
        // 方式1: 使用默认渠道发送（根据场景配置）
        $notice->send(NoticeEnum::USER_REGISTER, $params);
        
        // 方式2: 指定渠道发送
        $notice->send(NoticeEnum::USER_REGISTER, $params, ['sms']);
        
        // 方式3: 同时通过多个渠道发送
        $notice->send(NoticeEnum::USER_REGISTER, $params, ['sms', 'email']);
    }
    
    /**
     * 订单支付通知示例
     * 
     * @return void
     */
    public function orderPaidExample(): void
    {
        $notice = new Notice();
        
        // 发送订单支付通知
        $params = [
            'order_no' => 'ORD202310010001',
            'amount' => '299.00',
            'mobile' => '13800138000',
            'openid' => 'oAHeX5A-d6cGsN7iX88HdF0MzNkI',
            'email' => 'john@example.com'
        ];
        
        // 通过所有可用渠道发送通知
        $notice->send(NoticeEnum::ORDER_PAID, $params);
    }
    
    /**
     * 系统维护通知示例
     * 
     * @return void
     */
    public function systemMaintenanceExample(): void
    {
        $notice = new Notice();
        
        // 发送系统维护通知
        $params = [
            'start_time' => '2023-10-01 02:00:00',
            'end_time' => '2023-10-01 04:00:00',
            'reason' => '系统升级维护',
            'email' => 'admin@example.com'
        ];
        
        // 仅通过邮件发送系统维护通知
        $notice->send(NoticeEnum::SYSTEM_MAINTENANCE, $params, ['email']);
    }
}