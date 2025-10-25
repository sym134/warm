<?php

namespace warm\process;

use warm\admin\service\system\SystemCrontabService;
use Workerman\Crontab\Crontab;

/**
 * 定时任务处理类
 * 
 * 处理系统定时任务的初始化和执行
 * 在Worker启动时加载并注册所有启用的定时任务
 */
class CrontabTask
{
    /**
     * Worker启动时的回调方法
     * 
     * 在Worker启动时加载所有启用的定时任务并注册到Crontab中
     * 每个任务根据其规则定时执行
     *
     * @return void
     */
    public function onWorkerStart(): void
    {
        $service = new SystemCrontabService();
        $taskList = $service->getModel()->where('task_status', 1)->get();

        foreach ($taskList as $item) {
            new Crontab($item->rule, function () use ($service, $item) {
                $service->run($item->id);
            });
        }
    }

    /**
     * 运行任务
     * 
     * 执行具体的任务逻辑，这里是一个示例实现
     *
     * @param mixed $item 任务项
     * @return string 任务执行结果信息
     */
    public function run($item): string
    {
        return '任务调用：' . date('Y-m-d H:i:s') . "\n";
    }
}