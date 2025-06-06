<?php

namespace warm\framework\filesystem;

use InvalidArgumentException;
use League\Flysystem\UnableToSetVisibility;
use League\Flysystem\UnableToWriteFile;
use Psr\Http\Message\StreamInterface;
use Webman\File;
use Webman\Http\UploadFile;

/**
 * 文件系统管理器类，用于管理多个文件系统磁盘
 */
class FilesystemManager
{
    protected string $engine = 'local';

    /** @var array 自定义磁盘创建器 */
    protected array $customCreators = [];


    public function getConfig(): array
    {
        return $this->disk()->getConfig();
    }

    /**
     * 获取指定名称的磁盘实例
     * @param string|null $name 磁盘名称，如果为null则使用默认磁盘
     * @return FilesystemAdapter 文件系统适配器实例
     */
    public function disk(string $name = null)
    {
        $name = $name ?: $this->getDefaultDriver();
        return $this->resolve($name);
    }

    /**
     * 获取默认磁盘驱动名称
     * @return string 默认驱动名称
     */
    public function getDefaultDriver(): string
    {
        $filesystems = $this->getSystemsConfig();
        return $filesystems['engine'] ?? 'local';
    }

    /**
     * 解析并创建磁盘实例
     * @param string $name 磁盘名称
     */
    protected function resolve(string $name)
    {
        $config = $this->getStorageConfig($name);

        if (isset($this->customCreators[$name])) {
            return $this->callCustomCreator($name, $config);
        }

        if (isset($this->customCreators[$name])) {
            $filesystem = $this->callCustomCreator($name, $config);
        } else {
            $filesystem = FilesystemAdapter::create($name, $config);
        }

        // 封装为代理对象
        return new FilesystemDisk($filesystem, $config);

    }

    /**
     * 获取磁盘配置
     * @return array 磁盘配置数组
     */
    protected function getSystemsConfig(): array
    {
        return warmConfig()->get('filesystems');
    }

    protected function getStorageConfig(string $name): array
    {
        $filesystems = $this->getSystemsConfig();
        if (!isset($filesystems['storage'][$name])) {
            throw new InvalidArgumentException("Disk [{$name}] not configured.");
        }

        return $filesystems['storage'][$name] ?? [];
    }

    public function getUploadConfig(): array
    {
        $config = $this->getSystemsConfig();
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

    public function put(string $path, $contents, $options = []): bool
    {
        $options = is_string($options)
            ? ['visibility' => $options]
            : (array)$options;

        if ($contents instanceof File) {
            return self::putFile($path, $contents, $options);
        }

        try {
            if ($contents instanceof StreamInterface) {
                $this->disk()->writeStream($path, $contents->detach(), $options);

                return true;
            }

            is_resource($contents)
                ? $this->disk()->writeStream($path, $contents, $options)
                : $this->disk()->write($path, $contents, $options);
        } catch (UnableToWriteFile|UnableToSetVisibility $e) {
            throw_if(self::throwsExceptions(), $e);

            return false;
        }

        return true;
    }

    /**
     *
     * @param string $path
     * @param null $file
     * @param mixed $options
     * @return bool|string
     *
     * @author heimiao
     * @date 2025-06-05 16:37
     */
    public function putFile(string $path, $file = null, $options = []): bool|string
    {
        $file = is_string($file) ? new File($file) : $file;
        return self::putFileAs($path, $file, self::hashName($file->getPathname()) . '.' . $file->getUploadExtension(), $options);
    }

    public function hashName($path, $algorithm = 'md5'): bool|string
    {
        return hash_file($algorithm, $path);
    }

    /**
     *
     * @param string $path
     * @param string|UploadFile $file
     * @param string|null $name
     * @param  $options
     * @return false|string
     *
     * @author heimiao
     * @date 2025-06-05 16:36
     */
    public function putFileAs(string $path, string|UploadFile $file, string $name = null, $options = []): bool|string
    {
        $stream = fopen(is_string($file) ? $file : $file->getRealPath(), 'r');

        $result = self::put(
            $path = trim($path . '/' . $name, '/'), $stream, $options
        );

        if (is_resource($stream)) {
            fclose($stream);
        }

        return $result ? $path : false;
    }
}