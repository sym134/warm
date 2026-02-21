<?php

namespace warm\admin\service\system;

use warm\common\config\CacheKeys;
use warm\common\config\ConfigDefaults;

/**
 * 缓存服务类
 * 
 * 提供缓存清理功能
 * 使用 CacheKeys 类统一管理缓存键
 * 
 * Author:sym
 * Date:2024/6/29 上午7:49
 * Company:极智科技
 */
class CacheService
{
    /**
     * 清理缓存
     * 
     * @param array $data 需要清理的缓存配置
     * @return void
     */
    public static function clear(array $data): void
    {
        $clearActions = [];
        
        // 解析清理请求
        foreach ($data as $key => $val) {
            // 处理布尔值和整数值
            if (!$val || (is_numeric($val) && intval($val) !== 1)) continue;
            
            switch ($key) {
                case 'system':
                    foreach (CacheKeys::getSystemConfigKeys() as $systemKey) {
                        $clearActions[] = [$systemKey, '系统配置'];
                    }
                    break;
                    
                case 'wechat':
                    foreach (CacheKeys::getWechatKeys() as $wechatKey) {
                        $clearActions[] = [$wechatKey, '微信配置'];
                    }
                    break;
                    
                case 'payment':
                    foreach (CacheKeys::getPaymentKeys() as $paymentKey) {
                        $clearActions[] = [$paymentKey, '支付配置'];
                    }
                    break;
                    

                case 'all':
                    // 清理所有缓存
                    foreach (CacheKeys::getAllStaticKeys() as $cacheKey) {
                        $description = CacheKeys::getKeyDescription($cacheKey);
                        $clearActions[] = [$cacheKey, $description];
                    }
                    break;
            }
        }
        
        // 执行清理操作
        foreach ($clearActions as [$cacheKey, $description]) {
            systemConfig()->clearCache($cacheKey);
        }
    }
    
    /**
     * 获取可清理的缓存类别
     * 
     * @return array
     */
    public static function getClearableCategories(): array
    {
        return [
            'system' => [
                'name' => '系统配置',
                'description' => '清理系统相关配置缓存',
                'keys' => CacheKeys::getSystemConfigKeys()
            ],
            'wechat' => [
                'name' => '微信配置',
                'description' => '清理所有微信平台配置缓存',
                'keys' => CacheKeys::getWechatKeys()
            ],
            'payment' => [
                'name' => '支付配置',
                'description' => '清理支付相关配置',
                'keys' => CacheKeys::getPaymentKeys()
            ],

            'all' => [
                'name' => '全部缓存',
                'description' => '清理所有可管理的缓存',
                'keys' => CacheKeys::getAllStaticKeys()
            ]
        ];
    }
    
    /**
     * 验证缓存键是否有效
     * 
     * @param string $key 缓存键
     * @return bool
     */
    public static function isValidCacheKey(string $key): bool
    {
        return CacheKeys::isValidKey($key);
    }
}