<?php

namespace warm\common\service\notice;

use Overtrue\EasySms\EasySms;
use Overtrue\EasySms\Exceptions\NoGatewayAvailableException;
use support\Log;

/**
 * 短信通知渠道
 * 
 * 实现基于 overtrue/easy-sms 的短信通知发送功能
 */
class SmsChannel implements NoticeChannelInterface
{
    /**
     * 发送短信通知
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
        
        // 根据场景获取模板内容
        $template = $this->getTemplateByScene($scene, $params);
        
        // 获取手机号
        $mobile = $params['mobile'] ?? '';
        if (empty($mobile)) {
            return false;
        }
        
        try {
            $easySms = new EasySms($config);
            $result = $easySms->send($mobile, [
                'content' => $template,
            ]);
            
            // 记录发送日志
            Log::info('SMS sent successfully', [
                'mobile' => $mobile,
                'template' => $template,
                'result' => $result
            ]);
            
            return true;
        } catch (NoGatewayAvailableException $exception) {
            // 记录异常日志
            Log::error('SMS send failed', [
                'mobile' => $mobile,
                'template' => $template,
                'exception' => $exception->getMessage(),
                'results' => $exception->getExceptions()
            ]);
            
            return false;
        } catch (\Exception $exception) {
            // 记录其他异常日志
            Log::error('SMS send error', [
                'mobile' => $mobile,
                'template' => $template,
                'exception' => $exception->getMessage()
            ]);
            
            return false;
        }
    }
    
    /**
     * 获取渠道名称
     * 
     * @return string
     */
    public function getName(): string
    {
        return 'sms';
    }
    
    /**
     * 获取渠道描述
     * 
     * @return string
     */
    public function getDescription(): string
    {
        return '短信通知';
    }
    
    /**
     * 验证配置是否完整
     * 
     * @param array $config 配置参数
     * @return bool
     */
    public function validateConfig(array $config): bool
    {
        // 检查基本配置
        if (!isset($config['timeout']) || !isset($config['default']) || !isset($config['gateways'])) {
            return false;
        }
        
        // 检查默认配置
        if (empty($config['default']['gateways'])) {
            return false;
        }
        
        // 检查至少有一个网关配置
        if (empty($config['gateways'])) {
            return false;
        }
        
        return true;
    }
    
    /**
     * 根据场景获取短信模板
     * 
     * @param string $scene 场景ID
     * @param array $params 参数
     * @return string
     */
    private function getTemplateByScene(string $scene, array $params): string
    {
        // 实际项目中应该从数据库或配置文件中获取模板
        $templates = [
            'user_register' => '您的注册验证码是：{code}，5分钟内有效',
            'user_login' => '您的登录验证码是：{code}，5分钟内有效',
            'user_password_reset' => '您的密码重置验证码是：{code}，5分钟内有效',
            'order_created' => '您的订单{order_no}已创建成功',
            'order_paid' => '您的订单{order_no}已支付成功',
            'order_shipped' => '您的订单{order_no}已发货，快递单号：{tracking_no}',
        ];
        
        $template = $templates[$scene] ?? '您有一条新消息';
        
        // 替换模板中的变量
        foreach ($params as $key => $value) {
            $template = str_replace('{' . $key . '}', $value, $template);
        }
        
        return $template;
    }
}