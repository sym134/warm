<?php

namespace warm\framework\filesystem\drivers;

use League\Flysystem\Filesystem;
use warm\framework\filesystem\Exception\DriverException;

/**
 * 阿里云 OSS 驱动
 * 
 * 支持 PHP 7.x 和 PHP 8.x
 */
class OssDriver
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
        $phpVersion = PHP_MAJOR_VERSION . '.' . PHP_MINOR_VERSION;
        
        // PHP 8.x 使用 v4
        if (PHP_MAJOR_VERSION >= 8) {
            if (!class_exists(\iidestiny\flysystem\Oss\OssAdapter::class)) {
                throw new DriverException(
                    'OSS adapter for PHP 8.x not installed. Run: composer require "iidestiny/flysystem-oss:^4"'
                );
            }
            $adapter = new \iidestiny\flysystem\Oss\OssAdapter(
                $config['access_id'] ?? $config['access_key_id'] ?? $config['access_key'] ?? '',
                $config['access_key'] ?? $config['access_key_secret'] ?? $config['secret_key'] ?? '',
                $config['bucket'] ?? '',
                $config['endpoint'] ?? '',
                $config['isCName'] ?? $config['is_cname'] ?? false,
                $config['security_token'] ?? null,
                $config['prefix'] ?? ''
            );
        } else {
            // PHP 7.x 使用 v3
            if (!class_exists(\iidestiny\flysystem\Oss\OssAdapter::class)) {
                throw new DriverException(
                    'OSS adapter for PHP 7.x not installed. Run: composer require "iidestiny/flysystem-oss:^3"'
                );
            }
            $adapter = new \iidestiny\flysystem\Oss\OssAdapter(
                $config['access_id'] ?? $config['access_key_id'] ?? $config['access_key'] ?? '',
                $config['access_key'] ?? $config['access_key_secret'] ?? $config['secret_key'] ?? '',
                $config['bucket'] ?? '',
                $config['endpoint'] ?? '',
                $config['isCName'] ?? $config['is_cname'] ?? false,
                $config['security_token'] ?? null,
                $config['prefix'] ?? ''
            );
        }

        return new Filesystem($adapter);
    }
}

