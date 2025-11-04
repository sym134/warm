<?php

namespace warm\common\service\notice;

use warm\common\service\BaseService;
use warm\common\service\NoticeConfigDefaults;

/**
 * 通知服务类
 * 
 * 作为所有推送请求的统一入口，采用策略模式处理不同渠道的通知
 */
class Notice extends BaseService
{
    /**
     * 支持的通知渠道
     * 
     * @var array
     */
    private array $channels = [];
    
    /**
     * 通知配置
     * 
     * @var array
     */
    private array $config = [];
    
    /**
     * 构造函数
     */
    public function __construct()
    {
        // 初始化支持的通知渠道
        $this->registerChannel(new SmsChannel());
        $this->registerChannel(new WechatOfficialAccountChannel());
        $this->registerChannel(new WechatMiniProgramChannel());
        $this->registerChannel(new EmailChannel());
        
        // 加载配置
        $this->loadConfig();
    }
    
    /**
     * 注册通知渠道
     * 
     * @param NoticeChannelInterface $channel 渠道实例
     * @return void
     */
    public function registerChannel(NoticeChannelInterface $channel): void
    {
        $this->channels[$channel->getName()] = $channel;
    }
    
    /**
     * 加载配置
     * 
     * @return void
     */
    private function loadConfig(): void
    {
        // 从系统配置中加载各渠道配置
        $this->config = [
            'sms' => $this->getConfig(NoticeConfigDefaults::KEY_SMS_CONFIG, []),
            'wechat_official_account' => $this->getConfig(NoticeConfigDefaults::KEY_WECHAT_OFFICIAL_ACCOUNT_CONFIG, []),
            'wechat_mini_program' => $this->getConfig(NoticeConfigDefaults::KEY_WECHAT_MINI_PROGRAM_CONFIG, []),
            'email' => $this->getConfig(NoticeConfigDefaults::KEY_EMAIL_CONFIG, []),
        ];
    }
    
    /**
     * 获取配置项
     * 
     * @param string $key 配置项键名
     * @param mixed $default 默认值
     * @return mixed
     */
    private function getConfig(string $key, mixed $default = null): mixed
    {
        return systemConfig()->get($key, $default);
    }
    
    /**
     * 发送通知
     * 
     * @param string $scene 场景ID
     * @param array $params 通知参数
     * @param array $channels 指定渠道，为空则使用场景默认渠道
     * @return bool 是否发送成功
     */
    public function send(string $scene, array $params, array $channels = []): bool
    {
        // 验证场景是否有效
        $noticeEnumClass = \warm\common\enum\NoticeEnum::class;
        if (class_exists($noticeEnumClass)) {
            $reflection = new \ReflectionClass($noticeEnumClass);
            if (!in_array($scene, $reflection->getConstants())) {
                $this->setError('无效的通知场景');
                return false;
            }
        }
        
        // 如果未指定渠道，则根据场景配置获取默认渠道
        if (empty($channels)) {
            $channels = $this->getDefaultChannelsByScene($scene);
        }
        
        $success = true;
        $results = [];
        
        // 遍历所有指定的渠道发送通知
        foreach ($channels as $channelName) {
            if (!isset($this->channels[$channelName])) {
                $results[$channelName] = ['success' => false, 'error' => '不支持的通知渠道'];
                $success = false;
                continue;
            }
            
            // 获取渠道配置
            $channelConfig = $this->getChannelConfig($channelName);
            if (empty($channelConfig)) {
                $results[$channelName] = ['success' => false, 'error' => '渠道配置为空'];
                $success = false;
                continue;
            }
            
            // 发送通知
            $channel = $this->channels[$channelName];
            $result = $channel->send($scene, $params, $channelConfig);
            
            $results[$channelName] = [
                'success' => $result,
                'error' => $result ? '' : '发送失败'
            ];
            
            if (!$result) {
                $success = false;
            }
            
            // 记录发送日志
            $this->logNotification($scene, $channelName, $params, $result);
        }
        
        // 如果所有渠道都发送失败，则设置错误信息
        if (!$success) {
            $failedChannels = array_filter($results, function($result) {
                return !$result['success'];
            });
            $this->setError('以下渠道发送失败: ' . implode(', ', array_keys($failedChannels)));
        }
        
        return $success;
    }
    
    /**
     * 根据场景获取默认渠道
     * 
     * @param string $scene 场景ID
     * @return array
     */
    private function getDefaultChannelsByScene(string $scene): array
    {
        // 实际项目中应该从数据库或配置文件中获取场景与渠道的映射关系
        $sceneChannels = [
            'USER_REGISTER' => ['sms', 'email'],
            'USER_LOGIN' => ['sms'],
            'USER_PASSWORD_RESET' => ['sms', 'email'],
            'ORDER_CREATED' => ['sms', 'wechat_official_account'],
            'ORDER_PAID' => ['sms', 'wechat_official_account', 'wechat_mini_program'],
            'ORDER_SHIPPED' => ['sms', 'wechat_official_account'],
            'ORDER_COMPLETED' => ['sms', 'wechat_official_account'],
            'ORDER_CANCELLED' => ['sms', 'wechat_official_account'],
            'SYSTEM_ALERT' => ['email'],
            'SYSTEM_MAINTENANCE' => ['email', 'wechat_official_account'],
        ];
        
        return $sceneChannels[$scene] ?? ['sms'];
    }
    
    /**
     * 获取渠道配置
     * 
     * @param string $channelName 渠道名称
     * @return array
     */
    private function getChannelConfig(string $channelName): array
    {
        switch ($channelName) {
            case 'sms':
                // 短信渠道需要特殊处理，因为支持多个服务商
                $configs = $this->config['sms'];
                foreach ($configs as $config) {
                    if (isset($config['enable']) && $config['enable']) {
                        return $config;
                    }
                }
                return [];
            default:
                return $this->config[$channelName] ?? [];
        }
    }

    /**
     * 记录通知发送日志
     * 
     * @param string $scene 场景ID
     * @param string $channel 渠道名称
     * @param array $params 参数
     * @param bool $success 是否成功
     * @return void
     */
    private function logNotification(string $scene, string $channel, array $params, bool $success): void
    {
        // 记录日志
        if (class_exists(\think\facade\Log::class)) {
            \think\facade\Log::info('Notification sent', [
                'scene' => $scene,
                'channel' => $channel,
                'params' => $params,
                'success' => $success,
                'created_at' => date('Y-m-d H:i:s')
            ]);
        }
        
        // TODO: 在实际项目中，这里应该将通知记录存储到数据库中
        // 以便后续查询和分析
    }
    
    /**
     * 获取所有支持的渠道
     * 
     * @return array
     */
    public function getChannels(): array
    {
        return array_map(function ($channel) {
            return $channel->getDescription();
        }, $this->channels);
    }
    
    /**
     * 获取场景列表
     * 
     * @return array
     */
    public function getScenes(): array
    {
        // 使用反射获取通知场景枚举的描述
        if (class_exists(\warm\common\enum\NoticeEnum::class)) {
            $class = new \ReflectionClass(\warm\common\enum\NoticeEnum::class);
            $scenes = [];
            foreach ($class->getConstants() as $name => $value) {
                $scenes[$value] = $name;
            }
            return $scenes;
        }
        
        return [];
    }
}