<?php

namespace warm\framework\filesystem\drivers;

use League\Flysystem\Filesystem;
use warm\framework\filesystem\Exception\DriverException;

/**
 * 腾讯云 COS 驱动
 * 
 * 支持 PHP 7.x 和 PHP 8.x
 */
class CosDriver
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
        // PHP 8.x 使用 v5
        if (PHP_MAJOR_VERSION >= 8) {
            if (!class_exists(\Overtrue\Flysystem\Cos\CosAdapter::class)) {
                throw new DriverException(
                    'COS adapter for PHP 8.x not installed. Run: composer require "overtrue/flysystem-cos:^5.0"'
                );
            }
        } else {
            // PHP 7.x 使用 v4
            if (!class_exists(\Overtrue\Flysystem\Cos\CosAdapter::class)) {
                throw new DriverException(
                    'COS adapter for PHP 7.x not installed. Run: composer require "overtrue/flysystem-cos:^4.0"'
                );
            }
        }

        $adapter = new \Overtrue\Flysystem\Cos\CosAdapter(
            $config['app_id'] ?? '',
            $config['secret_id'] ?? '',
            $config['secret_key'] ?? '',
            $config['region'] ?? '',
            $config['bucket'] ?? '',
            $config['domain'] ?? null,
            $config['options'] ?? []
        );

        return new Filesystem($adapter);
    }
}

