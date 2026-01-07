<?php

namespace warm\framework\filesystem\Drivers;

use League\Flysystem\Local\LocalFilesystemAdapter;
use League\Flysystem\Filesystem;
use warm\framework\filesystem\Exception\DriverException;

/**
 * 本地文件系统驱动
 */
class LocalDriver
{
    /**
     * 创建适配器
     * 
     * @param array $config
     * @return Filesystem
     * @throws DriverException
     */
    public static function createAdapter(array $config): Filesystem
    {
        $root = $config['root'] ?? base_path('storage/app');
        
        // 确保目录存在
        if (!is_dir($root)) {
            @mkdir($root, 0755, true);
        }

        if (!is_dir($root)) {
            throw new DriverException("Local filesystem root directory [{$root}] does not exist and could not be created.");
        }

        $adapter = new LocalFilesystemAdapter($root);
        return new Filesystem($adapter);
    }
}

