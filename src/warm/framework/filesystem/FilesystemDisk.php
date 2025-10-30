<?php

namespace warm\framework\filesystem;

use League\Flysystem\FilesystemOperator;
use RuntimeException;

/**
 * 文件系统磁盘类
 * 
 * 作为文件系统操作的代理类，封装了具体的文件系统实现
 * 提供统一的接口来操作不同类型的文件系统
 */
class FilesystemDisk
{
    /**
     * 文件系统操作实例
     * 
     * @var FilesystemOperator
     */
    protected FilesystemOperator $filesystem;
    
    /**
     * 磁盘配置参数
     * 
     * @var array
     */
    protected array $config;

    /**
     * 构造函数
     * 
     * 初始化文件系统磁盘实例
     * 
     * @param FilesystemOperator $filesystem 文件系统操作实例
     * @param array $config 磁盘配置参数
     */
    public function __construct(FilesystemOperator $filesystem, array $config)
    {
        $this->filesystem = $filesystem;
        $this->config = $config;
    }

    /**
     * 获取磁盘配置
     * 
     * @return array 磁盘配置参数
     */
    public function getConfig(): array
    {
        return $this->config;
    }

    /**
     * 获取文件的公开URL
     * 
     * @param string $path 文件路径
     * @return string 文件的公开访问URL
     */
    public function publicUrl(string $path): string
    {
        // 检查文件系统是否支持publicUrl方法
        if (method_exists($this->filesystem, 'publicUrl')) {
            return $this->filesystem->publicUrl($path, $this->config);
        }
        
        // 如果不支持，则抛出异常
        throw new RuntimeException("Current filesystem driver does not support publicUrl method.");
    }

    /**
     * 魔术方法：方法调用转发
     * 
     * 将对当前对象的方法调用转发给内部的文件系统实例处理
     * 
     * @param string $method 调用的方法名
     * @param array $parameters 方法参数
     * @return mixed 方法调用结果
     */
    public function __call(string $method, array $parameters)
    {
        return $this->filesystem->$method(...$parameters);
    }
}