<?php

namespace warm\common\config;

class FilesystemConfig
{
    const CONFIG = [
        'max_size' => '1024 * 1024 * 10', //单个文件大小10M
        'ext_yes' => [], //允许上传文件类型 为空则为允许所有
        'ext_no' => [], // 不允许上传文件类型 为空则不限制
        'image_yes' => [],

        'default' => 'public',
        'disks' => [
            'local' => [
                'driver' => 'local',
                'root' => 'public',
                'throw' => false,
                'url' => '//127.0.0.1:8787', // 静态文件访问域名
            ],
            'public' => [
                'driver' => 'local',
                'root' => 'public',
                'throw' => false,
                'url' => '//127.0.0.1:8787',
            ],
            'qiniu' => [
                'root' => '',
                'accessKey' => '',
                'secretKey' => '',
                'bucket' => '',
                'url' => '', // 静态文件访问域名
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
                'url' => '', // 静态文件访问域名
            ],
            'aliyun' => [
                'root' => '',
                'accessId' => '',
                'accessSecret' => '',
                'bucket' => '',
                // 'endpoint'     => 'OSS_ENDPOINT',
                'url' => '', // 静态文件访问域名
            ],
        ],

    ];
}