<?php

namespace warm\bootstrap;

use support\Db;
use warm\admin\support\SqlRecord;
use Webman\Bootstrap;
use Workerman\Worker;

class SqlMonitor implements Bootstrap
{

    public static function start(?Worker $worker): void
    {
        if (config('app.debug')) {
            SqlRecord::listen();
        }
    }

    public function boot()
    {
        Db::createMigrationsTable();
    }
}
