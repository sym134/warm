<?php

namespace warm\framework\support\facade;

use Closure;
use support\Cache as SymfonyCache;

class Cache
{

    private static string $prefix = 'jizhi_warm_';

    public static function rememberForever($key, Closure $callback)
    {
        $value = $callback($key);
        if (symfonyCache::set($key, $value)) {
            return $value;
        }
        return null;
    }

    public static function remember($key, $ttl, Closure $callback)
    {
        $value = self::get($key);
        if (!is_null($value)) {
            return $value;
        }

        $value = $callback();

        self::put($key, $value, value($ttl, $value));

        return $value;
    }

    public static function forget($key): bool
    {
        return symfonyCache::delete(self::getKey($key));
    }

    public static function delete($key): bool
    {
        return SymfonyCache::delete(self::getKey($key));
    }

    public static function put($key, $getCaptcha, $int = null): bool
    {
        return SymfonyCache::set(self::getKey($key), $getCaptcha, $int);
    }

    public static function has($key): bool
    {
        return SymfonyCache::has(self::getKey($key));
    }

    public static function forever($key, bool $true): bool
    {
        return SymfonyCache::set(self::getKey($key), $true);
    }

    public static function pull($key): ?string
    {
        if (!self::has($key)) {
            return null;
        }
        $res = self::get($key);
        self::delete($key);
        return $res;
    }

    public static function get($key)
    {
        return SymfonyCache::get(self::getKey($key));
    }

    private static function getKey($key): string
    {
        if (isset(request()->tenant)) {
            return self::$prefix . 'tenant_' . request()->tenant . '_' . $key;
        }
        return self::$prefix . $key;
    }
}
