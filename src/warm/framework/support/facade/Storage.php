<?php

namespace warm\framework\support\facade;

use warm\framework\filesystem\FilesystemManager;

/**
 * @method static FilesystemManager disk(string $name = null) 获取指定磁盘实例
 * @method static string getDefaultDriver() 获取默认驱动名称
 * @method static array getConfig()
 * @method static string getUploadConfig()
 * @method static void extend(string $driver, \Closure $callback) 注册自定义驱动
 * @method static bool fileExists(string $path) 检查文件是否存在
 * @method static bool directoryExists(string $path) 检查目录是否存在
 * @method static bool has(string $path) 检查路径是否存在(文件或目录)
 * @method static string read(string $path) 读取文件内容
 * @method static resource readStream(string $path) 获取文件流
 * @method static bool write(string $path, string $contents, array $config = []) 写入文件
 * @method static bool writeStream(string $path, $resource, array $config = []) 写入文件流
 * @method static string|false get(string $path) 获取文件内容
 * @method static bool delete(string $path) 删除文件
 * @method static bool deleteDirectory(string $path) 删除目录
 * @method static bool createDirectory(string $path, array $config = []) 创建目录
 * @method static int lastModified(string $path) 获取最后修改时间
 * @method static int fileSize(string $path) 获取文件大小
 * @method static string mimeType(string $path) 获取MIME类型
 * @method static array listContents(string $path = '', bool $deep = false) 列出目录内容
 */
class Storage extends Facade
{
    protected static function getFacadeClass(): string
    {
        return FilesystemManager::class;
    }

}