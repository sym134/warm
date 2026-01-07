<?php

namespace warm\framework\filesystem\Drivers;

use League\Flysystem\Filesystem;
use warm\framework\filesystem\Exception\DriverException;

/**
 * AWS S3 驱动
 */
class S3Driver
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
        if (!class_exists(\League\Flysystem\AwsS3V3\AwsS3V3Adapter::class)) {
            throw new DriverException(
                'S3 adapter not installed. Run: composer require "league/flysystem-aws-s3-v3:^3.0"'
            );
        }

        if (!class_exists(\Aws\S3\S3Client::class)) {
            throw new DriverException(
                'AWS SDK not installed. Run: composer require "aws/aws-sdk-php"'
            );
        }

        // 创建 S3 客户端配置
        $s3Config = [
            'credentials' => [
                'key' => $config['key'] ?? $config['access_key_id'] ?? '',
                'secret' => $config['secret'] ?? $config['secret_access_key'] ?? '',
            ],
            'region' => $config['region'] ?? 'us-east-1',
            'version' => 'latest',
        ];

        if (isset($config['endpoint'])) {
            $s3Config['endpoint'] = $config['endpoint'];
        }

        if (isset($config['use_path_style_endpoint'])) {
            $s3Config['use_path_style_endpoint'] = $config['use_path_style_endpoint'];
        }

        // 创建 S3 客户端
        $client = new \Aws\S3\S3Client($s3Config);

        $adapter = new \League\Flysystem\AwsS3V3\AwsS3V3Adapter(
            $client,
            $config['bucket'] ?? '',
            $config['prefix'] ?? '',
            $config['options'] ?? []
        );

        return new Filesystem($adapter);
    }
}

