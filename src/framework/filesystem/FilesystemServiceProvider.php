<?php

namespace warm\framework\filesystem;

use support\Container;
use Webman\Bootstrap;

/**
 * 文件系统服务提供者
 * 
 * 用于注册文件系统相关服务到容器
 * 提供 (new \Illuminate\Filesystem\Filesystem) 功能支持
 */
class FilesystemServiceProvider implements Bootstrap
{
    /**
     * 启动服务提供者
     * 
     * @param \Workerman\Worker|null $worker
     * @return void
     */
    public static function start($worker): void
    {
        // 注册 'files' 服务到容器（单例模式）
        Container::instance()->singleton('files', function () {
            return new Filesystem();
        });
    }
}

