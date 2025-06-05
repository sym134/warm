<?php

namespace warm\framework\filesystem;

use Iidestiny\Flysystem\Oss\OssAdapter;
use League\Flysystem\AwsS3V3\AwsS3V3Adapter;
use League\Flysystem\Filesystem;
use League\Flysystem\Local\LocalFilesystemAdapter;
use Overtrue\Flysystem\Cos\CosAdapter;
use Overtrue\Flysystem\Qiniu\QiniuAdapter;
use RuntimeException;

class FilesystemAdapter
{
    /**
     * 创建文件系统适配器
     *
     * @param string $engine 存储引擎名称
     * @param array $config 配置参数
     * @return Filesystem
     * @throws RuntimeException
     */
    public static function create(string $engine, array $config): Filesystem
    {
        $method = 'create' . ucfirst($engine);

        if (!method_exists(__CLASS__, $method)) {
            throw new RuntimeException("Unsupported storage engine: {$engine}");
        }

        return new Filesystem(self::$method($config));
    }

    /**
     * 检查适配器可用性
     */
    public static function isAdapterAvailable(string $engine): bool
    {
        $checks = [
            'local' => LocalFilesystemAdapter::class,
            'qiniu' => '\Overtrue\Flysystem\Qiniu\QiniuAdapter',
            'aliyun' => '\Iidestiny\Flysystem\Oss\OssAdapter',
            'qcloud' => '\Overtrue\Flysystem\Cos\CosAdapter',
            'aws' => '\League\Flysystem\AwsS3V3\AwsS3V3Adapter',
        ];

        return isset($checks[$engine]) && class_exists($checks[$engine]);
    }

    /*** 各存储引擎适配器创建方法 ***/

    protected static function createLocal(array $config): LocalFilesystemAdapter
    {
        $path = $config['path'] ?? 'public';
        return new LocalFilesystemAdapter($path);
    }

    protected static function createQiniu(array $config): QiniuAdapter
    {
        return new QiniuAdapter(
            $config['access_key'],
            $config['secret_key'],
            $config['bucket'],
            $config['domain'] ?? ''
        );
    }

    protected static function createAliyun(array $config): OssAdapter
    {
        return new OssAdapter(
            $config['access_key'],
            $config['secret_key'],
            $config['bucket'],
            $config['domain']
        );
    }

    protected static function createQcloud(array $config): CosAdapter
    {
        return new CosAdapter([
            'region' => $config['region'],
            'credentials' => [
                'secretId' => $config['access_key'],
                'secretKey' => $config['secret_key'],
            ],
            'bucket' => $config['bucket'],
            'cdn' => $config['domain'] ?? '',
        ]);
    }

    protected static function createAws(array $config): AwsS3V3Adapter
    {
        $client = new \Aws\S3\S3Client([
            'version' => 'latest',
            'region' => $config['region'] ?? 'us-east-1',
            'endpoint' => $config['endpoint'] ?? '',
            'credentials' => [
                'key' => $config['access_key'],
                'secret' => $config['secret_key'],
            ],
        ]);

        return new AwsS3V3Adapter(
            $client,
            $config['bucket'],
            $config['prefix'] ?? ''
        );
    }
}