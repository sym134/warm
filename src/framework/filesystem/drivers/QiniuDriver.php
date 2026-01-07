<?php

namespace warm\framework\filesystem\drivers;

use League\Flysystem\Filesystem;
use warm\framework\filesystem\Exception\DriverException;

/**
 * 七牛云驱动
 * 
 * 支持 PHP 7.x 和 PHP 8.x
 */
class QiniuDriver
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
        // PHP 8.x 使用 v3
        if (PHP_MAJOR_VERSION >= 8) {
            if (!class_exists(\Overtrue\Flysystem\Qiniu\QiniuAdapter::class)) {
                throw new DriverException(
                    'Qiniu adapter for PHP 8.x not installed. Run: composer require "overtrue/flysystem-qiniu:^3.0"'
                );
            }
        } else {
            // PHP 7.x 使用 v2
            if (!class_exists(\Overtrue\Flysystem\Qiniu\QiniuAdapter::class)) {
                throw new DriverException(
                    'Qiniu adapter for PHP 7.x not installed. Run: composer require "overtrue/flysystem-qiniu:^2.0"'
                );
            }
        }

        $adapter = new \Overtrue\Flysystem\Qiniu\QiniuAdapter(
            $config['access_key'] ?? '',
            $config['secret_key'] ?? '',
            $config['bucket'] ?? '',
            $config['domain'] ?? ''
        );

        return new Filesystem($adapter);
    }
}

