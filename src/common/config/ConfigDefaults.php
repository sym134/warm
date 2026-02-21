<?php

namespace warm\common\config;

/**
 * 通知配置键名和默认值管理类
 * 
 * 集中管理通知相关的配置键名和默认值，防止拼写错误
 */
class ConfigDefaults
{
    
    // 基础配置键
    const KEY_SMS_CONFIG = 'sms_config';
    const KEY_EMAIL_CONFIG = 'email_config';
    const KEY_ADMIN_LOCALE = 'admin_locale';
    const KEY_NOTICE_SCENE_CHANNELS = 'notice_scene_channels';
    
    // 支付配置键
    const KEY_PAYMENT_CONFIG = 'payment_config';
    const KEY_PAYMENT_ENCRYPTION_KEY = 'payment_encryption_key';
    
    // 文件系统缓存键
    const KEY_FILESYSTEMS = 'filesystems';

     // 微信相关配置键
    const KEY_WECHAT_OFFICIAL_ACCOUNT_CONFIG = 'wechat_official_account_config';
    const KEY_WECHAT_MINI_PROGRAM_CONFIG     = 'wechat_mini_program_config';
    const KEY_WECHAT_WORK_CONFIG             = 'wechat_work_config';
    const KEY_WECHAT_OPEN_PLATFORM_CONFIG    = 'wechat_open_platform_config';
    const KEY_WECHAT_OPEN_WORK_CONFIG        = 'wechat_open_work_config';
    
    /**
     * 获取短信配置默认值
     * 
     * @return array
     */
    public static function getSmsConfigDefault(): array
    {
        return [
            // HTTP 请求的超时时间（秒）
            'timeout' => 5.0,

            // HTTP 请求的连接超时时间（秒）
            'connect_timeout' => 5.0,

            // 默认发送配置
            'default' => [
                // 网关调用策略，默认：顺序调用
                'strategy' => \Overtrue\EasySms\Strategies\OrderStrategy::class,

                // 默认可用的发送网关
                'gateways' => [
                    'aliyun',
                ],
            ],

            // 可用的网关配置
            'gateways' => [
                'errorlog' => [
                    'file' => '/tmp/easy-sms.log',
                ],
                'aliyun' => [
                    'access_key_id' => '',
                    'access_key_secret' => '',
                    'sign_name' => '',
                ],
                'qcloud' => [
                    'sdk_app_id' => '',
                    'secret_id' => '',
                    'secret_key' => '',
                    'sign_name' => '',
                ],
                'smsbao' => [
                    'user' => '',
                    'password' => '',
                ],
                'yunpian' => [
                    'api_key' => '',
                ],
                'submail' => [
                    'app_id' => '',
                    'app_key' => '',
                    'project' => '',
                ],
                'luosimao' => [
                    'api_key' => '',
                ],
                'yuntongxun' => [
                    'app_id' => '',
                    'account_sid' => '',
                    'account_token' => '',
                    'is_sub_account' => '',
                ],
                'huyi' => [
                    'api_id' => '',
                    'api_key' => '',
                ],
                'juhe' => [
                    'app_key' => '',
                ],
                'baidu' => [
                    'ak' => '',
                    'sk' => '',
                    'invoke_id' => '',
                    'domain' => '',
                ],
                'huaxin' => [
                    'user_id' => '',
                    'password' => '',
                    'account' => '',
                    'ip' => '',
                    'ext_no' => '',
                ],
                'chuanglan' => [
                    'account' => '',
                    'password' => '',
                    'is_need_status' => '',
                ],
                'rongcloud' => [
                    'app_key' => '',
                    'app_secret' => '',
                ],
                'tianyiwuxian' => [
                    'username' => '',
                    'password' => '',
                    'sign_name' => '',
                ],
                'huawei' => [
                    'app_key' => '',
                    'app_secret' => '',
                    'url' => '',
                    'sign_name' => '',
                    'sender' => '',
                ],
                'yunxin' => [
                    'app_key' => '',
                    'app_secret' => '',
                    'code_length' => '',
                    'need_up' => '',
                ],
                'jdcloud' => [
                    'access_key' => '',
                    'secret_key' => '',
                    'region' => '',
                ],
                'ucloud' => [
                    'private_key' => '',
                    'public_key' => '',
                    'project_id' => '',
                ],
                'qiniu' => [
                    'access_key' => '',
                    'secret_key' => '',
                ],
                'sendcloud' => [
                    'sms_user' => '',
                    'sms_key' => '',
                ],
                'nowcn' => [
                    'key' => '',
                    'secret' => '',
                    'api_type' => '',
                ],
                'volcengine' => [
                    'access_key_id' => '',
                    'access_key_secret' => '',
                    'region_id' => '',
                    'sign_name' => '',
                    'sms_account' => '',
                ],
                'yidongmasblack' => [
                    'ecName' => '',
                    'secretKey' => '',
                    'apId' => '',
                    'sign' => '',
                    'addSerial' => '',
                ],
                'ctyun' => [
                    'access_key' => '',
                    'secret_key' => '',
                    'sign' => '',
                ],
                'weiqucloud' => [
                    'userId' => '',
                    'account' => '',
                    'password' => '',
                ],
            ],
        ];
    }

    /**
     * 获取邮件配置默认值
     * 
     * @return array
     */
    public static function getEmailConfigDefault(): array
    {
        return [
            'smtp_host' => '',
            'smtp_username' => '',
            'smtp_password' => '',
            'smtp_port' => 465,
            'smtp_secure' => 'ssl',
            'from_email' => '',
            'from_name' => '',
            'enable' => 0,
        ];
    }
    
    /**
     * 获取场景渠道映射配置默认值
     * 
     * @return array
     */
    public static function getSceneChannelMappingDefault(): array
    {
        return [];
    }

    /**
     * 获取微信公众号配置默认值
     */
    public static function getWechatOfficialAccountConfigDefault(): array
    {
        return [
            'app_id' => '',
            'app_secret' => '',
            'token' => '',
            'aes_key' => '',
            'enable' => 0,
        ];
    }

    /**
     * 获取微信小程序配置默认值
     */
    public static function getWechatMiniProgramConfigDefault(): array
    {
        return [
            'app_id' => '',
            'app_secret' => '',
            'enable' => 0,
        ];
    }

    /**
     * 获取企业微信配置默认值
     */
    public static function getWechatWorkConfigDefault(): array
    {
        return [
            'corp_id' => '',
            'secret' => '',
            'agent_id' => '',
            'token' => '',
            'aes_key' => '',
            'enable' => 0,
        ];
    }

    /**
     * 获取微信开放平台配置默认值
     */
    public static function getWechatOpenPlatformConfigDefault(): array
    {
        return [
            'app_id' => '',
            'app_secret' => '',
            'token' => '',
            'aes_key' => '',
            'enable' => 0,
        ];
    }

    /**
     * 获取企业微信开放平台配置默认值
     */
    public static function getWechatOpenWorkConfigDefault(): array
    {
        return [
            'corp_id' => '',
            'provider_secret' => '',
            'token' => '',
            'aes_key' => '',
            'enable' => 0,
        ];
    }

    /**
     * 获取支付配置默认值
     *
     * 结构对齐 yansongda/pay，支持 alipay、wechat、unipay、douyin、jsb
     */
    public static function getPaymentConfigDefault(): array
    {
        return [
            'alipay' => [
                'enable' => 0,
                'default' => [
                    'app_id' => '',
                    'app_secret_cert' => '',
                    'app_public_cert_path' => '',
                    'alipay_public_cert_path' => '',
                    'alipay_root_cert_path' => '',
                    'return_url' => '',
                    'notify_url' => '',
                    'app_auth_token' => '',
                    'service_provider_id' => '',
                    'mode' => 'normal',
                ],
            ],
            'wechat' => [
                'enable' => 0,
                'default' => [
                    'mch_id' => '',
                    'mch_secret_key_v2' => '',
                    'mch_secret_key' => '',
                    'mch_secret_cert' => '',
                    'mch_public_cert_path' => '',
                    'notify_url' => '',
                    'mp_app_id' => '',
                    'mini_app_id' => '',
                    'app_id' => '',
                    'sub_mp_app_id' => '',
                    'sub_app_id' => '',
                    'sub_mini_app_id' => '',
                    'sub_mch_id' => '',
                    'wechat_public_cert_path' => [],
                    'mode' => 'normal',
                ],
            ],
            'unipay' => [
                'enable' => 0,
                'default' => [
                    'mch_id' => '',
                    'mch_secret_key' => '',
                    'mch_cert_path' => '',
                    'mch_cert_password' => '',
                    'unipay_public_cert_path' => '',
                    'return_url' => '',
                    'notify_url' => '',
                    'mode' => 'normal',
                ],
            ],
            'douyin' => [
                'enable' => 0,
                'default' => [
                    'mch_id' => '',
                    'mch_secret_token' => '',
                    'mch_secret_salt' => '',
                    'mini_app_id' => '',
                    'thirdparty_id' => '',
                    'notify_url' => '',
                ],
            ],
            'jsb' => [
                'enable' => 0,
                'default' => [
                    'svr_code' => '',
                    'partner_id' => '',
                    'public_key_code' => '00',
                    'mch_secret_cert_path' => '',
                    'mch_public_cert_path' => '',
                    'jsb_public_cert_path' => '',
                    'notify_url' => '',
                    'mode' => 'normal',
                ],
            ],
            'logger' => [
                'enable' => false,
                'file' => './logs/pay.log',
                'level' => 'info',
                'type' => 'single',
                'max_file' => 30,
            ],
            'http' => [
                'timeout' => 5.0,
                'connect_timeout' => 5.0,
            ],
        ];
    }

    /**
     * 支付配置需加密的敏感字段（点号路径）
     *
     * @return array<int, string>
     */
    public static function getPaymentConfigSensitiveFields(): array
    {
        return [
            'alipay.default.app_secret_cert',
            'wechat.default.mch_secret_key_v2',
            'wechat.default.mch_secret_key',
            'wechat.default.mch_secret_cert',
            'unipay.default.mch_secret_key',
            'unipay.default.mch_cert_password',
            'douyin.default.mch_secret_token',
            'douyin.default.mch_secret_salt',
        ];
    }

    /**
     * 获取所有配置键与默认值映射表
     *
     * 可用于一次性初始化系统配置
     */
    public static function getAllDefaults(): array
    {
        return [
            self::KEY_SMS_CONFIG                   => self::getSmsConfigDefault(),
            self::KEY_EMAIL_CONFIG                 => self::getEmailConfigDefault(),
            self::KEY_NOTICE_SCENE_CHANNELS        => self::getSceneChannelMappingDefault(),
            self::KEY_WECHAT_OFFICIAL_ACCOUNT_CONFIG => self::getWechatOfficialAccountConfigDefault(),
            self::KEY_WECHAT_MINI_PROGRAM_CONFIG   => self::getWechatMiniProgramConfigDefault(),
            self::KEY_WECHAT_WORK_CONFIG           => self::getWechatWorkConfigDefault(),
            self::KEY_WECHAT_OPEN_PLATFORM_CONFIG  => self::getWechatOpenPlatformConfigDefault(),
            self::KEY_WECHAT_OPEN_WORK_CONFIG      => self::getWechatOpenWorkConfigDefault(),
            self::KEY_PAYMENT_CONFIG               => self::getPaymentConfigDefault(),
        ];
    }
}