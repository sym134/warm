<?php

namespace warm\process;

use Exception;
use GuzzleHttp\Exception\GuzzleException;
use support\Log;
use warm\admin\model\system\SystemCrontab;
use warm\admin\service\system\SystemCrontabService;
use Workerman\Crontab\Crontab;

/**
 * 任务管理器类（简化版）
 *
 * 负责定时任务的管理和执行
 * 提供公共的刷新接口供外部主动触发
 * 使用 Workerman Crontab 的 destroy 方法正确注销任务
 *
 * @author Warm Team
 * @since 2.0
 */
class TaskManager
{
    /**
     * 单例实例
     *
     * @var self|null
     */
    private static ?self $instance = null;

    /**
     * 任务实例映射表
     * 存储 Crontab 实例的引用
     *
     * @var array
     */
    private array $taskInstances = [];

    /**
     * 任务配置缓存
     * 存储最新的任务配置信息
     *
     * @var array
     */
    private array $taskConfigs = [];

    /**
     * 构造函数
     * 私有化防止直接实例化
     */
    private function __construct()
    {
        $this->initialize();
    }

    /**
     * 获取单例实例
     *
     * @return self
     */
    public static function getInstance(): self
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * 初始化管理器
     *
     * @return void
     */
    private function initialize(): void
    {
        // 加载初始任务配置
        $this->loadAllTaskConfigs();

        // 注册所有任务
        $this->registerAllTasks();

        Log::info('TaskManager 初始化完成');
    }

    /**
     * 加载所有任务配置
     *
     * @return void
     */
    private function loadAllTaskConfigs(): void
    {
        try {
            $tasks = SystemCrontab::where('task_status', 1)
                ->get();

            foreach ($tasks as $task) {
                $this->taskConfigs[$task->id] = $task->toArray();
            }

            Log::info('加载任务配置完成，共 ' . count($this->taskConfigs) . ' 个启用任务');
        } catch (Exception $e) {
            Log::error('加载任务配置失败: ' . $e->getMessage());
        }
    }

    /**
     * 注册所有任务到 Crontab
     *
     * @return void
     */
    private function registerAllTasks(): void
    {
        foreach ($this->taskConfigs as $taskId => $config) {
            $this->registerTaskInstance($taskId, $config);
        }
    }

    /**
     * 注册单个任务实例
     *
     * @param int $taskId 任务ID
     * @param array $config 任务配置
     * @return void
     */
    private function registerTaskInstance(int $taskId, array $config): void
    {
        try {
            // 验证 Cron 表达式
            if (empty($config['rule'])) {
                Log::warning("任务Cron表达式为空 [ID: $taskId]");
                return;
            }

            // 创建 Crontab 实例
            $crontab = new Crontab($config['rule'], function () use ($taskId) {
                var_dump('执行任务: ' . $taskId);
                $this->executeTask($taskId);
            });

            // 保存实例引用
            $this->taskInstances[$taskId] = $crontab;

            Log::info("任务注册成功 [ID: {$taskId}, 标题: {$config['name']}]");

        } catch (Exception $e) {
            Log::error("任务注册失败 [ID: {$taskId}]: " . $e->getMessage());
        }
    }

    /**
     * 公共刷新方法 - 允许外部主动触发
     *
     * @param int|null $taskId 特定任务ID，null表示刷新所有任务
     * @return void
     */
    public function refreshTask(?int $taskId = null): void
    {
        if ($taskId !== null) {
            $this->refreshSingleTask($taskId);
        } else {
            $this->refreshAllTasks();
        }
    }

    /**
     * 刷新单个任务
     *
     * @param int $taskId 任务ID
     * @return void
     */
    private function refreshSingleTask(int $taskId): void
    {
        Log::info("开始刷新单个任务 [ID: {$taskId}]");

        // 注销旧任务
        $this->unregisterTaskInstance($taskId);

        // 重新注册任务
        $task = SystemCrontab::find($taskId);
        if ($task && $task->task_status == 1) {
            $this->registerTaskInstance($taskId, $task->toArray());
            Log::info("单个任务刷新完成 [ID: {$taskId}]");
        } else {
            Log::info("任务不存在或已禁用 [ID: {$taskId}]");
        }
    }

    /**
     * 刷新所有任务
     *
     * @return void
     */
    private function refreshAllTasks(): void
    {
        Log::info('开始刷新所有任务');

        // 注销所有现有任务
        foreach (array_keys($this->taskInstances) as $taskId) {
            $this->unregisterTaskInstance($taskId);
        }

        // 清空配置缓存
        $this->taskConfigs = [];

        // 重新加载和注册所有启用任务
        $this->loadAllTaskConfigs();
        $this->registerAllTasks();

        Log::info('所有任务刷新完成');
    }

    /**
     * 正确注销任务实例
     *
     * @param int $taskId 任务ID
     * @return void
     */
    private function unregisterTaskInstance(int $taskId): void
    {
        if (isset($this->taskInstances[$taskId])) {
            // 使用 Workerman Crontab 的 destroy 方法正确注销
            $this->taskInstances[$taskId]->destroy();
            unset($this->taskInstances[$taskId]);
            Log::info("任务实例已正确注销 [ID: {$taskId}]");
        }
    }

    /**
     * 执行任务
     *
     * @param int $taskId 任务ID
     * @return bool 执行结果
     * @throws GuzzleException
     */
    public function executeTask(int $taskId): bool
    {
        try {
            // 获取配置
            $taskConfig = $this->taskConfigs[$taskId] ?? [];
            Log::info("执行任务 [ID: {$taskId}, 标题: {$taskConfig['name']}]");

            $service = SystemCrontabService::make();
            return $service->run($taskId, $taskConfig);

        } catch (Exception $e) {
            Log::error("任务执行异常 [ID: {$taskId}]: " . $e->getMessage());
            return false;
        }
    }

    /**
     * 获取最新任务配置
     *
     * @param int $taskId 任务ID
     * @return array|null 任务配置
     */
    private function getLatestTaskConfig(int $taskId): ?array
    {
        try {
            $task = SystemCrontab::find($taskId);
            return $task?->toArray();
        } catch (Exception $e) {
            Log::error("获取任务配置失败 [ID: {$taskId}]: " . $e->getMessage());
            return null;
        }
    }

    /**
     * 获取任务统计信息
     *
     * @return array
     */
    public function getTaskStatistics(): array
    {
        return [
            'total_registered' => count($this->taskInstances),
            'total_configured' => count($this->taskConfigs),
            'task_list' => array_keys($this->taskInstances)
        ];
    }
}