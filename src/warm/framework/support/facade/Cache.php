<?php

namespace warm\framework\support\facade;

use Closure;
use support\Cache as SymfonyCache;

/**
 * 缓存门面类
 * 
 * 提供简化的缓存操作接口，封装了底层缓存实现
 * 支持缓存的读取、写入、删除等操作，并处理多租户场景下的缓存键前缀
 */
class Cache
{

    /**
     * 缓存键前缀
     * 
     * 用于区分不同租户的缓存数据
     * 
     * @var string
     */
    private static string $prefix = 'jizhi_warm_';

    /**
     * 获取缓存值，如果不存在则执行回调并永久存储
     * 
     * @param string $key 缓存键
     * @param Closure $callback 回调函数，用于生成缓存值
     * @return mixed 缓存值
     */
    public static function rememberForever($key, Closure $callback): mixed
    {
        $value = self::get($key);
        if (!is_null($value)) {
            return $value;
        }

        $value = $callback();

        self::forever($key, $value);

        return $value;
    }

    /**
     * 获取缓存值，如果不存在则执行回调并存储指定时间
     * 
     * @param string $key 缓存键
     * @param mixed $ttl 缓存有效期
     * @param Closure $callback 回调函数，用于生成缓存值
     * @return mixed 缓存值
     */
    public static function remember($key, $ttl, Closure $callback): mixed
    {
        $value = self::get($key);
        if (!is_null($value)) {
            return $value;
        }

        $value = $callback();

        self::put($key, $value, value($ttl, $value));

        return $value;
    }

    /**
     * 删除指定缓存键的值
     * 
     * @param string $key 缓存键
     * @return bool 删除是否成功
     */
    public static function forget($key): bool
    {
        return symfonyCache::delete(self::getKey($key));
    }

    /**
     * 删除指定缓存键的值（别名方法）
     * 
     * @param string $key 缓存键
     * @return bool 删除是否成功
     */
    public static function delete($key): bool
    {
        return SymfonyCache::delete(self::getKey($key));
    }

    /**
     * 存储缓存值
     * 
     * @param string $key 缓存键
     * @param mixed $value 缓存值
     * @param int|null $ttl 缓存有效期（秒）
     * @return bool 存储是否成功
     */
    public static function put($key, $value, $ttl = null): bool
    {
        return SymfonyCache::set(self::getKey($key), $value, $ttl);
    }

    /**
     * 检查缓存键是否存在
     * 
     * @param string $key 缓存键
     * @return bool 缓存键是否存在
     */
    public static function has($key): bool
    {
        return SymfonyCache::has(self::getKey($key));
    }

    /**
     * 永久存储缓存值
     * 
     * @param string $key 缓存键
     * @param mixed $value 缓存值
     * @return bool 存储是否成功
     */
    public static function forever($key, $value): mixed
    {
        return SymfonyCache::set(self::getKey($key), $value);
    }

    /**
     * 获取并删除缓存值
     * 
     * @param string $key 缓存键
     * @return string|null 缓存值，不存在时返回null
     */
    public static function pull($key): ?string
    {
        if (!self::has($key)) {
            return null;
        }
        $res = self::get($key);
        self::delete($key);
        return $res;
    }

    /**
     * 获取缓存值
     * @param $key
     * @param $default
     * @return mixed
     */
    public static function get($key, $default = null): mixed
    {
        return SymfonyCache::get(self::getKey($key),$default);
    }


    public static function clear(): bool
    {
        return SymfonyCache::clear();
    }

    /**
     * 获取带前缀的缓存键
     * 
     * 根据当前租户信息生成带前缀的缓存键，确保不同租户的缓存隔离
     * 
     * @param string $key 原始缓存键
     * @return string 带前缀的缓存键
     */
    private static function getKey($key): string
    {
        if (isset(request()->tenant)) {
            return self::$prefix . 'tenant_' . request()->tenant . '_' . $key;
        }
        return self::$prefix . $key;
    }
}