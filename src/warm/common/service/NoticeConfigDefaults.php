<?php

namespace warm\common\service;

/**
 * 通知配置键名和默认值管理类
 * 
 * 集中管理通知相关的配置键名和默认值，防止拼写错误
 */
class NoticeConfigDefaults
{
    // 配置键名常量
    const KEY_SMS_CONFIG = 'sms_config';
    const KEY_WECHAT_OFFICIAL_ACCOUNT_CONFIG = 'wechat_official_account_config';
    const KEY_WECHAT_MINI_PROGRAM_CONFIG = 'wechat_mini_program_config';
    const KEY_EMAIL_CONFIG = 'email_config';
    const KEY_NOTICE_SCENE_CHANNELS = 'notice_scene_channels';
    
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
     * 获取微信公众号配置默认值
     * 
     * @return array
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
     * 
     * @return array
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
}