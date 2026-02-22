<?php

namespace warm\common\service\task;

use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use support\Log;
use warm\common\service\BaseService;
use warm\admin\model\system\SystemCrontab;
use warm\admin\service\system\SystemCrontabLogService;

/**
 * 任务监控服务类
 * 
 * 提供任务执行监控、告警等功能
 * 
 * @author sym
 * @date 2024/7/2
 */
class TaskMonitorService extends BaseService
{
    /**
     * 监控任务执行
     *
     * @param array $task 任务信息
     * @param bool $success 是否执行成功
     * @param float $executionTime 执行时间（毫秒）
     * @param array $logData 日志数据
     * @return void
     * @throws GuzzleException
     */
    public function monitorTaskExecution(array $task, bool $success, float $executionTime, array $logData): void
    {
        // 检查失败告警
        if (!$success) {
            $failureCount = $this->getRecentFailureCount($task['id']);
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
    public function getRecentFailureCount(int $taskId, int $minutes = 60): int
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
     * @param array $task 任务信息
     * @param string $alertType 告警类型：failure, timeout
     * @param array $data 告警数据
     * @return void
     * @throws GuzzleException
     */
    public function sendAlert(array $task, string $alertType, array $data = []): void
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
                Log::error("定时任务告警发送失败 [任务ID: {$task['id']}, 渠道: {$channel}]: " . $e->getMessage());
            }
        }
    }

    /**
     * 构建告警标题
     *
     * @param array $task 任务信息
     * @param string $alertType 告警类型
     * @return string
     */
    private function buildAlertTitle(array $task, string $alertType): string
    {
        $typeMap = [
            'failure' => '任务执行失败',
            'timeout' => '任务执行超时',
        ];
        
        return sprintf(
            '[定时任务告警] %s - %s',
            $typeMap[$alertType] ?? '未知告警',
            $task['name'] ?? "任务 #{$task['id']}"
        );
    }

    /**
     * 构建告警消息
     *
     * @param array $task 任务信息
     * @param string $alertType 告警类型
     * @param array $data 告警数据
     * @return string
     */
    private function buildAlertMessage(array $task, string $alertType, array $data): string
    {
        $message = "任务名称：{$task['name']}\n";
        $message .= "任务ID：{$task['id']}\n";
        $message .= "任务类型：" . (SystemCrontab::TASK_TYPE[$task['task_type']] ?? '未知') . "\n";
        $message .= "任务目标：{$task['target']}\n";
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
     * @param array $task 任务信息
     * @param string $alertType 告警类型
     * @return void
     * @throws GuzzleException
     */
    private function sendAlertToChannel(string $channel, array $receivers, string $title, string $message, array $task, string $alertType): void
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
        foreach ($receivers as $receiver) {
            Log::info("邮件告警 [收件人: {$receiver}]: {$title}\n{$message}");
            // TODO: 集成实际的邮件发送服务
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
        }
    }

    /**
     * 发送 Webhook 告警
     *
     * @param array $webhooks Webhook URL 列表
     * @param string $title 标题
     * @param string $message 消息
     * @param array $task 任务信息
     * @param string $alertType 告警类型
     * @return void
     * @throws GuzzleException
     */
    private function sendWebhookAlert(array $webhooks, string $title, string $message, array $task, string $alertType): void
    {
        $client = new Client(['timeout' => 10]);
        $payload = [
            'alert_type' => $alertType,
            'title' => $title,
            'message' => $message,
            'task_id' => $task['id'],
            'task_name' => $task['name'],
            'task_target' => $task['target'],
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
}