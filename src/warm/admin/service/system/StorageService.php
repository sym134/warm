<?php

namespace warm\admin\service\system;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use warm\admin\service\AdminService;

/**
 * 配置已保存
 * StorageService
 *
 * @author heimiao
 * Company:极智网络科技
 * @date 2025-01-09 10:09
 */
class StorageService extends AdminService
{
    private array $config = [

        'engine' => 'local',
        'max_size' => '1024 * 1024 * 10', //单个文件大小10M
        'ext_yes' => [], //允许上传文件类型 为空则为允许所有
        'ext_no' => [], // 不允许上传文件类型 为空则不限制
        'image_yes' => [],
        'storage' => [
            'local' => [
                'root' => 'public',
                'url' => '//127.0.0.1:8787', // 静态文件访问域名
            ],
            'qiniu' => [
                'accessKey' => '',
                'secretKey' => '',
                'bucket' => '',
                'domain' => '',
                'url' => '', // 静态文件访问域名
            ],
            'qcloud' => [
                'region' => '',
                'app_id' => 'COS_APPID',
                'secret_id' => '',
                'secret_key' => '',
                // 可选，如果 bucket 为私有访问请打开此项
                // 'signed_url' => false,
                'bucket' => '',
                'read_from_cdn' => false,
                'url' => '', // 静态文件访问域名
                // 'timeout' => 60,
                // 'connect_timeout' => 60,
                // 'cdn' => '',
                // 'scheme' => 'https',
            ],
            'aliyun' => [
                'accessId' => '',
                'accessSecret' => '',
                'bucket' => '',
                // 'endpoint'     => 'OSS_ENDPOINT',
                'url' => '', // 静态文件访问域名
                // 'timeout' => 3600,
                // 'connectTimeout' => 10,
                // 'isCName' => false,
                // 'token' => null,
                // 'proxy' => null,
            ],
        ],

    ];

    public function saveConfig(array $data): bool
    {
        warmConfig()->set('filesystems', array_merge($this->config['storage'], $data));
        return true;
    }

    public function getEditData($id): Model|Collection|Builder|array|null
    {
        return array_merge($this->config, warmConfig()->get('filesystems'));
    }
}
