<?php

namespace warm\common\enum;

/**
 * 通知枚举类
 * 
 * 定义系统中各种通知场景的枚举值
 */
class NoticeEnum
{
    // 用户相关通知场景
    const USER_REGISTER = 'user_register';           // 用户注册
    const USER_LOGIN = 'user_login';                 // 用户登录
    const USER_PASSWORD_RESET = 'user_password_reset'; // 密码重置
    
    // 订单相关通知场景
    const ORDER_CREATED = 'order_created';           // 订单创建
    const ORDER_PAID = 'order_paid';                 // 订单支付
    const ORDER_SHIPPED = 'order_shipped';           // 订单发货
    const ORDER_COMPLETED = 'order_completed';       // 订单完成
    const ORDER_CANCELLED = 'order_cancelled';       // 订单取消
    
    // 系统相关通知场景
    const SYSTEM_ALERT = 'system_alert';             // 系统警报
    const SYSTEM_MAINTENANCE = 'system_maintenance'; // 系统维护
    
    /**
     * 获取所有通知场景描述
     * 
     * @return array
     */
    public static function getDescriptions(): array
    {
        return [
            self::USER_REGISTER => '用户注册',
            self::USER_LOGIN => '用户登录',
            self::USER_PASSWORD_RESET => '密码重置',
            self::ORDER_CREATED => '订单创建',
            self::ORDER_PAID => '订单支付',
            self::ORDER_SHIPPED => '订单发货',
            self::ORDER_COMPLETED => '订单完成',
            self::ORDER_CANCELLED => '订单取消',
            self::SYSTEM_ALERT => '系统警报',
            self::SYSTEM_MAINTENANCE => '系统维护',
        ];
    }
    
    /**
     * 获取指定场景的描述
     * 
     * @param string $scene 场景ID
     * @return string
     */
    public static function getDescription(string $scene): string
    {
        return self::getDescriptions()[$scene] ?? $scene;
    }
}