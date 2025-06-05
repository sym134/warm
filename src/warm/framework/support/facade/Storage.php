<?php

namespace warm\framework\support\facade;

use warm\framework\filesystem\FilesystemManager;

/**
 * @method static \warm\framework\filesystem\FilesystemAdapter disk(string $name = null) 获取指定磁盘实例
 * @method static string getDefaultDriver() 获取默认驱动名称
 * @method static void extend(string $driver, \Closure $callback) 注册自定义驱动
 * @method static bool fileExists(string $path) 检查文件是否存在
 * @method static bool directoryExists(string $path) 检查目录是否存在
 * @method static bool has(string $path) 检查路径是否存在(文件或目录)
 * @method static string read(string $path) 读取文件内容
 * @method static resource readStream(string $path) 获取文件流
 * @method static bool write(string $path, string $contents, array $config = []) 写入文件
 * @method static bool writeStream(string $path, $resource, array $config = []) 写入文件流
 * @method static bool put(string $path, string $contents, array $config = []) 写入文件(同write)
 * @method static bool putStream(string $path, $resource, array $config = []) 写入文件流(同writeStream)
 * @method static string|false get(string $path) 获取文件内容
 * @method static bool delete(string $path) 删除文件
 * @method static bool deleteDirectory(string $path) 删除目录
 * @method static bool createDirectory(string $path, array $config = []) 创建目录
 * @method static bool setVisibility(string $path, string $visibility) 设置可见性
 * @method static string getVisibility(string $path) 获取可见性
 * @method static int lastModified(string $path) 获取最后修改时间
 * @method static int fileSize(string $path) 获取文件大小
 * @method static string mimeType(string $path) 获取MIME类型
 * @method static array listContents(string $path = '', bool $deep = false) 列出目录内容
 * @method static array getMetadata(string $path) 获取文件元数据
 * @method static string getUrl(string $path) 获取文件URL
 * @method static \League\Flysystem\FilesystemOperator getDriver() 获取底层驱动实例
 * @method static bool move(string $from, string $to, array $config = []) 移动文件
 * @method static bool copy(string $from, string $to, array $config = []) 复制文件
 * @method static string|false readAndDelete(string $path) 读取并删除文件
 * @method static array listFiles(string $path = '', bool $deep = false) 列出文件
 * @method static array listDirectories(string $path = '', bool $deep = false) 列出目录
 * @method static bool assertExists(string $path) 断言文件存在
 * @method static bool assertMissing(string $path) 断言文件不存在
 * @method static resource|null readStreamAndDelete(string $path) 读取流并删除文件
 */
class Storage extends Facade
{
    protected static function getFacadeClass(): string
    {
        return FilesystemManager::class;
    }
}