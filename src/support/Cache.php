<?php

namespace warm\support;

use Closure;
use support\Cache as WebmanCache;
use support\Container;

/**
 * 缓存操作工具类
 * 基于Webman缓存组件封装，提供静态调用方式并支持缓存键自动添加前缀
 */
class Cache
{
    /**
     * 处理缓存键，为原始键名添加前缀
     * @param string $key 原始缓存键名
     * @return string 带前缀的缓存键名
     */
    protected static function getPrefixedKey(string $key): string
    {
        $cfg = config('plugin.saas.app');
        $prefix = config('cache.prefix', 'warm');

        $defaultPrefix = $cfg['cache']['default_prefix'] ?? '';
        $tenantPrefix = $cfg['cache']['tenant_prefix'] ?? 'tenant';

        // ❌ 未安装插件 / pluginContainer 不存在 / 插件未启用
        if (
            empty($cfg['enable']) || is_null(pluginContainer('saas')) ||
            !pluginContainer('saas')->has(\plugin\saas\app\support\RequestTenantContextInterface::class)
        ) {
            return "{$prefix}-{$defaultPrefix}-{$key}";
        }

        // 安全调用 TenantContext
        $tenantId = \plugin\saas\app\support\tenant\TenantContext::getTenantId();

        // 🔁 启用 SaaS 但无租户 → 默认前缀
        if (!$tenantId) {
            return "{$prefix}-{$defaultPrefix}-{$key}";
        }

        // 🔁 有租户 → 租户前缀
        return "{$prefix}-{$tenantPrefix}-{$tenantId}-{$key}";
    }

    /**
     * 获取缓存值
     * @param string $key 缓存键名
     * @param mixed $default 当缓存不存在时返回的默认值
     * @return mixed 缓存值或默认值
     */
    public static function get(string $key, mixed $default = null): mixed
    {
        return WebmanCache::get(self::getPrefixedKey($key), $default);
    }

    /**
     * 写入缓存（带过期时间）
     * @param string $key 缓存键名
     * @param mixed $value 缓存值
     * @param int|null $seconds 过期时间（秒），默认3600秒
     * @return bool 写入成功返回true，失败返回false
     */
    public static function put(string $key, mixed $value, ?int $seconds = null): bool
    {
        return WebmanCache::set(self::getPrefixedKey($key), $value, $seconds ?? 3600);
    }

    /**
     * 永久保存缓存（不过期）
     * @param string $key 缓存键名
     * @param mixed $value 缓存值
     * @return bool 保存成功返回true，失败返回false
     */
    public static function forever(string $key, mixed $value): bool
    {
        return WebmanCache::set(self::getPrefixedKey($key), $value, 0);
    }

    /**
     * 仅在键不存在时写入缓存
     * @param string $key 缓存键名
     * @param mixed $value 缓存值
     * @param int|null $seconds 过期时间（秒），默认3600秒
     * @return bool 键不存在且写入成功返回true，否则返回false
     */
    public static function add(string $key, mixed $value, ?int $seconds = null): bool
    {
        if (self::has($key)) {
            return false;
        }
        return self::put($key, $value, $seconds);
    }

    /**
     * 获取缓存值并立即删除该缓存
     * @param string $key 缓存键名
     * @param mixed $default 当缓存不存在时返回的默认值
     * @return mixed 缓存值或默认值
     */
    public static function pull(string $key, mixed $default = null): mixed
    {
        $value = self::get($key, $default);
        self::forget($key);
        return $value;
    }

    /**
     * 判断缓存键是否存在
     * @param string $key 缓存键名
     * @return bool 存在返回true，不存在返回false
     */
    public static function has(string $key): bool
    {
        return WebmanCache::has(self::getPrefixedKey($key));
    }

    /**
     * 删除指定缓存键
     * @param string $key 缓存键名
     * @return bool 删除成功返回true，失败返回false
     */
    public static function forget(string $key): bool
    {
        return WebmanCache::delete(self::getPrefixedKey($key));
    }

    /**
     * 清空所有缓存（谨慎使用）
     * @return bool 清空成功返回true，失败返回false
     */
    public static function flush(): bool
    {
        return WebmanCache::clear();
    }

    /**
     * 递增缓存值（适用于数值类型缓存）
     * @param string $key 缓存键名
     * @param int $value 递增步长，默认1
     * @return int 递增后的结果值
     */
    public static function increment(string $key, int $value = 1): int
    {
        $val = (int)self::get($key, 0) + $value;
        self::put($key, $val);
        return $val;
    }

    /**
     * 递减缓存值（适用于数值类型缓存）
     * @param string $key 缓存键名
     * @param int $value 递减步长，默认1
     * @return int 递减后的结果值
     */
    public static function decrement(string $key, int $value = 1): int
    {
        $val = (int)self::get($key, 0) - $value;
        self::put($key, $val);
        return $val;
    }

    /**
     * 自动缓存回调函数结果
     * 若缓存存在则返回缓存值，否则执行回调并缓存结果
     * @param string $key 缓存键名
     * @param int $seconds 过期时间（秒）
     * @param Closure $callback 生成缓存值的回调函数
     * @return mixed 缓存值或回调函数返回值
     */
    public static function remember(string $key, int $seconds, Closure $callback): mixed
    {
        if (self::has($key)) {
            return self::get($key);
        }

        $value = $callback();
        self::put($key, $value, $seconds);
        return $value;
    }

    /**
     * 永久缓存回调函数结果
     * 若缓存存在则返回缓存值，否则执行回调并永久缓存结果
     * @param string $key 缓存键名
     * @param Closure $callback 生成缓存值的回调函数
     * @return mixed 缓存值或回调函数返回值
     */
    public static function rememberForever(string $key, Closure $callback): mixed
    {
        if (self::has($key)) {
            return self::get($key);
        }

        $value = $callback();
        self::forever($key, $value);
        return $value;
    }

    /**
     * 批量获取多个缓存键的值
     * @param array $keys 缓存键名数组，格式：[键名1, 键名2, ...]
     * @return array 缓存结果数组，格式：[键名1 => 值1, 键名2 => 值2, ...]
     */
    public static function many(array $keys): array
    {
        $result = [];
        foreach ($keys as $key) {
            $result[$key] = self::get($key);
        }
        return $result;
    }

    /**
     * 批量写入多个缓存键值对
     * @param array $values 缓存键值对数组，格式：[键名1 => 值1, 键名2 => 值2, ...]
     * @param int|null $seconds 过期时间（秒），默认3600秒
     * @return bool 全部写入成功返回true，否则返回false
     */
    public static function putMany(array $values, ?int $seconds = null): bool
    {
        foreach ($values as $key => $value) {
            // 若有一个键写入失败则整体返回false
            if (!self::put((string)$key, $value, $seconds)) {
                return false;
            }
        }
        return true;
    }

    /**
     * Webman兼容写法（与put方法功能一致，便于IDE联想）
     * @param string $key 缓存键名
     * @param mixed $value 缓存值
     * @param int|null $seconds 过期时间（秒），默认3600秒
     * @return bool 写入成功返回true，失败返回false
     */
    public static function set(string $key, mixed $value, ?int $seconds = null): bool
    {
        return self::put($key, $value, $seconds);
    }
}