<?php

namespace warm\framework\filesystem;

use Closure;
use warm\framework\filesystem\drivers\LocalDriver;
use warm\framework\filesystem\drivers\OssDriver;
use warm\framework\filesystem\drivers\S3Driver;
use warm\framework\filesystem\drivers\QiniuDriver;
use warm\framework\filesystem\drivers\CosDriver;
use warm\framework\filesystem\drivers\MemoryDriver;
use warm\framework\filesystem\exception\DriverException;
use warm\framework\filesystem\exception\FilesystemException;
use warm\framework\filesystem\support\ConfigResolver;

/**
 * 文件系统管理器
 * 
 * 管理多个磁盘配置，提供统一入口
 * 非单例模式，每次调用都创建新实例
 */
class FilesystemManager
{
    /**
     * 自定义驱动创建器
     * 
     * @var array<string, Closure>
     */
    protected array $customDrivers = [];

    /**
     * 获取磁盘实例
     * 
     * @param string|null $disk 磁盘名称，null 使用默认磁盘
     * @return FilesystemAdapter
     * @throws FilesystemException
     */
    public static function disk(?string $disk = null): FilesystemAdapter
    {
        return (new self())->createDisk($disk);
    }

    /**
     * 创建磁盘实例
     * 
     * @param string|null $disk
     * @return FilesystemAdapter
     * @throws FilesystemException
     */
    public function createDisk(?string $disk = null): FilesystemAdapter
    {
        $disk = $disk ?? ConfigResolver::getDefaultDisk();
        $config = ConfigResolver::resolve($disk);

        $driver = $config['driver'] ?? 'local';
        $filesystem = $this->createDriver($driver, $config);

        return new FilesystemAdapter($filesystem, $config);
    }

    /**
     * 创建驱动
     * 
     * @param string $driver
     * @param array $config
     * @return \League\Flysystem\FilesystemOperator
     * @throws DriverException
     */
    protected function createDriver(string $driver, array $config)
    {
        // 检查是否有自定义驱动
        if (isset($this->customDrivers[$driver])) {
            return $this->customDrivers[$driver]($config);
        }

        // 使用内置驱动
        return match ($driver) {
            'local' => LocalDriver::createAdapter($config),
            'oss' => OssDriver::createAdapter($config),
            's3' => S3Driver::createAdapter($config),
            'qiniu' => QiniuDriver::createAdapter($config),
            'cos' => CosDriver::createAdapter($config),
            'memory' => MemoryDriver::createAdapter($config),
            default => throw new DriverException("Unsupported driver [{$driver}]."),
        };
    }

    /**
     * 扩展自定义驱动
     * 
     * @param string $driver
     * @param Closure $callback
     * @return void
     */
    public function extend(string $driver, Closure $callback): void
    {
        $this->customDrivers[$driver] = $callback;
    }

    /**
     * 获取默认磁盘名称
     * 
     * @return string
     */
    public static function getDefaultDisk(): string
    {
        return ConfigResolver::getDefaultDisk();
    }
}

