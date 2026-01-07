<?php

namespace warm\framework\filesystem\facade;

use warm\framework\filesystem\FilesystemAdapter;
use warm\framework\filesystem\FilesystemManager;

/**
 * Storage 门面
 * 提供静态调用方式，类似 Laravel Storage
 */
class Storage
{
    /**
     * 获取磁盘实例
     * 
     * @param string|null $disk
     * @return FilesystemAdapter
     */
    public static function disk(?string $disk = null): FilesystemAdapter
    {
        return FilesystemManager::disk($disk);
    }

    /**
     * 静态调用转发到默认磁盘
     * 
     * @param string $method
     * @param array $arguments
     * @return mixed
     */
    public static function __callStatic(string $method, array $arguments)
    {
        $disk = FilesystemManager::disk();
        return $disk->$method(...$arguments);
    }

    /**
     * 便捷方法：put
     * 
     * @param string $path
     * @param string|resource $contents
     * @param array $config
     * @return bool
     */
    public static function put(string $path, $contents, array $config = []): bool
    {
        return static::disk()->put($path, $contents, $config);
    }

    /**
     * 便捷方法：get
     * 
     * @param string $path
     * @return string
     */
    public static function get(string $path): string
    {
        return static::disk()->get($path);
    }

    /**
     * 便捷方法：exists
     * 
     * @param string $path
     * @return bool
     */
    public static function exists(string $path): bool
    {
        return static::disk()->exists($path);
    }

    /**
     * 便捷方法：delete
     * 
     * @param string|array $paths
     * @return bool
     */
    public static function delete($paths): bool
    {
        return static::disk()->delete($paths);
    }

    /**
     * 便捷方法：url
     * 
     * @param string $path
     * @return string
     */
    public static function url(string $path): string
    {
        return static::disk()->url($path);
    }
}

