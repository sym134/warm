<?php

namespace warm\common\service\notice;

/**
 * 微信公众号通知渠道
 * 
 * 实现微信公众号模板消息发送功能
 */
class WechatOfficialAccountChannel implements NoticeChannelInterface
{
    /**
     * 发送微信公众号模板消息
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
        
        // 获取用户openid
        $openid = $params['openid'] ?? '';
        if (empty($openid)) {
            return false;
        }
        
        // 根据场景获取模板ID和数据
        $templateData = $this->getTemplateDataByScene($scene, $params);
        
        if (empty($templateData)) {
            return false;
        }
        
        // 调用微信公众号模板消息接口
        return $this->sendTemplateMessage($openid, $templateData, $config);
    }
    
    /**
     * 获取渠道名称
     * 
     * @return string
     */
    public function getName(): string
    {
        return 'wechat_official_account';
    }
    
    /**
     * 获取渠道描述
     * 
     * @return string
     */
    public function getDescription(): string
    {
        return '微信公众号';
    }
    
    /**
     * 验证配置是否完整
     * 
     * @param array $config 配置参数
     * @return bool
     */
    public function validateConfig(array $config): bool
    {
        return isset($config['app_id']) && 
               isset($config['app_secret']) && 
               isset($config['enable']) && 
               $config['enable'];
    }
    
    /**
     * 根据场景获取模板数据
     * 
     * @param string $scene 场景ID
     * @param array $params 参数
     * @return array
     */
    private function getTemplateDataByScene(string $scene, array $params): array
    {
        // 实际项目中应该从数据库或配置文件中获取模板
        $templates = [
            'user_register' => [
                'template_id' => 'TEMPLATE_ID_REGISTER',
                'data' => [
                    'first' => '恭喜您注册成功',
                    'keyword1' => $params['username'] ?? '',
                    'keyword2' => date('Y-m-d H:i:s'),
                    'remark' => '感谢您的注册，祝您使用愉快！'
                ]
            ],
            'order_created' => [
                'template_id' => 'TEMPLATE_ID_ORDER_CREATED',
                'data' => [
                    'first' => '您的订单已创建成功',
                    'keyword1' => $params['order_no'] ?? '',
                    'keyword2' => $params['amount'] ?? '',
                    'keyword3' => date('Y-m-d H:i:s'),
                    'remark' => '我们将尽快为您处理订单'
                ]
            ],
            'order_paid' => [
                'template_id' => 'TEMPLATE_ID_ORDER_PAID',
                'data' => [
                    'first' => '您的订单已支付成功',
                    'keyword1' => $params['order_no'] ?? '',
                    'keyword2' => $params['amount'] ?? '',
                    'keyword3' => date('Y-m-d H:i:s'),
                    'remark' => '感谢您的购买，我们将尽快发货'
                ]
            ]
        ];
        
        return $templates[$scene] ?? [];
    }
    
    /**
     * 发送模板消息
     * 
     * @param string $openid 用户openid
     * @param array $templateData 模板数据
     * @param array $config 配置信息
     * @return bool
     */
    private function sendTemplateMessage(string $openid, array $templateData, array $config): bool
    {
        // 这里应该是实际的微信公众号模板消息接口调用
        // 为演示目的，我们只记录日志
        \support\Log::info('Wechat Official Account Template Message sent', [
            'openid' => $openid,
            'template_data' => $templateData,
            'config' => $config
        ]);
        
        // 模拟发送结果
        return true;
    }
}