<?php

namespace warm\admin\service\notice;

use warm\admin\service\AdminService;
use warm\common\service\NoticeConfigDefaults;

/**
 * 通知配置服务类
 *
 * 提供通知配置管理功能
 */
class NoticeConfigService extends AdminService
{
    protected string $modelName = '';

    /**
     * 获取主键字段名
     *
     * @return string 主键字段名
     */
    public function primaryKey(): string
    {
        return 'id';
    }

    /**
     * 获取通知配置
     *
     * @return array
     */
    public function list(): array
    {
        $config = $this->get();
        $data = [];
        $channels = $this->getChannels();
        foreach ($channels as $name => $description) {
            $status = 0;
            if (isset($config[$name])) {
                if ($name === 'sms') {
                    // 短信特殊处理
                    $smsConfig = $config[$name];
                    // 检查是否有启用的网关
                    if (isset($smsConfig['default']['gateways']) && !empty($smsConfig['default']['gateways'])) {
                        $status = 1;
                    }
                } else {
                    $status = $config[$name]['enable'] ?? 0;
                }
            }

            $data[] = [
                'id' => $name,
                'name' => $name,
                'description' => $description,
                'status' => $status,
            ];
        }
        return $data;
    }

    public function get(): array
    {
        return [
            'sms' => $this->getSmsConfig(),
            'wechat_official_account' => $this->getWechatOfficialAccountConfig(),
            'wechat_mini_program' => $this->getWechatMiniProgramConfig(),
            'email' => $this->getEmailConfig(),
        ];
    }

    /**
     * 获取编辑数据
     *
     * @param mixed $id 数据ID
     * @return array 编辑数据
     */
    public function getEditData(mixed $id): array
    {
        $config = $this->get();

        // 根据渠道类型返回相应的配置数据
        if ($id === 'sms') {
            // 对于短信渠道，返回配置数据
            $data = $config['sms'] ?? [];
            $data['id'] = $id;
            $data['description'] = '短信通知';
        } else {
            $data = $config[$id] ?? [];
            $data['id'] = $id;

            // 添加描述信息
            $channels = $this->getChannels();
            $data['description'] = $channels[$id] ?? '';
        }

        return $data;
    }

    /**
     * 获取渠道列表
     *
     * @return array
     */
    private function getChannels(): array
    {
        return [
            'sms' => '短信通知',
            'wechat_official_account' => '微信公众号',
            'wechat_mini_program' => '微信小程序',
            'email' => '邮件通知',
        ];
    }

    /**
     * 获取短信配置
     *
     * @return array
     */
    public function getSmsConfig(): array
    {
        // 返回符合 overtrue/easy-sms 格式的配置
        return systemConfig()->get(NoticeConfigDefaults::KEY_SMS_CONFIG, NoticeConfigDefaults::getSmsConfigDefault());
    }

    /**
     * 获取微信公众号配置
     *
     * @return array
     */
    public function getWechatOfficialAccountConfig(): array
    {
        return systemConfig()->get(NoticeConfigDefaults::KEY_WECHAT_OFFICIAL_ACCOUNT_CONFIG, NoticeConfigDefaults::getWechatOfficialAccountConfigDefault());
    }

    /**
     * 获取微信小程序配置
     *
     * @return array
     */
    public function getWechatMiniProgramConfig(): array
    {
        return systemConfig()->get(NoticeConfigDefaults::KEY_WECHAT_MINI_PROGRAM_CONFIG, NoticeConfigDefaults::getWechatMiniProgramConfigDefault());
    }

    /**
     * 获取邮件配置
     *
     * @return array
     */
    private function getEmailConfig(): array
    {
        return systemConfig()->get(NoticeConfigDefaults::KEY_EMAIL_CONFIG, NoticeConfigDefaults::getEmailConfigDefault());
    }

    /**
     * 保存配置
     *
     * @param string $channel 渠道名称
     * @param array $data 配置数据
     * @return bool
     */
    public function saveConfig(string $channel, array $data): bool
    {
        switch ($channel) {
            case 'sms':
                $key = NoticeConfigDefaults::KEY_SMS_CONFIG;
                $config = systemConfig()->get($key, NoticeConfigDefaults::getSmsConfigDefault());
                return systemConfig()->set($key, array_merge($config, $data));
            case 'wechat_mini_program':
                $key = NoticeConfigDefaults::KEY_WECHAT_MINI_PROGRAM_CONFIG;
                return systemConfig()->set($key, $data);
            case 'email':
                $key = NoticeConfigDefaults::KEY_EMAIL_CONFIG;
                return systemConfig()->set($key, $data);
            case 'wechat_official_account':
                $key = NoticeConfigDefaults::KEY_WECHAT_OFFICIAL_ACCOUNT_CONFIG;
                return systemConfig()->set($key, $data);
            default:
                return false;
        }
    }

    /**
     * 获取场景配置
     *
     * @return array
     */
    public function getSceneConfig(): array
    {
        return [
            'scenes' => $this->getNoticeScenes(),
            'channels' => $this->getNoticeChannels(),
            'scene_channels' => $this->getSceneChannelMapping(),
        ];
    }

    /**
     * 获取通知场景列表
     *
     * @return array
     */
    private function getNoticeScenes(): array
    {
        // 使用反射获取通知场景枚举的描述
        $class = new \ReflectionClass(\warm\common\enum\NoticeEnum::class);
        $scenes = [];
        foreach ($class->getConstants() as $name => $value) {
            $scenes[$value] = $name;
        }
        return $scenes;
    }

    /**
     * 获取通知渠道列表
     *
     * @return array
     */
    private function getNoticeChannels(): array
    {
        return $this->getChannels();
    }

    /**
     * 获取场景与渠道映射配置
     *
     * @return array
     */
    private function getSceneChannelMapping(): array
    {
        return systemConfig()->get(NoticeConfigDefaults::KEY_NOTICE_SCENE_CHANNELS, NoticeConfigDefaults::getSceneChannelMappingDefault());
    }

    /**
     * 保存场景与渠道映射配置
     *
     * @param array $data 配置数据
     * @return bool
     */
    public function saveSceneChannelMapping(array $data): bool
    {
        return systemConfig()->set(NoticeConfigDefaults::KEY_NOTICE_SCENE_CHANNELS, $data);
    }
}