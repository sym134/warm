<?php

namespace warm\common\service\notice;

/**
 * 邮件通知渠道
 * 
 * 实现邮件通知发送功能
 */
class EmailChannel implements NoticeChannelInterface
{
    /**
     * 发送邮件通知
     * 
     * @param string $scene 场景ID
     * @param array $params 通知参数
     * @param array $config 渠道配置
     * @return bool 是否发送成功
     */
    public function send(string $scene, array $params, array $config): bool
    {
        // 验证配置
        if (!$this->validateConfig($config)) {
            return false;
        }
        
        // 获取邮箱地址
        $email = $params['email'] ?? '';
        if (empty($email)) {
            return false;
        }
        
        // 根据场景获取邮件主题和内容
        $emailData = $this->getEmailDataByScene($scene, $params);
        
        if (empty($emailData)) {
            return false;
        }
        
        // 发送邮件
        return $this->sendEmail($email, $emailData, $config);
    }
    
    /**
     * 获取渠道名称
     * 
     * @return string
     */
    public function getName(): string
    {
        return 'email';
    }
    
    /**
     * 获取渠道描述
     * 
     * @return string
     */
    public function getDescription(): string
    {
        return '邮件通知';
    }
    
    /**
     * 验证配置是否完整
     * 
     * @param array $config 配置参数
     * @return bool
     */
    public function validateConfig(array $config): bool
    {
        return isset($config['smtp_host']) && 
               isset($config['smtp_username']) && 
               isset($config['smtp_password']) && 
               isset($config['smtp_port']) && 
               isset($config['enable']) && 
               $config['enable'];
    }
    
    /**
     * 根据场景获取邮件数据
     * 
     * @param string $scene 场景ID
     * @param array $params 参数
     * @return array
     */
    private function getEmailDataByScene(string $scene, array $params): array
    {
        // 实际项目中应该从数据库或配置文件中获取邮件模板
        $emails = [
            'user_register' => [
                'subject' => '欢迎注册',
                'body' => '<p>尊敬的用户：</p><p>恭喜您成功注册账号，您的用户名是：' . ($params['username'] ?? '') . '</p><p>感谢您的注册！</p>'
            ],
            'user_password_reset' => [
                'subject' => '密码重置',
                'body' => '<p>尊敬的用户：</p><p>您正在重置密码，验证码是：<strong>' . ($params['code'] ?? '') . '</strong>，5分钟内有效</p>'
            ],
            'order_created' => [
                'subject' => '订单创建成功',
                'body' => '<p>尊敬的用户：</p><p>您的订单 ' . ($params['order_no'] ?? '') . ' 已创建成功</p><p>订单金额：' . ($params['amount'] ?? '') . '</p>'
            ]
        ];
        
        return $emails[$scene] ?? [];
    }
    
    /**
     * 发送邮件
     * 
     * @param string $email 邮箱地址
     * @param array $emailData 邮件数据
     * @param array $config 配置信息
     * @return bool
     */
    private function sendEmail(string $email, array $emailData, array $config): bool
    {
        // 这里应该是实际的邮件发送逻辑
        // 为演示目的，我们只记录日志
        \support\Log::info('Email sent', [
            'email' => $email,
            'email_data' => $emailData,
            'config' => $config
        ]);
        
        // 模拟发送结果
        return true;
    }
}