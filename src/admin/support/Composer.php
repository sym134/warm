<?php

namespace warm\admin\support;

use Composer\Autoload\ClassLoader;

/**
 * Composer工具类
 * 
 * 该类提供对Composer相关功能的封装，包括：
 * 1. 获取Composer类加载器
 * 2. 解析composer.json文件
 * 3. 获取已安装包的版本信息
 * 4. 缓存已读取的JSON文件内容
 * 
 * 主要用于插件系统和依赖管理。
 */
class Composer
{
    /**
     * 缓存已读取的JSON文件内容
     * 
     * 键为文件路径，值为解析后的数组
     * 
     * @var array
     */
    protected static array $files = [];

    /**
     * Composer类加载器实例
     * 
     * @var ClassLoader
     */
    protected static $loader;

    /**
     * 获取 composer 类加载器
     *
     * 如果类加载器尚未初始化，则从vendor/autoload.php加载
     * 
     * @return ClassLoader Composer类加载器实例
     */
    public static function loader(): ClassLoader
    {
        // 如果类加载器尚未初始化，则加载
        if (! static::$loader) {
            static::$loader = include base_path().'/vendor/autoload.php';
        }

        return static::$loader;
    }

    /**
     * 解析composer.json文件
     * 
     * 将指定路径的JSON文件解析为ComposerProperty对象
     * 
     * @param string|null $path composer.json文件路径
     * @return ComposerProperty Composer属性对象
     */
    public static function parse(?string $path): ComposerProperty
    {
        return new ComposerProperty(static::fromJson($path));
    }

    /**
     * 获取指定包的版本信息
     * 
     * 从composer.lock文件中查找指定包的版本信息
     * 
     * @param string|null $packageName 包名称
     * @param string|null $lockFile composer.lock文件路径，默认为项目根目录下的composer.lock
     * @return string|null 包版本信息，如果未找到则返回null
     */
    public static function getVersion(?string $packageName, ?string $lockFile = null)
    {
        // 如果包名称为空，则返回null
        if (! $packageName) {
            return null;
        }

        // 如果未指定lock文件，则使用默认路径
        $lockFile = $lockFile ?: base_path('composer.lock');

        // 从lock文件中查找指定包的信息
        $content = collect(static::fromJson($lockFile)['packages'] ?? [])
            ->filter(function ($value) use ($packageName) {
                return $value['name'] == $packageName;
            })->first();

        // 返回包的版本信息
        return $content['version'] ?? null;
    }

    /**
     * 从JSON文件中读取内容
     * 
     * 读取并解析指定路径的JSON文件，结果会被缓存以提高性能
     * 
     * @param string|null $path JSON文件路径
     * @return array 解析后的数组，如果文件不存在或解析失败则返回空数组
     */
    public static function fromJson(?string $path): array
    {
        // 如果文件内容已缓存，则直接返回
        if (isset(static::$files[$path])) {
            return static::$files[$path];
        }

        // 如果路径为空或文件不存在，则返回空数组
        if (! $path || ! is_file($path)) {
            return static::$files[$path] = [];
        }

        try {
            // 读取并解析JSON文件内容
            return static::$files[$path] = (array) json_decode(app('files')->get($path), true);
        } catch (\Throwable $e) {
            // 如果解析失败，则返回空数组
        }

        return static::$files[$path] = [];
    }
}