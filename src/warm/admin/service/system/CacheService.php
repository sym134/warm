<?php

namespace warm\admin\service\system;

/**
 * 缓存服务类
 * 
 * 提供缓存清理功能
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
        foreach ($data as $key => $val) {
            if ($key === 'storage' && $val === 1) {
                warmConfig()->clearCache('storage');
            }
        }
    }
}