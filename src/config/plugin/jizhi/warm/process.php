<?php

use warm\process\CrontabTask;

/**
 * Warm Admin 进程配置文件
 * 
 * 配置后台进程任务，用于处理定时任务、队列等后台操作
 */
return [
    // 定时任务进程配置
    'crontabTask'  => [
        // 定时任务处理器
        'handler'  => CrontabTask::class
    ],
];