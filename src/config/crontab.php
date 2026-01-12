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
     * 任务执行超时时间（秒）
     * 防止任务执行时间过长
     * 默认 300 秒（5分钟）
     */
    'task_timeout' => env('CRONTAB_TASK_TIMEOUT', 300),

    /**
     * 是否启用并发控制
     * 启用后，同一任务不会并发执行
     * 默认 false
     */
    'enable_concurrent_control' => env('CRONTAB_ENABLE_CONCURRENT_CONTROL', false),

    /**
     * 并发控制锁过期时间（秒）
     * 防止死锁，默认 3600 秒（1小时）
     */
    'lock_expire' => env('CRONTAB_LOCK_EXPIRE', 3600),

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
     * 是否启用失败任务重试
     * 启用后，失败的任务会自动重试
     * 默认 false
     */
    'enable_retry' => env('CRONTAB_ENABLE_RETRY', false),

    /**
     * 最大重试次数
     * 任务失败后最多重试多少次
     * 默认 3 次
     */
    'max_retry_count' => env('CRONTAB_MAX_RETRY_COUNT', 3),

    /**
     * 重试间隔（秒）
     * 任务失败后等待多少秒再重试
     * 默认 60 秒
     */
    'retry_interval' => env('CRONTAB_RETRY_INTERVAL', 60),

    /**
     * 重试间隔递增倍数
     * 每次重试后，间隔时间会乘以这个倍数
     * 例如：60秒 * 2 = 120秒，120秒 * 2 = 240秒
     * 默认 2（指数退避）
     */
    'retry_interval_multiplier' => env('CRONTAB_RETRY_INTERVAL_MULTIPLIER', 2),

    /**
     * 是否启用异步执行
     * 启用后，任务会在协程或队列中异步执行，不阻塞 Worker 进程
     * 默认 false（同步执行）
     */
    'enable_async' => env('CRONTAB_ENABLE_ASYNC', false),

    /**
     * 异步执行方式
     * 可选值：coroutine（协程）, queue（队列）, process（进程）
     * 默认 coroutine
     * 
     * 说明：
     * - coroutine: 使用 Workerman 协程，需要安装 workerman/coroutine 扩展
     * - queue: 使用队列系统，需要配置队列服务
     * - process: 使用独立进程执行（当前不支持，保留用于未来扩展）
     */
    'async_method' => env('CRONTAB_ASYNC_METHOD', 'coroutine'),

    /**
     * 异步执行超时时间（秒）
     * 异步执行时，如果任务执行时间超过此值，会记录警告
     * 默认 300 秒（5分钟）
     */
    'async_timeout' => env('CRONTAB_ASYNC_TIMEOUT', 300),

    /**
     * 队列名称（当 async_method 为 queue 时使用）
     * 默认 crontab_tasks
     */
    'queue_name' => env('CRONTAB_QUEUE_NAME', 'crontab_tasks'),
];

