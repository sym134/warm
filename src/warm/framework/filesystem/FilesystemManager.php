<?php

namespace warm\framework\filesystem;

use InvalidArgumentException;

/**
 * 文件系统管理器类，用于管理多个文件系统磁盘
 */
class FilesystemManager
{
    /** @var array 已初始化的磁盘实例缓存 */
    protected array $disks = [];

    /** @var array 自定义磁盘创建器 */
    protected array $customCreators = [];

    /**
     * 获取指定名称的磁盘实例
     * @param string|null $name 磁盘名称，如果为null则使用默认磁盘
     * @return FilesystemAdapter 文件系统适配器实例
     */
    public function disk(string $name = null)
    {
        $name = $name ?: $this->getDefaultDriver();

        return $this->disks[$name] ?? $this->disks[$name] = $this->resolve($name);
    }

    /**
     * 获取默认磁盘驱动名称
     * @return string 默认驱动名称
     */
    public function getDefaultDriver(): string
    {
        $filesystems = warmConfig()->get('filesystems');
        return $filesystems['engine'] ?? 'local';
    }

    /**
     * 解析并创建磁盘实例
     * @param string $name 磁盘名称
     * @return FilesystemAdapter 文件系统适配器实例
     */
    protected function resolve(string $name)
    {
        $config = $this->getConfig($name);

        if (isset($this->customCreators[$name])) {
            return $this->callCustomCreator($name, $config);
        }

        return FilesystemAdapter::create($config[$name], $config);
    }

    /**
     * 获取磁盘配置
     * @param string $name 磁盘名称
     * @return array 磁盘配置数组
     * @throws InvalidArgumentException 当磁盘未配置时抛出
     */
    protected function getConfig(string $name): array
    {
        // 从配置中获取磁盘配置
        $filesystems = warmConfig()->get('filesystems');

        if (!isset($filesystems[$name])) {
            throw new InvalidArgumentException("Disk [{$name}] not configured.");
        }

        return $filesystems[$name];
    }

    public function getUploadConfig(): array
    {
        $config = $this->getConfig('filesystems');

        return [
            'file_type' => $config['file_type'] ?? '',
            'image_type' => $config['image_type'] ?? '',
            'upload_size' => $config['upload_size'] ?? 0
        ];
    }

    /**
     * 注册自定义磁盘创建器
     * @param string $driver 驱动名称
     * @param callable $callback 创建回调函数
     */
    public function extend(string $driver, callable $callback)
    {
        $this->customCreators[$driver] = $callback;
    }

    /**
     * 调用自定义创建器创建磁盘实例
     * @param array $config 磁盘配置
     * @return mixed 自定义创建器返回的实例
     */
    protected function callCustomCreator(string $name, array $config)
    {
        return $this->customCreators[$name]($config);
    }

    /**
     * 魔术方法，将方法调用转发到默认磁盘
     * @param string $method 方法名
     * @param array $parameters 方法参数
     * @return mixed 方法调用结果
     */
    public function __call($method, $parameters)
    {
        return $this->disk()->$method(...$parameters);
    }
}