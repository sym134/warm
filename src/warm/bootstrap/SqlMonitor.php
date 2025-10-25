<?php

namespace warm\bootstrap;

use support\Db;
use warm\admin\support\SqlRecord;
use Webman\Bootstrap;
use Workerman\Worker;

/**
 * Class SqlMonitor
 *
 * SQL监控启动类，用于在开发环境中监听和记录SQL执行情况
 */
class SqlMonitor implements Bootstrap
{

    /**
     * Webman启动时调用的方法
     *
     * 在应用启动时检查是否处于调试模式，如果是则启动SQL监听器
     * 用于在开发阶段监控SQL执行情况
     *
     * @param Worker|null $worker Workerman工作进程实例
     * @return void
     */
    public static function start(?Worker $worker): void
    {
        if (config('app.debug')) {
            SqlRecord::listen();
        }
    }

    /**
     * 应用启动时执行的引导方法
     *
     * 创建数据库迁移表，确保系统可以正常执行数据库迁移操作
     *
     * @return void
     */
    public function boot()
    {
        Db::createMigrationsTable();
    }
}