<?php

namespace warm\framework\filesystem;

use Iidestiny\Flysystem\Oss\OssAdapter;
use League\Flysystem\AwsS3V3\AwsS3V3Adapter;
use League\Flysystem\Filesystem;
use League\Flysystem\Local\LocalFilesystemAdapter;
use Overtrue\Flysystem\Cos\CosAdapter;
use Overtrue\Flysystem\Qiniu\QiniuAdapter;
use RuntimeException;

/**
 * 文件系统适配器类
 * 
 * 负责创建和管理不同存储引擎的适配器实例
 * 支持本地存储、七牛云、阿里云OSS、腾讯云COS、AWS S3等多种存储方式
 */
class FilesystemAdapter
{
    /**
     * 创建文件系统适配器
     *
     * 根据指定的存储引擎和配置参数创建对应的文件系统适配器实例
     *
     * @param string $engine 存储引擎名称 (local, qiniu, aliyun, qcloud, aws)
     * @param array $config 配置参数，不同引擎需要的参数不同
     * @return Filesystem 文件系统实例
     * @throws RuntimeException 当不支持指定的存储引擎时抛出异常
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
     * 
     * 检查指定的存储引擎适配器是否可用（即对应的类是否存在）
     *
     * @param string $engine 存储引擎名称
     * @return bool 适配器是否可用
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

    /**
     * 创建本地文件系统适配器
     * 
     * @param array $config 配置参数
     * @return LocalFilesystemAdapter 本地文件系统适配器实例
     */
    protected static function createLocal(array $config): LocalFilesystemAdapter
    {
        $path = $config['disk'] ?? 'public';
        return new LocalFilesystemAdapter($path);
    }

    /**
     * 创建七牛云存储适配器
     * 
     * @param array $config 配置参数，需要access_key, secret_key, bucket, domain等
     * @return QiniuAdapter 七牛云存储适配器实例
     */
    protected static function createQiniu(array $config): QiniuAdapter
    {
        return new QiniuAdapter(
            $config['access_key'],
            $config['secret_key'],
            $config['bucket'],
            $config['domain'] ?? ''
        );
    }

    /**
     * 创建阿里云OSS适配器
     * 
     * @param array $config 配置参数，需要access_key, secret_key, bucket, domain等
     * @return OssAdapter 阿里云OSS适配器实例
     */
    protected static function createAliyun(array $config): OssAdapter
    {
        return new OssAdapter(
            $config['access_key'],
            $config['secret_key'],
            $config['bucket'],
            $config['domain']
        );
    }

    /**
     * 创建腾讯云COS适配器
     * 
     * @param array $config 配置参数，需要region, access_key, secret_key, bucket, domain等
     * @return CosAdapter 腾讯云COS适配器实例
     */
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

    /**
     * 创建AWS S3适配器
     * 
     * @param array $config 配置参数，需要version, region, endpoint, access_key, secret_key, bucket, prefix等
     * @return AwsS3V3Adapter AWS S3适配器实例
     */
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