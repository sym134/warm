<?php

namespace warm\process;

use support\Log;

/**
 * 定时任务处理类（简化版）
 * 
 * 处理系统定时任务的初始化和执行
 * 提供外部调用接口用于主动刷新任务
 * 
 * @author Warm Team
 * @since 2.0
 */
class CrontabTask
{
    /**
     * 任务管理器实例
     * 
     * @var TaskManager
     */
    private TaskManager $taskManager;

    /**
     * Worker启动时的回调方法
     * 
     * 在Worker启动时初始化任务管理器
     *
     * @return void
     */
    public function onWorkerStart(): void
    {
        // 初始化任务管理器
        $this->taskManager = TaskManager::getInstance();

        Log::info('CrontabTask 初始化完成');
    }

    /**
     * 公共接口 - 允许外部主动触发任务刷新
     * 
     * @param int|null $taskId 特定任务ID，null表示刷新所有任务
     * @return void
     */
    public static function refreshTasks(?int $taskId = null): void
    {
        $taskManager = TaskManager::getInstance();
        $taskManager->refreshTask($taskId);
    }

    /**
     * 获取任务管理器实例
     *
     * @return TaskManager
     */
    public function getTaskManager(): TaskManager
    {
        return $this->taskManager;
    }

    /**
     * 获取任务统计信息
     * 
     * @return array
     */
    public function getTaskStatistics(): array
    {
        if (isset($this->taskManager)) {
            return $this->taskManager->getTaskStatistics();
        }
        return [
            'total_registered' => 0,
            'total_configured' => 0,
            'task_list' => []
        ];
    }
}
