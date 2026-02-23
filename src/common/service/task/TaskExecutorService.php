<?php

namespace warm\common\service\task;

use Exception;
use GuzzleHttp\Exception\GuzzleException;
use support\Log;
use warm\admin\model\system\SystemCrontab;
use Webman\RedisQueue\Redis;
use warm\common\service\BaseService;
use warm\admin\service\system\SystemCrontabLogService;

/**
 * 任务执行服务类
 *
 * 提供任务执行的核心功能，包括同步、异步、协程等多种执行方式
 *
 * @author sym
 * @date 2024/7/2
 */
class TaskExecutorService extends BaseService
{
    /**
     * HTTP任务服务
     *
     * @var HttpTaskService
     */
    protected HttpTaskService $httpTaskService;

    /**
     * 类任务服务
     *
     * @var ClassTaskService
     */
    protected ClassTaskService $classTaskService;

    /**
     * 监控服务
     *
     * @var TaskMonitorService
     */
    protected TaskMonitorService $monitorService;

    /**
     * 构造函数
     */
    public function __construct()
    {
        $this->httpTaskService = HttpTaskService::make();
        $this->classTaskService = ClassTaskService::make();
        $this->monitorService = TaskMonitorService::make();
    }

    /**
     * 运行任务
     *
     * @param int $id 任务ID
     * @param array|null $task 任务模型实例
     * @param bool $forceQueue 是否强制使用队列执行
     * @return bool 是否运行成功（异步执行时立即返回 true）
     * @throws GuzzleException
     */
    public function run(int $id, ?array $task = null, bool $forceQueue = false): bool
    {
        // 检查是否启用异步执行
        if ($forceQueue || config('crontab.enable_queue', false)) {
            return $this->runQueue($id, $task);
        }

        // 同步执行
        return $this->runSync($id, $task);
    }

    /**
     * 使用队列异步执行任务
     *
     * @param int $id 任务ID
     * @return bool 是否成功提交
     */
    private function runQueue(int $id, array $task): bool
    {
        $queueName = config('crontab.queue_name', 'crontab_tasks');

        try {
            // 检查是否有 Webman Redis Queue 服务
            if (class_exists('\Webman\RedisQueue\Redis')) {
                // 投递消息
                Redis::send($queueName, [
                    'task_id' => $id,
                    'type' => 'crontab',
                    'created_at' => time(),
                    'task' => $task
                ]);
                return true;
            }

            Log::warning("Webman Redis Queue 未安装 (composer require webman/redis-queue)， [任务ID: $id]");
            return false;
        } catch (Exception $e) {
            Log::error("提交队列任务失败 [任务ID: $id]: " . $e->getMessage());
            return false;
        }
    }

    /**
     * 同步执行任务
     *
     * @param int $id 任务ID
     * @param array|null $task 任务模型实例
     * @return bool 是否运行成功
     * @throws GuzzleException
     */
    public function runSync(int $id, ?array $task = null): bool
    {
        $startTime = microtime(true);

        // 获取任务信息
        if ($task === null) {
            $task = SystemCrontab::find($id);
            // 检查任务是否存在
            if (!$task) {
                return false;
            }
            $task = $task->toArray();
        }

        // 检查任务状态是否为启用状态
        if ($task['task_status'] !== 1) {
            return false;
        }

        // 初始化日志数据
        $logData = [
            'crontab_id' => $task['id'],
            'target' => $task['target'],
            'parameter' => $task['parameter'],
            'exception_info' => [],
            'execution_status' => 2 // 默认失败状态
        ];

        try {
            $result = $this->executeTaskByType($task, $logData);

            // 记录执行时间
            $executionTime = round((microtime(true) - $startTime) * 1000, 2); // 转换为毫秒
            if (isset($logData['exception_info']) && is_array($logData['exception_info'])) {
                $logData['exception_info']['execution_time_ms'] = $executionTime;
            } elseif (is_string($logData['exception_info'])) {
                $logData['exception_info'] = [
                    'message' => $logData['exception_info'],
                    'execution_time_ms' => $executionTime
                ];
            }

            // 监控和告警
            if (config('crontab.enable_monitor', false)) {
                $this->monitorService->monitorTaskExecution($task, $result, $executionTime, $logData);
            }

            return $result;
        } catch (Exception $e) {
            // 记录完整的异常信息，包括堆栈跟踪
            $executionTime = round((microtime(true) - $startTime) * 1000, 2);
            $logData['exception_info'] = [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString(),
                'execution_time_ms' => $executionTime
            ];

            SystemCrontabLogService::make()->store($logData);

            // 监控和告警
            if (config('crontab.enable_monitor', false)) {
                $this->monitorService->monitorTaskExecution($task, false, $executionTime, $logData);
            }

            return false;
        }
    }

    /**
     * 根据任务类型执行任务
     *
     * @param array $task 任务信息
     * @param array $logData 日志数据
     * @return bool 是否执行成功
     * @throws Exception
     */
    private function executeTaskByType(array $task, array &$logData): bool
    {
        switch ($task['task_type']) {
            case 1:
                // URL任务GET
                return $this->httpTaskService->executeHttpGetTask($task, $logData);

            case 2:
                // URL任务POST
                return $this->httpTaskService->executeHttpPostTask($task, $logData);

            case 3:
                // 类任务
                return $this->classTaskService->executeClassTask($task, $logData);

            default:
                $logData['exception_info']['message'] = '未知的任务类型: ' . $task['task_type'];
                SystemCrontabLogService::make()->store($logData);
                return false;
        }
    }
}