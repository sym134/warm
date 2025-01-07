<?php

namespace warm\process;

use warm\service\system\AdminCrontabService;
use Workerman\Crontab\Crontab;

class Task
{
    public function onWorkerStart(): void
    {
        $service = new AdminCrontabService();
        $taskList = $service->getModel()->where('task_status', 1)->get();

        foreach ($taskList as $item) {
            new Crontab($item->rule, function () use ($service, $item) {
                $service->run($item->id);
            });
        }
    }

    public function run($item): string
    {
        return '任务调用：' . date('Y-m-d H:i:s') . "\n";
    }
}
