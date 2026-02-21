<?php

namespace warm\admin\service\system;

use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use support\Log;
use Webman\RedisQueue\Redis;
use Workerman\Coroutine;
use Workerman\Http\Client as WorkermanHttpClient;
use Illuminate\Database\Eloquent\Builder;
use warm\admin\model\system\SystemCrontab;
use warm\admin\service\AdminService;

/**
 * 定时任务服务类
 *
 * 提供定时任务管理功能，包括任务存储、更新、执行等
 *
 * @method SystemCrontab getModel() 获取模型实例
 * @method SystemCrontab|\Illuminate\Database\Query\Builder query() 获取查询构造器
 */
class SystemCrontabService extends AdminService
{
    /**
     * 模型类名
     *
     * @var string
     */
    protected string $modelName = SystemCrontab::class;

    /**
     * 存储任务
     *
     * @param array $data 存储的数据
     * @return bool 是否存储成功
     * @throws Exception
     */
    public function store(array $data): bool
    {
        $data = $this->getArr($data);
        unset($data['parameter']['']);
        return parent::store($data);
    }

    /**
     * 更新任务
     *
     * @param mixed $primaryKey 主键值
     * @param array $data 更新的数据
     * @return bool 是否更新成功
     * @throws Exception
     */
    public function update(mixed $primaryKey, array $data): bool
    {
        $data = $this->getArr($data);
        unset($data['parameter']['']);
        return parent::update($primaryKey, $data);
    }

    /**
     * 列表查询处理
     *
     * @return Builder 查询构造器
     */
    public function listQuery(): Builder
    {
        return parent::listQuery();
    }

    /**
     * 验证任务
     *
     * @param string $task_type 任务类型
     * @param string $target 任务目标
     * @return void
     * @throws Exception Author:sym
     * Date:2024/7/2 下午3:28
     * Company:极智科技
     */
    private function validateTask(string $task_type, string $target): void
    {
        if ((int)$task_type === 3) {
            if (!str_contains($target, ':')) {
                throw new Exception('类任务格式错误');
            }
            [$class, $fun] = explode(':', $target);
            if (!class_exists($class)) {
                throw new Exception('类任务不存在:' . $class);
            }
            if (!method_exists($class, $fun)) {
                throw new Exception('类任务:' . $class . ',方法:' . $fun . ',未找到');
            }
        }
    }

    /**
     * 生成Crontab表达式
     *
     * @param string $executionPeriod 执行周期
     * @param string $second 秒
     * @param string $minute 分钟
     * @param string $hour 小时
     * @param string $dayOfMonth 日期
     * @param string $month 月份
     * @param string $dayOfWeek 星期
     * @return string Crontab表达式
     *
     * Author:sym
     * Date:2024/7/4 下午6:34
     * Company:极智科技
     */
    private function generateCrontabExpression($executionPeriod, $second = '*', $minute = '*', $hour = '*', $dayOfMonth = '*', $month = '*', $dayOfWeek = '*'): string
    {
        // 设置默认值
        $second = ($second !== null && $second !== '') ? $second : '*';
        $minute = ($minute !== null && $minute !== '') ? $minute : '*';
        $hour = ($hour !== null && $hour !== '') ? $hour : '*';
        $dayOfMonth = ($dayOfMonth !== null && $dayOfMonth !== '') ? $dayOfMonth : '*';
        $dayOfWeek = ($dayOfWeek !== null && $dayOfWeek !== '') ? $dayOfWeek : '*';

        switch ($executionPeriod) {
            case 'day':
                // 每天执行
                $minute = ($minute !== '*') ? $minute : '0';
                $hour = ($hour !== '*') ? $hour : '0';
                $dayOfMonth = '*';
                $month = '*';
                $dayOfWeek = '*';
                break;
            case 'day-n':
                // 每 N 天执行
                $minute = ($minute !== '*') ? $minute : '0';
                $hour = ($hour !== '*') ? $hour : '0';
                $dayOfMonth = "*/$dayOfMonth";
                $month = '*';
                $dayOfWeek = '*';
                break;
            case 'hour':
                // 每小时执行
                $minute = ($minute !== '*') ? $minute : '0';
                $hour = '*';
                $dayOfMonth = '*';
                $month = '*';
                $dayOfWeek = '*';
                break;
            case 'hour-n':
                // 每 N 小时执行
                $minute = ($minute !== '*') ? $minute : '0';
                $hour = "*/$hour";
                $dayOfMonth = '*';
                $month = '*';
                $dayOfWeek = '*';
                break;
            case 'minute-n':
                // 每 N 分钟执行
                $minute = "*/$minute";
                $hour = '*';
                $dayOfMonth = '*';
                $month = '*';
                $dayOfWeek = '*';
                break;
            case 'week':
                // 每周执行
                $minute = ($minute !== '*') ? $minute : '0';
                $hour = ($hour !== '*') ? $hour : '0';
                $dayOfMonth = '*';
                $month = '*';
                $dayOfWeek = ($dayOfWeek !== '*') ? $dayOfWeek : '0';
                break;
            case 'month':
                // 每月执行
                $minute = ($minute !== '*') ? $minute : '0';
                $hour = ($hour !== '*') ? $hour : '0';
                $dayOfMonth = ($dayOfMonth !== '*') ? $dayOfMonth : '1';
                $month = '*';
                $dayOfWeek = '*';
                break;
            case 'second-n':
                // 修复：当 second 为 '*' 时，应该使用默认值而不是 '0'
                if ($second === '*' || $second === '' || $second === null) {
                    $second = '*/1'; // 默认每秒执行
                } else {
                    $second = '*/' . $second;
                }
                $minute = "*";
                $hour = '*';
                $dayOfMonth = '*';
                $month = '*';
                $dayOfWeek = '*';
                break;
            default:
                return "Invalid execution period.";
        }

        // 组合成 crontab 表达式
        return "$second $minute $hour $dayOfMonth $month $dayOfWeek";
    }

    /**
     * crontab表达式到文本
     *
     * @param string $executionPeriod 执行周期
     * @param string $expression 表达式
     * @return string 文本描述
     *
     * Author:sym
     * Date:2024/7/4 下午6:34
     * Company:极智科技
     */
    public function crontabExpressionToText(string $executionPeriod, string $expression): string
    {
        $parts = explode(' ', $expression);

        if (count($parts) != 6) {
            return "Invalid crontab expression.";
        }

        [$second, $minute, $hour, $dayOfMonth, $month, $dayOfWeek] = $parts;

        // 定义一个用于返回的文本数组
        $text = [];

        // 处理不同的执行周期
        switch ($executionPeriod) {
            case 'second-n':
                return $this->convertPeriod($second, '秒', 'second-n');
            case 'minute-n':
                return $this->convertPeriod($minute, '分钟', 'minute-n');
            case 'hour-n':
                return $this->convertPeriod($hour, '小时', 'hour-n');
            case 'day-n':
                return $this->convertPeriod($dayOfMonth, '天', 'day-n');
            case 'day':
                $text[] = "每天";
                break;
            case 'hour':
                $text[] = "每小时";
                break;
            case 'week':
                $text[] = "每周";
                break;
            case 'month':
                $text[] = "每月";
                break;
            default:
                return "Invalid execution period.";
        }

        // 处理周几
        if ($dayOfWeek !== '*') {
            $days = ['日', '一', '二', '三', '四', '五', '六'];
            $text[] = "周" . $days[$dayOfWeek];
        }

        // 处理每月的哪一天
        if ($dayOfMonth !== '*' && $executionPeriod !== 'day-n') {
            $text[] = "每月" . $dayOfMonth . "日";
        }

        // 处理月份
        if ($month !== '*') {
            $text[] = $month . "月";
        }

        // 处理小时和分钟
        if ($hour !== '*') {
            $text[] = sprintf("%02d", $hour) . "时";
        }

        if ($minute !== '*') {
            $text[] = sprintf("%02d", $minute) . "分";
        }

        // 处理秒
        if ($second !== '*' && $executionPeriod !== 'second-n') {
            $text[] .= sprintf("%02d", $second) . "秒";
        }

        // 生成最终的文本描述
        $finalText = implode(' ', array_filter($text));

        // 优化输出
        if ($executionPeriod == 'hour' && str_contains($finalText, "00时")) {
            $finalText = str_replace("00时", "", $finalText);
            $finalText = "每小时第 " . trim($finalText) . " 执行一次";
        } else {
            $finalText = $finalText . " 执行一次";
        }

        return $finalText;
    }

    /**
     * 辅助函数，用于处理 'n' 周期
     *
     * @param string $part 部分表达式
     * @param string $unit 单位
     * @param string $periodType 周期类型
     * @return string 文本描述
     *
     * Author:sym
     * Date:2024/7/4 下午6:33
     * Company:极智科技
     */
    private function convertPeriod($part, $unit, $periodType): string
    {
        if (preg_match('/^\*\/(\d+)$/', $part, $matches)) {
            return "每隔 " . $matches[1] . " " . $unit . "执行一次";
        } else {
            return "Invalid expression for " . $periodType . ".";
        }
    }

    /**
     * 运行任务
     *
     * @param int $id 任务ID
     * @param bool $forceSync 是否强制同步执行（忽略异步配置）
     * @return bool 是否运行成功（异步执行时立即返回 true）
     *
     * Author:sym
     * Date:2024/7/2 下午3:29
     * Company:极智科技
     * @throws GuzzleException
     */
    public function run(int $id, bool $forceSync = false): bool
    {
        // 检查是否启用异步执行
        if (!$forceSync && config('crontab.enable_async', false)) {
            return $this->runAsync($id);
        }
        
        // 同步执行
        return $this->runSync($id);
    }

    /**
     * 异步执行任务
     *
     * @param int $id 任务ID
     * @return bool 是否成功提交异步任务
     * @throws GuzzleException
     */
    private function runAsync(int $id): bool
    {
        $asyncMethod = config('crontab.async_method', 'coroutine');
        
        switch ($asyncMethod) {
            case 'coroutine':
                return $this->runAsyncWithCoroutine($id);
            case 'queue':
                return $this->runAsyncWithQueue($id);
            default:
                Log::warning("未知的异步执行方式: {$asyncMethod}");
        }
    }

    /**
     * 使用协程异步执行任务
     *
     * @param int $id 任务ID
     * @return bool 是否成功提交
     * @throws GuzzleException
     */
    private function runAsyncWithCoroutine(int $id): bool
    {
        // 检查是否在协程环境中
        // 只有当前已经在协程环境中运行，才能安全地创建子协程实现异步
        // 如果在同步模式下，创建协程也无法实现真正的异步非阻塞（除非使用 Coroutine\run 但那会阻塞当前进程直到协程结束）
        if (!isCoroutineEnabled()) {
            Log::warning("当前未运行在协程环境，无法使用协程异步执行，降级到同步执行 [任务ID: {$id}]");
            return $this->runSync($id);
        }

        try {
            // 使用协程异步执行
            Coroutine::create(function () use ($id) {
                try {
                    $this->runSync($id);
                } catch (Exception $e) {
                    Log::error("异步任务执行失败 [任务ID: {$id}]: " . $e->getMessage());
                }
            });
            
            return true;
        } catch (Exception $e) {
            Log::error("提交异步任务失败 [任务ID: {$id}]: " . $e->getMessage());
        }
    }

    /**
     * 使用队列异步执行任务
     *
     * @param int $id 任务ID
     * @return bool 是否成功提交
     */
    private function runAsyncWithQueue(int $id): bool
    {
        $queueName = config('crontab.queue_name', 'crontab_tasks');
        
        try {
            // 检查是否有 Webman Redis Queue 服务
            // 需安装 webman/redis-queue: composer require webman/redis-queue
            // 文档: https://www.workerman.net/doc/webman/queue/redis.html
            if (class_exists('\Webman\RedisQueue\Redis')) {
                // 投递消息
                Redis::send($queueName, [
                    'task_id' => $id,
                    'type' => 'crontab',
                    'created_at' => time(),
                ]);
                return true;
            }
            
            Log::warning("Webman Redis Queue 未安装 (composer require webman/redis-queue)， [任务ID: {$id}]");
        } catch (Exception $e) {
            Log::error("提交队列任务失败 [任务ID: {$id}]: " . $e->getMessage());
        }
    }

    /**
     * 同步执行任务（原有逻辑）
     *
     * @param int $id 任务ID
     * @return bool 是否运行成功
     * @throws GuzzleException
     */
    private function runSync(int $id): bool
    {
        $startTime = microtime(true);
        
        // 获取任务信息
        $info = $this->getModel()->find($id);
        
        // 检查任务是否存在
        if (!$info) {
            return false;
        }
        
        // 检查任务状态是否为启用状态
        if ($info->task_status !== 1) {
            return false;
        }
        
        // 并发控制检查
        if (config('crontab.enable_concurrent_control', false)) {
            if (!$this->acquireLock($id)) {
                return false; // 任务正在执行中，跳过本次执行
            }
        }
        
        // 初始化日志数据
        $logData = [
            'crontab_id' => $info->id,
            'target' => $info->target,
            'parameter' => $info->parameter,
            'exception_info' => [],
            'execution_status' => 2 // 默认失败状态
        ];

        try {
            switch ($info->task_type) {
                case 1:
                    // URL任务GET
                    $result = $this->executeHttpGetTask($info, $logData);
                    break;
                    
                case 2:
                    // URL任务POST
                    $result = $this->executeHttpPostTask($info, $logData);
                    break;
                    
                case 3:
                    // 类任务
                    $result = $this->executeClassTask($info, $logData);
                    break;
                    
                default:
                    $logData['exception_info']['message'] = '未知的任务类型: ' . $info->task_type;
                    SystemCrontabLogService::make()->store($logData);
                    $result = false;
            }
            
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
                $this->monitorTaskExecution($info, $result, $executionTime, $logData);
            }
            
            // 如果任务失败且启用了重试，安排重试
            if (!$result && config('crontab.enable_retry', false)) {
                $this->scheduleRetry($id, $info);
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
                $this->monitorTaskExecution($info, false, $executionTime, $logData);
            }
            
            // 如果任务失败且启用了重试，安排重试
            if (config('crontab.enable_retry', false)) {
                $this->scheduleRetry($id, $info);
            }
            
            return false;
        } finally {
            // 释放锁
            if (config('crontab.enable_concurrent_control', false)) {
                $this->releaseLock($id);
            }
        }
    }
    
    /**
     * 获取任务执行锁
     *
     * @param int $id 任务ID
     * @return bool 是否成功获取锁
     */
    private function acquireLock(int $id): bool
    {
        $lockKey = 'crontab_lock_' . $id;
        $lockExpire = config('crontab.lock_expire', 3600);
        
        // 尝试使用 Redis（如果可用）
        try {
            if (class_exists('\Illuminate\Support\Facades\Redis')) {
                $redis = \Illuminate\Support\Facades\Redis::connection();
                $result = $redis->set($lockKey, time(), 'EX', $lockExpire, 'NX');
                if ($result) {
                    return true;
                }
            }
        } catch (Exception $e) {
            // Redis 不可用时，使用文件锁
        }
        
        // 使用文件锁作为后备方案
        return $this->acquireFileLock($id, $lockExpire);
    }
    
    /**
     * 获取文件锁
     *
     * @param int $id 任务ID
     * @param int $expire 过期时间（秒）
     * @return bool 是否成功获取锁
     */
    private function acquireFileLock(int $id, int $expire): bool
    {
        $lockFile = runtime_path() . '/crontab_locks/' . $id . '.lock';
        $lockDir = dirname($lockFile);
        
        if (!is_dir($lockDir)) {
            mkdir($lockDir, 0755, true);
        }
        
        // 检查锁文件是否存在且未过期
        if (file_exists($lockFile)) {
            $lockTime = filemtime($lockFile);
            if (time() - $lockTime < $expire) {
                return false; // 锁仍有效
            }
            // 锁已过期，删除旧锁文件
            @unlink($lockFile);
        }
        
        // 创建锁文件
        return touch($lockFile);
    }
    
    /**
     * 释放任务执行锁
     *
     * @param int $id 任务ID
     * @return void
     */
    private function releaseLock(int $id): void
    {
        $lockKey = 'crontab_lock_' . $id;
        
        // 尝试使用 Redis（如果可用）
        try {
            if (class_exists('\Illuminate\Support\Facades\Redis')) {
                $redis = \Illuminate\Support\Facades\Redis::connection();
                $redis->del($lockKey);
                return;
            }
        } catch (Exception $e) {
            // Redis 不可用时，删除文件锁
        }
        
        // 使用文件锁作为后备方案
        $this->releaseFileLock($id);
    }
    
    /**
     * 释放文件锁
     *
     * @param int $id 任务ID
     * @return void
     */
    private function releaseFileLock(int $id): void
    {
        $lockFile = runtime_path() . '/crontab_locks/' . $id . '.lock';
        if (file_exists($lockFile)) {
            @unlink($lockFile);
        }
    }
    
    /**
     * 执行HTTP GET任务
     *
     * @param SystemCrontab $info 任务信息
     * @param array $logData 日志数据
     * @return bool 是否执行成功
     */
    private function executeHttpGetTask(SystemCrontab $info, array &$logData): bool
    {
        // 优先使用 Workerman\Http\Client (支持协程非阻塞)
        if (isCoroutineEnabled()) {
            try {
                $options = [
                    'timeout' => config('crontab.http_timeout', 30),
                    'ssl' => [
                        'verify_peer' => config('crontab.verify_ssl', true),
                        'verify_peer_name' => config('crontab.verify_ssl', true),
                    ],
                ];
                $client = new WorkermanHttpClient($options);
                $url = $info->target;
                if (!empty($info->parameter)) {
                    $url .= (!str_contains($url, '?') ? '?' : '&') . http_build_query($info->parameter);
                }
                $response = $client->get($url);
                var_dump($response);
                $logData['execution_status'] = $response->getStatusCode() === 200 ? 1 : 2;
                SystemCrontabLogService::make()->store($logData);
                return $logData['execution_status'] === 1;
            } catch (\Throwable $e) {
                $logData['exception_info'] = [
                    'message' => 'Workerman HTTP Client Error: ' . $e->getMessage(),
                    'file' => $e->getFile(),
                    'line' => $e->getLine(),
                ];
                SystemCrontabLogService::make()->store($logData);
                return false;
            }
        }

        $httpClient = new Client([
            'timeout' => config('crontab.http_timeout', 30),
            'verify' => config('crontab.verify_ssl', true),
        ]);
        
        try {
            // GET 请求使用 query 参数，而不是 form_params
            $response = $httpClient->request('GET', $info->target, [
                'query' => $info->parameter ?? [],
            ]);

            $logData['execution_status'] = $response->getStatusCode() === 200 ? 1 : 2;
            SystemCrontabLogService::make()->store($logData);
            return $logData['execution_status'] === 1;
        } catch (GuzzleException $e) {
            // 记录完整的异常信息
            $logData['exception_info'] = [
                'message' => $e->getMessage(),
                'code' => $e->getCode(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ];
            SystemCrontabLogService::make()->store($logData);
            return false;
        }
    }
    
    /**
     * 执行HTTP POST任务
     *
     * @param SystemCrontab $info 任务信息
     * @param array $logData 日志数据
     * @return bool 是否执行成功
     */
    private function executeHttpPostTask(SystemCrontab $info, array &$logData): bool
    {
        // 优先使用 Workerman\Http\Client (支持协程非阻塞)
        // 必须确保当前处于协程环境中，否则 WorkermanHttpClient 无法正常工作
        if (isCoroutineEnabled()) {
            try {
                $options = [
                    'timeout' => config('crontab.http_timeout', 30),
                    'ssl' => [
                        'verify_peer' => config('crontab.verify_ssl', true),
                        'verify_peer_name' => config('crontab.verify_ssl', true),
                    ],
                ];
                $client = new WorkermanHttpClient($options);
                $data = $info->parameter;
                // 如果是 JSON 字符串，需要设置 Header
                $headers = [];
                if (is_string($data) && (str_starts_with(trim($data), '{') || str_starts_with(trim($data), '['))) {
                     $headers = ['Content-Type' => 'application/json'];
                }
                
                // Workerman Http Client 的 post 方法第二个参数是 data
                $response = $client->post($info->target, $data, $headers);
                
                $logData['execution_status'] = $response->getStatusCode() === 200 ? 1 : 2;
                SystemCrontabLogService::make()->store($logData);
                return $logData['execution_status'] === 1;
            } catch (\Throwable $e) {
                $logData['exception_info'] = [
                    'message' => 'Workerman HTTP Client Error: ' . $e->getMessage(),
                    'file' => $e->getFile(),
                    'line' => $e->getLine(),
                ];
                SystemCrontabLogService::make()->store($logData);
                return false;
            }
        }

        $httpClient = new Client([
            'timeout' => config('crontab.http_timeout', 30),
            'verify' => config('crontab.verify_ssl', true),
        ]);
        
        try {
            // POST 请求根据参数类型选择 form_params 或 json
            $options = [];
            if (!empty($info->parameter)) {
                // 如果参数是数组，使用 form_params；如果是 JSON 字符串，使用 json
                if (is_array($info->parameter)) {
                    $options['form_params'] = $info->parameter;
                } else {
                    $options['json'] = $info->parameter;
                }
            }
            
            $response = $httpClient->request('POST', $info->target, $options);
            
            $logData['execution_status'] = $response->getStatusCode() === 200 ? 1 : 2;
            SystemCrontabLogService::make()->store($logData);
            return $logData['execution_status'] === 1;
        } catch (GuzzleException $e) {
            // 记录完整的异常信息
            $logData['exception_info'] = [
                'message' => $e->getMessage(),
                'code' => $e->getCode(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ];
            SystemCrontabLogService::make()->store($logData);
            return false;
        }
    }
    
    /**
     * 执行类任务
     *
     * @param SystemCrontab $info 任务信息
     * @param array $logData 日志数据
     * @return bool 是否执行成功
     */
    private function executeClassTask(SystemCrontab $info, array &$logData): bool
    {
        if (!str_contains($info->target, ':')) {
            $logData['exception_info'] = '类任务格式错误';
            SystemCrontabLogService::make()->store($logData);
            return false;
        }
        
        [$className, $methodName] = explode(':', $info->target, 2);
        
        if (!class_exists($className)) {
            $logData['exception_info']['message'] = '类任务不存在:' . $className;
            SystemCrontabLogService::make()->store($logData);
            return false;
        }
        
        if (!method_exists($className, $methodName)) {
            $logData['exception_info']['message'] = '类任务:' . $className . ',方法:' . $methodName . ',未找到';
            SystemCrontabLogService::make()->store($logData);
            return false;
        }
        
        try {
            $class = new $className;
            
            // 根据方法签名决定如何传递参数
            $reflection = new \ReflectionMethod($className, $methodName);
            $parameters = $reflection->getParameters();
            
            if (empty($parameters)) {
                // 方法无参数
                $result = $class->$methodName();
            } elseif (count($parameters) === 1 && $parameters[0]->isArray()) {
                // 方法接受数组参数
                $result = $class->$methodName($info->parameter ?? []);
            } else {
                // 其他情况，直接传递参数
                $result = $class->$methodName($info->parameter);
            }
            
            $logData['execution_status'] = 1;
            $logData['exception_info'] = is_string($result) ? $result : json_encode($result, JSON_UNESCAPED_UNICODE);
            SystemCrontabLogService::make()->store($logData);
            return true;
        } catch (Exception $e) {
            // 记录完整的异常信息
            $logData['exception_info'] = [
                'message' => '执行类任务时发生错误: ' . $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString(),
            ];
            SystemCrontabLogService::make()->store($logData);
            return false;
        }
    }

    /**
     * 处理任务数据数组
     *
     * @param array $data 任务数据
     * @return array 处理后的数据
     * @throws Exception
     */
    public function getArr(array $data): array
    {
        // 验证必填字段
        $requiredFields = ['execution_cycle', 'task_type', 'target'];
        foreach ($requiredFields as $field) {
            if (!isset($data[$field]) || $data[$field] === '') {
                throw new Exception("字段 {$field} 是必填的");
            }
        }
        
        // 验证任务类型
        if (!in_array((int)$data['task_type'], [1, 2, 3])) {
            throw new Exception('无效的任务类型: ' . $data['task_type']);
        }
        
        // 验证执行周期
        $validPeriods = ['day', 'day-n', 'hour', 'hour-n', 'minute-n', 'week', 'month', 'second-n'];
        if (!in_array($data['execution_cycle'], $validPeriods)) {
            throw new Exception('无效的执行周期: ' . $data['execution_cycle']);
        }
        
        // 验证 URL 任务的目标格式
        if (in_array((int)$data['task_type'], [1, 2])) {
            if (!filter_var($data['target'], FILTER_VALIDATE_URL)) {
                throw new Exception('URL 任务的目标必须是有效的 URL 地址');
            }
        }
        
        // 生成 Cron 表达式
        $data['rule'] = $this->generateCrontabExpression(
            $data['execution_cycle'],
            $data['second'] ?? '*',
            $data['minute'] ?? '*',
            $data['hour'] ?? '*',
            $data['day'] ?? '*',
            '*',
            $data['week'] ?? '*'
        );
        
        // 设置创建者（如果存在 request 对象）
        if (isset($this->request) && isset($this->request->user) && isset($this->request->user->id)) {
            $data['created_by'] = $this->request->user->id;
        }
        
        // 验证任务
        $this->validateTask($data['task_type'], $data['target']);
        
        return $data;
    }

    /**
     * 监控任务执行
     *
     * @param SystemCrontab $task 任务信息
     * @param bool $success 是否执行成功
     * @param float $executionTime 执行时间（毫秒）
     * @param array $logData 日志数据
     * @return void
     * @throws GuzzleException
     */
    private function monitorTaskExecution(SystemCrontab $task, bool $success, float $executionTime, array $logData): void
    {
        // 检查失败告警
        if (!$success) {
            $failureCount = $this->getRecentFailureCount($task->id);
            if ($failureCount >= config('crontab.failure_alert_threshold', 3)) {
                $this->sendAlert($task, 'failure', [
                    'failure_count' => $failureCount,
                    'last_error' => $logData['exception_info'] ?? '未知错误',
                ]);
            }
        }

        // 检查超时告警
        $timeoutThreshold = config('crontab.timeout_alert_threshold_ms', 60000);
        if ($executionTime > $timeoutThreshold) {
            $this->sendAlert($task, 'timeout', [
                'execution_time_ms' => $executionTime,
                'threshold_ms' => $timeoutThreshold,
            ]);
        }
    }

    /**
     * 获取最近失败次数
     *
     * @param int $taskId 任务ID
     * @param int $minutes 统计时间范围（分钟）
     * @return int 失败次数
     */
    private function getRecentFailureCount(int $taskId, int $minutes = 60): int
    {
        $logService = SystemCrontabLogService::make();
        $startTime = date('Y-m-d H:i:s', time() - $minutes * 60);
        
        return $logService->getModel()
            ->where('crontab_id', $taskId)
            ->where('execution_status', 2) // 失败状态
            ->where('created_at', '>=', $startTime)
            ->count();
    }

    /**
     * 发送告警
     *
     * @param SystemCrontab $task 任务信息
     * @param string $alertType 告警类型：failure, timeout
     * @param array $data 告警数据
     * @return void
     * @throws GuzzleException
     */
    private function sendAlert(SystemCrontab $task, string $alertType, array $data = []): void
    {
        $channels = config('crontab.alert_channels', '');
        $receivers = config('crontab.alert_receivers', '');
        
        if (empty($channels) || empty($receivers)) {
            return; // 未配置告警渠道或接收人
        }

        $channels = array_map('trim', explode(',', $channels));
        $receivers = array_map('trim', explode(',', $receivers));

        // 构建告警消息
        $message = $this->buildAlertMessage($task, $alertType, $data);
        $title = $this->buildAlertTitle($task, $alertType);

        // 发送告警到各个渠道
        foreach ($channels as $channel) {
            try {
                $this->sendAlertToChannel($channel, $receivers, $title, $message, $task, $alertType);
            } catch (Exception $e) {
                // 记录告警发送失败，但不影响主流程
                Log::error("定时任务告警发送失败 [任务ID: {$task->id}, 渠道: {$channel}]: " . $e->getMessage());
            }
        }
    }

    /**
     * 构建告警标题
     *
     * @param SystemCrontab $task 任务信息
     * @param string $alertType 告警类型
     * @return string
     */
    private function buildAlertTitle(SystemCrontab $task, string $alertType): string
    {
        $typeMap = [
            'failure' => '任务执行失败',
            'timeout' => '任务执行超时',
        ];
        
        return sprintf(
            '[定时任务告警] %s - %s',
            $typeMap[$alertType] ?? '未知告警',
            $task->name ?? "任务 #{$task->id}"
        );
    }

    /**
     * 构建告警消息
     *
     * @param SystemCrontab $task 任务信息
     * @param string $alertType 告警类型
     * @param array $data 告警数据
     * @return string
     */
    private function buildAlertMessage(SystemCrontab $task, string $alertType, array $data): string
    {
        $message = "任务名称：{$task->name}\n";
        $message .= "任务ID：{$task->id}\n";
        $message .= "任务类型：" . (SystemCrontab::TASK_TYPE[$task->task_type] ?? '未知') . "\n";
        $message .= "任务目标：{$task->target}\n";
        $message .= "告警时间：" . date('Y-m-d H:i:s') . "\n\n";

        if ($alertType === 'failure') {
            $message .= "连续失败次数：{$data['failure_count']}\n";
            if (isset($data['last_error'])) {
                $error = is_array($data['last_error']) 
                    ? ($data['last_error']['message'] ?? json_encode($data['last_error'], JSON_UNESCAPED_UNICODE))
                    : $data['last_error'];
                $message .= "最后错误：{$error}\n";
            }
        } elseif ($alertType === 'timeout') {
            $message .= "执行时间：{$data['execution_time_ms']} 毫秒\n";
            $message .= "超时阈值：{$data['threshold_ms']} 毫秒\n";
        }

        return $message;
    }

    /**
     * 发送告警到指定渠道
     *
     * @param string $channel 告警渠道
     * @param array $receivers 接收人列表
     * @param string $title 告警标题
     * @param string $message 告警消息
     * @param SystemCrontab $task 任务信息
     * @param string $alertType 告警类型
     * @return void
     * @throws GuzzleException
     */
    private function sendAlertToChannel(string $channel, array $receivers, string $title, string $message, SystemCrontab $task, string $alertType): void
    {
        switch (strtolower($channel)) {
            case 'email':
                $this->sendEmailAlert($receivers, $title, $message);
                break;
            case 'sms':
                $this->sendSmsAlert($receivers, $message);
                break;
            case 'wechat':
                $this->sendWechatAlert($receivers, $title, $message);
                break;
            case 'webhook':
                $this->sendWebhookAlert($receivers, $title, $message, $task, $alertType);
                break;
            default:
                Log::warning("未知的告警渠道: {$channel}");
        }
    }

    /**
     * 发送邮件告警
     *
     * @param array $receivers 接收人列表
     * @param string $title 标题
     * @param string $message 消息
     * @return void
     */
    private function sendEmailAlert(array $receivers, string $title, string $message): void
    {
        // 如果系统有邮件服务，使用邮件服务发送
        // 这里使用日志记录，实际项目中应该调用邮件服务
        foreach ($receivers as $receiver) {
            Log::info("邮件告警 [收件人: {$receiver}]: {$title}\n{$message}");
            // TODO: 集成实际的邮件发送服务
            // Mail::to($receiver)->send(new CrontabAlertMail($title, $message));
        }
    }

    /**
     * 发送短信告警
     *
     * @param array $receivers 接收人列表
     * @param string $message 消息
     * @return void
     */
    private function sendSmsAlert(array $receivers, string $message): void
    {
        // 如果系统有短信服务，使用短信服务发送
        foreach ($receivers as $receiver) {
            Log::info("短信告警 [收件人: {$receiver}]: {$message}");
            // TODO: 集成实际的短信发送服务
            // SmsService::send($receiver, $message);
        }
    }

    /**
     * 发送微信告警
     *
     * @param array $receivers 接收人列表
     * @param string $title 标题
     * @param string $message 消息
     * @return void
     */
    private function sendWechatAlert(array $receivers, string $title, string $message): void
    {
        // 如果系统有微信服务，使用微信服务发送
        foreach ($receivers as $receiver) {
            Log::info("微信告警 [收件人: {$receiver}]: {$title}\n{$message}");
            // TODO: 集成实际的微信发送服务
            // WechatService::send($receiver, $title, $message);
        }
    }

    /**
     * 发送 Webhook 告警
     *
     * @param array $webhooks Webhook URL 列表
     * @param string $title 标题
     * @param string $message 消息
     * @param SystemCrontab $task 任务信息
     * @param string $alertType 告警类型
     * @return void
     * @throws GuzzleException
     */
    private function sendWebhookAlert(array $webhooks, string $title, string $message, SystemCrontab $task, string $alertType): void
    {
        $client = new Client(['timeout' => 10]);
        $payload = [
            'alert_type' => $alertType,
            'title' => $title,
            'message' => $message,
            'task_id' => $task->id,
            'task_name' => $task->name,
            'task_target' => $task->target,
            'timestamp' => time(),
        ];

        foreach ($webhooks as $webhook) {
            try {
                $client->post($webhook, [
                    'json' => $payload,
                ]);
            } catch (Exception $e) {
                Log::error("Webhook 告警发送失败 [URL: {$webhook}]: " . $e->getMessage());
            }
        }
    }

    /**
     * 安排任务重试
     *
     * @param int $taskId 任务ID
     * @param SystemCrontab $task 任务信息
     * @return void
     * @throws GuzzleException
     */
    private function scheduleRetry(int $taskId, SystemCrontab $task): void
    {
        // 获取当前重试次数
        $retryCount = $this->getRetryCount($taskId);
        $maxRetryCount = config('crontab.max_retry_count', 3);

        if ($retryCount >= $maxRetryCount) {
            // 已达到最大重试次数，发送告警
            $this->sendAlert($task, 'failure', [
                'failure_count' => $retryCount,
                'message' => "任务已达到最大重试次数 ({$maxRetryCount})，停止重试",
            ]);
            return;
        }

        // 计算重试延迟时间（指数退避）
        $retryInterval = config('crontab.retry_interval', 60);
        $multiplier = config('crontab.retry_interval_multiplier', 2);
        $delay = $retryInterval * pow($multiplier, $retryCount);

        // 记录重试计划
        $this->recordRetrySchedule($taskId, $retryCount + 1, $delay);

        // 使用延迟执行（这里使用简单的延迟，实际项目中可以使用队列系统）
        // 由于是同步执行，这里使用定时器延迟执行
        if (function_exists('Workerman\Timer::add')) {
            \Workerman\Timer::add($delay, function () use ($taskId) {
                $this->retryTask($taskId);
            }, [], false);
        } else {
            // 如果没有 Timer，记录到数据库，由另一个进程处理
            $this->saveRetryTask($taskId, $delay);
        }
    }

    /**
     * 获取任务重试次数
     *
     * @param int $taskId 任务ID
     * @return int 重试次数
     */
    private function getRetryCount(int $taskId): int
    {
        $retryKey = 'crontab_retry_count_' . $taskId;
        
        // 尝试从 Redis 获取
        try {
            if (class_exists('\Illuminate\Support\Facades\Redis')) {
                $redis = \Illuminate\Support\Facades\Redis::connection();
                $count = $redis->get($retryKey);
                return $count ? (int)$count : 0;
            }
        } catch (Exception $e) {
            // Redis 不可用，使用文件
        }

        // 使用文件存储
        $retryFile = runtime_path() . '/crontab_retries/' . $taskId . '.retry';
        if (file_exists($retryFile)) {
            $data = json_decode(file_get_contents($retryFile), true);
            return $data['count'] ?? 0;
        }

        return 0;
    }

    /**
     * 记录重试计划
     *
     * @param int $taskId 任务ID
     * @param int $retryCount 重试次数
     * @param int $delay 延迟时间（秒）
     * @return void
     */
    private function recordRetrySchedule(int $taskId, int $retryCount, int $delay): void
    {
        $retryKey = 'crontab_retry_count_' . $taskId;
        $retryData = [
            'count' => $retryCount,
            'next_retry_time' => time() + $delay,
            'delay' => $delay,
        ];

        // 尝试存储到 Redis
        try {
            if (class_exists('\Illuminate\Support\Facades\Redis')) {
                $redis = \Illuminate\Support\Facades\Redis::connection();
                $redis->setex($retryKey, $delay + 60, $retryCount); // 设置过期时间
                return;
            }
        } catch (Exception $e) {
            // Redis 不可用，使用文件
        }

        // 使用文件存储
        $retryDir = runtime_path() . '/crontab_retries';
        if (!is_dir($retryDir)) {
            mkdir($retryDir, 0755, true);
        }
        $retryFile = $retryDir . '/' . $taskId . '.retry';
        file_put_contents($retryFile, json_encode($retryData, JSON_UNESCAPED_UNICODE));
    }

    /**
     * 保存重试任务到数据库（用于队列处理）
     *
     * @param int $taskId 任务ID
     * @param int $delay 延迟时间（秒）
     * @return void
     */
    private function saveRetryTask(int $taskId, int $delay): void
    {
        // 这里可以将重试任务保存到数据库，由另一个进程处理
        // 或者使用队列系统
        Log::info("任务重试已安排 [任务ID: {$taskId}, 延迟: {$delay}秒]");
    }

    /**
     * 重试任务
     *
     * @param int $taskId 任务ID
     * @return bool 是否重试成功
     * @throws GuzzleException
     */
    public function retryTask(int $taskId): bool
    {
        Log::info("开始重试任务 [任务ID: {$taskId}]");
        
        $result = $this->run($taskId);
        
        if ($result) {
            // 重试成功，清除重试计数
            $this->clearRetryCount($taskId);
        }
        
        return $result;
    }

    /**
     * 清除重试计数
     *
     * @param int $taskId 任务ID
     * @return void
     */
    private function clearRetryCount(int $taskId): void
    {
        $retryKey = 'crontab_retry_count_' . $taskId;

        // 尝试从 Redis 清除
        try {
            if (class_exists('\Illuminate\Support\Facades\Redis')) {
                $redis = \Illuminate\Support\Facades\Redis::connection();
                $redis->del($retryKey);
                return;
            }
        } catch (Exception $e) {
            // Redis 不可用，删除文件
        }

        // 删除文件
        $retryFile = runtime_path() . '/crontab_retries/' . $taskId . '.retry';
        if (file_exists($retryFile)) {
            @unlink($retryFile);
        }
    }
}