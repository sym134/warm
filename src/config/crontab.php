<?php
/**
 * 定时任务配置
 * 
 * 配置定时任务执行相关的参数
 */

return [
    /**
     * HTTP 请求超时时间（秒）
     * 默认 30 秒
     */
    'http_timeout' => env('CRONTAB_HTTP_TIMEOUT', 30),

    /**
     * SSL 证书验证
     * 生产环境建议设置为 true
     * 默认 true（启用验证）
     */
    'verify_ssl' => env('CRONTAB_VERIFY_SSL', true),

    /**
     * 是否启用任务监控
     * 启用后，会监控任务执行状态并发送告警
     * 默认 false
     */
    'enable_monitor' => env('CRONTAB_ENABLE_MONITOR', false),

    /**
     * 任务失败告警阈值
     * 连续失败多少次后发送告警
     * 默认 3 次
     */
    'failure_alert_threshold' => env('CRONTAB_FAILURE_ALERT_THRESHOLD', 3),

    /**
     * 任务执行超时告警阈值（毫秒）
     * 任务执行时间超过此值会发送告警
     * 默认 60000 毫秒（1分钟）
     */
    'timeout_alert_threshold_ms' => env('CRONTAB_TIMEOUT_ALERT_THRESHOLD_MS', 60000),

    /**
     * 告警通知渠道
     * 支持多个渠道，用逗号分隔
     * 可选值：email, sms, wechat, webhook
     * 默认空（不发送告警）
     */
    'alert_channels' => env('CRONTAB_ALERT_CHANNELS', ''),

    /**
     * 告警接收人
     * 多个接收人用逗号分隔
     * 默认空
     */
    'alert_receivers' => env('CRONTAB_ALERT_RECEIVERS', ''),

    /**
     * 是否启用队列执行
     * 启用后，任务会使用队列系统执行，需要配置队列服务
     * 默认 false
     */
    'enable_queue' => env('CRONTAB_ENABLE_QUEUE', false),

    /**
     * 队列名称（当 async_method 为 queue 时使用）
     * 默认 crontab_tasks
     */
    'queue_name' => env('CRONTAB_QUEUE_NAME', 'crontab_tasks'),
];

