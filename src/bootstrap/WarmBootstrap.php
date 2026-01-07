<?php

namespace warm\bootstrap;

use warm\admin\support\cores\Api;
use Webman\Bootstrap;
use Workerman\Worker;

/**
 * Warm 启动类
 * 
 * 用于在 Webman 启动时初始化 Warm 相关的核心服务
 * 包括 API 管理、上下文管理等
 */
class WarmBootstrap implements Bootstrap
{
    /**
     * 初始化标识
     * 
     * @var bool
     */
    protected static bool $initialized = false;

    /**
     * Webman 启动时调用
     * 
     * @param Worker|null $worker Workerman 工作进程实例
     * @return void
     */
    public static function start(?Worker $worker): void
    {
        if (self::$initialized) {
            return;
        }

        // 启动 API 管理器
        Api::boot();

        // 标记为已初始化
        self::$initialized = true;

        echo "[WarmBootstrap] Warm services initialized\n";
    }
}

