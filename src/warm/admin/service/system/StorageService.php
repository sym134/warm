<?php

namespace warm\admin\service\system;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use warm\admin\service\AdminService;

/**
 * 存储配置服务类
 * 
 * 提供存储配置管理功能
 * 
 * @author heimiao
 * Company:极智网络科技
 * @date 2025-01-09 10:09
 */
class StorageService extends AdminService
{
    /**
     * 默认配置
     * 
     * @var array
     */
    private array $config = [

        'engine' => 'local',
        'max_size' => '1024 * 1024 * 10', //单个文件大小10M
        'ext_yes' => [], //允许上传文件类型 为空则为允许所有
        'ext_no' => [], // 不允许上传文件类型 为空则不限制
        'image_yes' => [],
        'storage' => [
            'local' => [
                'disk' => 'public',
                'root' => '',
                'domain' => '//127.0.0.1:8787', // 静态文件访问域名
            ],
            'qiniu' => [
                'root' => '',
                'accessKey' => '',
                'secretKey' => '',
                'bucket' => '',
                'domain' => '', // 静态文件访问域名
            ],
            'qcloud' => [
                'root' => '',
                'region' => '',
                'app_id' => 'COS_APPID',
                'secret_id' => '',
                'secret_key' => '',
                // 可选，如果 bucket 为私有访问请打开此项
                // 'signed_url' => false,
                'bucket' => '',
                'read_from_cdn' => false,
                'domain' => '', // 静态文件访问域名
            ],
            'aliyun' => [
                'root' => '',
                'accessId' => '',
                'accessSecret' => '',
                'bucket' => '',
                // 'endpoint'     => 'OSS_ENDPOINT',
                'domain' => '', // 静态文件访问域名
            ],
        ],

    ];

    /**
     * 保存配置
     * 
     * @param array $data 配置数据
     * @return bool 是否保存成功
     */
    public function saveConfig(array $data): bool
    {
        return warmConfig()->set('filesystems', array_merge($this->config, $data));
    }

    /**
     * 获取编辑数据
     * 
     * @param mixed $id 数据ID
     * @return Model|Collection|Builder|array|null 编辑数据
     */
    public function getEditData($id): Model|Collection|Builder|array|null
    {
        return array_merge($this->config, warmConfig()->get('filesystems', []));
    }
}