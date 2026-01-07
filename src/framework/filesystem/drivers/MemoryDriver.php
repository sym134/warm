<?php

namespace warm\framework\filesystem\Drivers;

use League\Flysystem\Filesystem;
use League\Flysystem\InMemory\InMemoryFilesystemAdapter;
use warm\framework\filesystem\Exception\DriverException;

/**
 * 内存驱动
 */
class MemoryDriver
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
        if (!class_exists(\League\Flysystem\InMemory\InMemoryFilesystemAdapter::class)) {
            throw new DriverException(
                'Memory adapter not installed. Run: composer require "league/flysystem-memory:^3.0"'
            );
        }

        $adapter = new InMemoryFilesystemAdapter();
        return new Filesystem($adapter);
    }
}

