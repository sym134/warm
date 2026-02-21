<?php

namespace warm\common\config;

/**
 * 缓存键名管理代理类
 * 
 * 
 * Author: sym
 * Date: 2024/6/29
 * Company: 极智科技
 */
class CacheKeys
{

    /**
     * 获取所有系统配置相关缓存键
     * 
     * @return array
     */
    public static function getSystemConfigKeys(): array
    {
        return [
            ConfigDefaults::KEY_FILESYSTEMS,
            ConfigDefaults::KEY_SMS_CONFIG,
            ConfigDefaults::KEY_EMAIL_CONFIG,
            ConfigDefaults::KEY_ADMIN_LOCALE,
        ];
    }

    /**
     * 获取所有微信相关缓存键
     * 
     * @return array
     */
    public static function getWechatKeys(): array
    {
        return [
            ConfigDefaults::KEY_WECHAT_OFFICIAL_ACCOUNT_CONFIG,
            ConfigDefaults::KEY_WECHAT_MINI_PROGRAM_CONFIG,
            ConfigDefaults::KEY_WECHAT_WORK_CONFIG,
            ConfigDefaults::KEY_WECHAT_OPEN_PLATFORM_CONFIG,
            ConfigDefaults::KEY_WECHAT_OPEN_WORK_CONFIG,
        ];
    }

    /**
     * 获取所有支付相关缓存键
     * 
     * @return array
     */
    public static function getPaymentKeys(): array
    {
        return [
            ConfigDefaults::KEY_PAYMENT_CONFIG,
            ConfigDefaults::KEY_PAYMENT_ENCRYPTION_KEY,
        ];
    }

    /**
     * 获取所有需要清理的缓存键类别
     * 
     * @return array
     */
    public static function getAllCategories(): array
    {
        return [
            'system' => [
                'name' => '系统配置',
                'keys' => self::getSystemConfigKeys(),
                'description' => '包括存储配置、微信配置、支付配置等系统级别配置'
            ],
            'wechat' => [
                'name' => '微信相关',
                'keys' => self::getWechatKeys(),
                'description' => '包括各种微信平台的配置和凭证缓存'
            ],
            'payment' => [
                'name' => '支付相关',
                'keys' => self::getPaymentKeys(),
                'description' => '包括支付配置和回调处理缓存'
            ],

        ];
    }

    /**
     * 根据类别获取缓存键
     * 
     * @param string $category 类别名称
     * @return array
     */
    public static function getKeysByCategory(string $category): array
    {
        $categories = self::getAllCategories();
        return $categories[$category]['keys'] ?? [];
    }

    /**
     * 获取所有缓存键（不包含前缀类型的键）
     * 
     * @return array
     */
    public static function getAllStaticKeys(): array
    {
        $allKeys = [];
        foreach (self::getAllCategories() as $category) {
            $allKeys = array_merge($allKeys, $category['keys']);
        }
        return array_unique($allKeys);
    }

    /**
     * 验证缓存键是否存在
     * 
     * @param string $key 缓存键
     * @return bool
     */
    public static function isValidKey(string $key): bool
    {
        return in_array($key, self::getAllStaticKeys());
    }

    /**
     * 获取缓存键的描述信息
     * 
     * @param string $key 缓存键
     * @return string
     */
    public static function getKeyDescription(string $key): string
    {
        $descriptions = [
            ConfigDefaults::KEY_FILESYSTEMS => '文件配置缓存',
            ConfigDefaults::KEY_WECHAT_OFFICIAL_ACCOUNT_CONFIG => '微信公众号配置缓存',
            ConfigDefaults::KEY_WECHAT_MINI_PROGRAM_CONFIG => '微信小程序配置缓存',
            ConfigDefaults::KEY_WECHAT_WORK_CONFIG => '企业微信配置缓存',
            ConfigDefaults::KEY_WECHAT_OPEN_PLATFORM_CONFIG => '微信开放平台配置缓存',
            ConfigDefaults::KEY_WECHAT_OPEN_WORK_CONFIG => '企业微信开放平台配置缓存',
            ConfigDefaults::KEY_PAYMENT_CONFIG => '支付配置缓存',
            ConfigDefaults::KEY_PAYMENT_ENCRYPTION_KEY => '支付加密密钥缓存',
            ConfigDefaults::KEY_ADMIN_LOCALE => '管理员语言设置缓存',
        ];

        return $descriptions[$key] ?? '未知缓存键';
    }
}
