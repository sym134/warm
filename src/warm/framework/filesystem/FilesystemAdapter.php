<?php

namespace warm\framework\filesystem;

use League\Flysystem\Local\LocalFilesystemAdapter;
use RuntimeException;
use InvalidArgumentException;

class FilesystemAdapter
{
    /**
     * 创建文件系统适配器
     *
     * @param string $engine 存储引擎名称
     * @param array $config 配置参数
     * @return mixed
     * @throws RuntimeException
     */
    public static function create(string $engine, array $config): mixed
    {
        $method = 'create' . ucfirst($engine) . 'Adapter';
        if (!method_exists(__CLASS__, $method)) {
            throw new RuntimeException("Unsupported storage engine: {$engine}");
        }

        return self::$method($config);
    }

    /**
     * 检查适配器可用性
     */
    public static function isAdapterAvailable(string $engine): bool
    {
        $checks = [
            'local'  => LocalFilesystemAdapter::class,
            'qiniu'  => '\Overtrue\Flysystem\Qiniu\QiniuAdapter',
            'aliyun' => '\Iidestiny\Flysystem\Oss\OssAdapter',
            'qcloud' => '\Overtrue\Flysystem\Cos\CosAdapter',
            'aws'    => '\League\Flysystem\AwsS3V3\AwsS3V3Adapter',
        ];

        return isset($checks[$engine]) && class_exists($checks[$engine]);
    }

    /*** 各存储引擎适配器创建方法 ***/

    protected static function createLocal(array $config)
    {
        $path = $config['path'] ?? 'public';
        return new LocalFilesystemAdapter($path);
    }

    protected static function createQiniu(array $config)
    {
        return new \Overtrue\Flysystem\Qiniu\QiniuAdapter(
            $config['access_key'],
            $config['secret_key'],
            $config['bucket'],
            $config['domain'] ?? ''
        );
    }

    protected static function createAliyun(array $config)
    {
        return new \Iidestiny\Flysystem\Oss\OssAdapter(
            $config['access_key'],
            $config['secret_key'],
            $config['bucket'],
            $config['domain']
        );
    }

    protected static function createQcloud(array $config)
    {
        return new \Overtrue\Flysystem\Cos\CosAdapter([
            'region' => $config['region'],
            'credentials' => [
                'secretId'  => $config['access_key'],
                'secretKey' => $config['secret_key'],
            ],
            'bucket' => $config['bucket'],
            'cdn'    => $config['domain'] ?? '',
        ]);
    }

    protected static function createAws(array $config)
    {
        $client = new \Aws\S3\S3Client([
            'version'     => 'latest',
            'region'      => $config['region'] ?? 'us-east-1',
            'endpoint'    => $config['endpoint'] ?? '',
            'credentials' => [
                'key'    => $config['access_key'],
                'secret' => $config['secret_key'],
            ],
        ]);

        return new \League\Flysystem\AwsS3V3\AwsS3V3Adapter(
            $client,
            $config['bucket'],
            $config['prefix'] ?? ''
        );
    }
}