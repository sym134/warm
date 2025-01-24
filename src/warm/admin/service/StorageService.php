<?php

namespace warm\admin\service;

use Shopwwi\WebmanFilesystem\FilesystemFactory;
use Shopwwi\WebmanFilesystem\Storage;

/**
 * 存储服务
 * StorageService
 * warm\service
 *
 * Author:sym
 * Date:2024/6/27 上午7:23
 * Company:极智科技
 */
class StorageService extends Storage
{
    public static function disk(string $name = ''): StorageService
    {
        $config = warmConfig()->get('storage');
        $name = $name?:$config['engine'];
        $config = [
            'default'   => $config['engine'] ?? 'local',
            'max_size'  => $config['upload_size'] ?? 1024 * 1024 * 10, //单个文件大小10M
            'ext_yes'   => isset($config['file_type']) ? explode(',', $config['file_type']) : [], //允许上传文件类型 为空则为允许所有
            'ext_no'    => [], // 不允许上传文件类型 为空则不限制
            'image_yes' => isset($config['image_type']) ? explode(',', $config['image_type']) : [],
            'storage'   => [
                'local'  => [
                    'driver' => \Shopwwi\WebmanFilesystem\Adapter\LocalAdapterFactory::class,
                    'root'   => $config['local']['path'] ?? public_path(),
                    'url'    => $config['local']['domain'] ?? '//127.0.0.1:8787', // 静态文件访问域名
                ],
                'qiniu'  => [
                    'driver'    => \Shopwwi\WebmanFilesystem\Adapter\QiniuAdapterFactory::class,
                    'accessKey' => $config['qiniu']['access_key'] ?? '',
                    'secretKey' => $config['qiniu']['secret_key'] ?? '',
                    'bucket'    => $config['qiniu']['bucket'] ?? '',
                    'domain'    => $config['qiniu']['domain'] ?? '',
                    'url'       => $config['qiniu']['domain'] ?? '', // 静态文件访问域名
                ],
                'qcloud' => [
                    'driver'        => \Shopwwi\WebmanFilesystem\Adapter\CosAdapterFactory::class,
                    'region'        => $config['qcloud']['region'] ?? '',
                    'app_id'        => 'COS_APPID',
                    'secret_id'     => $config['qiniu']['access_key'] ?? '',
                    'secret_key'    => $config['qcloud']['secret_key'] ?? '',
                    // 可选，如果 bucket 为私有访问请打开此项
                    // 'signed_url' => false,
                    'bucket'        => $config['qcloud']['bucket'] ?? '',
                    'read_from_cdn' => false,
                    'url'           => $config['qcloud']['domain'] ?? '', // 静态文件访问域名
                    // 'timeout' => 60,
                    // 'connect_timeout' => 60,
                    // 'cdn' => '',
                    // 'scheme' => 'https',
                ],
                'aliyun' => [
                    'driver'       => \Shopwwi\WebmanFilesystem\Adapter\AliyunOssAdapterFactory::class,
                    'accessId'     => $config['qiniu']['access_key'] ?? '',
                    'accessSecret' => $config['qiniu']['secret_key'] ?? '',
                    'bucket'       => $config['qiniu']['bucket'] ?? '',
                    // 'endpoint'     => 'OSS_ENDPOINT',
                    'url'          => $config['qiniu']['domain'] ?? '', // 静态文件访问域名
                    // 'timeout' => 3600,
                    // 'connectTimeout' => 10,
                    // 'isCName' => false,
                    // 'token' => null,
                    // 'proxy' => null,
                ],
            ],
        ];
        $file = new static($config);
        return $file->adapter($name);
    }

    protected function verifyFile($file): void
    {
        if (str_contains($file->getUploadMineType(), 'image')) {
            if (!empty($this->imageYes) && !in_array(ltrim($file->getUploadMineType(), 'image/'), $this->imageYes)) {
                throw new \Exception('不允许上传图片格式' . $file->getUploadMineType());
            }
        } else {
            if (!empty($this->extYes) && !in_array($file->getUploadMineType(), $this->extYes)) {
                throw new \Exception('不允许上传文件类型' . $file->getUploadMineType());
            }
            if (!empty($this->extNo) && in_array($file->getUploadMineType(), $this->extNo)) {
                throw new \Exception('文件类型不被允许' . $file->getUploadMineType());
            }
        }

        if ($file->getSize() > $this->size) {
            throw new \Exception("上传文件过大（当前大小 {$file->getSize()}，需小于 $this->size)");
        }
    }

    public function get(string $path): string
    {
        return FilesystemFactory::get($this->adapterType, $this->config)->read($path);
    }
}
