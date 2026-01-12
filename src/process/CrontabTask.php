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
            // 验证 Cron 表达式格式
            if (empty($item->rule)) {
                continue;
            }
            
            try {
                new Crontab($item->rule, function () use ($service, $item) {
                    $service->run($item->id);
                });
            } catch (\Exception $e) {
                // 记录任务注册失败的错误
                \support\Log::error("定时任务注册失败 [ID: {$item->id}]: " . $e->getMessage());
            }
        }
    }
}