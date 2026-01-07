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

    /**
     * 便捷方法：missing
     * 
     * @param string $path
     * @return bool
     */
    public static function missing(string $path): bool
    {
        return static::disk()->missing($path);
    }

    /**
     * 便捷方法：copy
     * 
     * @param string $from
     * @param string $to
     * @return bool
     */
    public static function copy(string $from, string $to): bool
    {
        return static::disk()->copy($from, $to);
    }

    /**
     * 便捷方法：move
     * 
     * @param string $from
     * @param string $to
     * @return bool
     */
    public static function move(string $from, string $to): bool
    {
        return static::disk()->move($from, $to);
    }

    /**
     * 便捷方法：size
     * 
     * @param string $path
     * @return int
     */
    public static function size(string $path): int
    {
        return static::disk()->size($path);
    }

    /**
     * 便捷方法：lastModified
     * 
     * @param string $path
     * @return int
     */
    public static function lastModified(string $path): int
    {
        return static::disk()->lastModified($path);
    }

    /**
     * 便捷方法：mimeType
     * 
     * @param string $path
     * @return string
     */
    public static function mimeType(string $path): string
    {
        return static::disk()->mimeType($path);
    }

    /**
     * 便捷方法：files
     * 
     * @param string $directory
     * @param bool $recursive
     * @return array
     */
    public static function files(string $directory = '', bool $recursive = false): array
    {
        return static::disk()->files($directory, $recursive);
    }

    /**
     * 便捷方法：allFiles
     * 
     * @param string $directory
     * @return array
     */
    public static function allFiles(string $directory = ''): array
    {
        return static::disk()->allFiles($directory);
    }

    /**
     * 便捷方法：directories
     * 
     * @param string $directory
     * @param bool $recursive
     * @return array
     */
    public static function directories(string $directory = '', bool $recursive = false): array
    {
        return static::disk()->directories($directory, $recursive);
    }

    /**
     * 便捷方法：allDirectories
     * 
     * @param string $directory
     * @return array
     */
    public static function allDirectories(string $directory = ''): array
    {
        return static::disk()->allDirectories($directory);
    }

    /**
     * 便捷方法：makeDirectory
     * 
     * @param string $path
     * @return bool
     */
    public static function makeDirectory(string $path): bool
    {
        return static::disk()->makeDirectory($path);
    }

    /**
     * 便捷方法：deleteDirectory
     * 
     * @param string $path
     * @return bool
     */
    public static function deleteDirectory(string $path): bool
    {
        return static::disk()->deleteDirectory($path);
    }

    /**
     * 便捷方法：getVisibility
     * 
     * @param string $path
     * @return string
     */
    public static function getVisibility(string $path): string
    {
        return static::disk()->getVisibility($path);
    }

    /**
     * 便捷方法：setVisibility
     * 
     * @param string $path
     * @param string $visibility
     * @return bool
     */
    public static function setVisibility(string $path, string $visibility): bool
    {
        return static::disk()->setVisibility($path, $visibility);
    }

    /**
     * 便捷方法：prepend
     * 
     * @param string $path
     * @param string $data
     * @return bool
     */
    public static function prepend(string $path, string $data): bool
    {
        return static::disk()->prepend($path, $data);
    }

    /**
     * 便捷方法：append
     * 
     * @param string $path
     * @param string $data
     * @return bool
     */
    public static function append(string $path, string $data): bool
    {
        return static::disk()->append($path, $data);
    }

    /**
     * 便捷方法：putFile
     * 
     * @param string $path
     * @param string|\SplFileInfo $file
     * @param array $config
     * @return string|false
     */
    public static function putFile(string $path, $file, array $config = [])
    {
        return static::disk()->putFile($path, $file, $config);
    }

    /**
     * 便捷方法：putFileAs
     * 
     * @param string $path
     * @param string|\SplFileInfo $file
     * @param string $name
     * @param array $config
     * @return string|false
     */
    public static function putFileAs(string $path, $file, string $name, array $config = [])
    {
        return static::disk()->putFileAs($path, $file, $name, $config);
    }

    /**
     * 便捷方法：path
     * 
     * @param string $path
     * @return string
     */
    public static function path(string $path): string
    {
        return static::disk()->path($path);
    }

    /**
     * 便捷方法：hash
     * 
     * @param string $path
     * @return string
     */
    public static function hash(string $path): string
    {
        return static::disk()->hash($path);
    }

    /**
     * 便捷方法：checksum
     * 
     * @param string $path
     * @param string $algorithm
     * @return string
     */
    public static function checksum(string $path, string $algorithm = 'md5'): string
    {
        return static::disk()->checksum($path, $algorithm);
    }

    /**
     * 便捷方法：temporaryUrl
     * 
     * @param string $path
     * @param \DateTimeInterface $expiration
     * @param array $options
     * @return string
     */
    public static function temporaryUrl(string $path, \DateTimeInterface $expiration, array $options = []): string
    {
        return static::disk()->temporaryUrl($path, $expiration, $options);
    }

    /**
     * 获取适配器实例
     * 
     * @param string|null $disk
     * @return mixed
     */
    public static function getAdapter(?string $disk = null)
    {
        return static::disk($disk)->getAdapter();
    }

    /**
     * 获取磁盘配置
     * 
     * @param string|null $disk
     * @return array
     */
    public static function getConfig(?string $disk = null): array
    {
        return static::disk($disk)->getConfig();
    }
}

