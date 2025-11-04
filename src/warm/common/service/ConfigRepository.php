<?php

namespace warm\common\service;
use support\Db;
use Redis;

class ConfigRepository
{
    protected static array $config = [];
    protected static string $cacheKey = 'app:config';
    protected static int $ttl = 3600; // 1小时缓存

    /**
     * 初始化配置，从Redis或DB加载
     */
    public static function load(): void
    {
        $redis = static::redis();

        $cached = $redis->get(static::$cacheKey);
        if ($cached) {
            static::$config = json_decode($cached, true);
            return;
        }

        static::reload();
    }

    /**
     * 从数据库强制刷新配置
     */
    public static function reload(): void
    {
        $data = Db::table('system_configs')->pluck('values', 'key')->toArray();
        static::$config = $data;
//        static::redis()->setex(static::$cacheKey, static::$ttl, json_encode($data, JSON_UNESCAPED_UNICODE));
        echo "[ConfigRepository] Configuration reloaded\n";
    }

    /**
     * 获取配置值
     */
    public static function get(?string $key = null, $default = null)
    {
        if ($key === null) {
            return static::$config;
        }
        return static::$config[$key] ?? $default;
    }

    /**
     * 更新配置（数据库 + 缓存）
     */
    public static function set(string $key, $value): void
    {
        static::$config[$key] = $value;
        Db::table('settings')->updateOrInsert(['key' => $key], ['value' => $value]);
        static::redis()->setex(static::$cacheKey, static::$ttl, json_encode(static::$config));
        echo "[ConfigRepository] Updated key: {$key}\n";
    }

    /**
     * Redis 连接
     */
    protected static function redis(): \Redis
    {
        static $r;
        if (!$r) {
            $r = new \Redis();
            $r->connect('127.0.0.1', 6379);
        }
        return $r;
    }
}