<?php

namespace warm\admin\model\system;

use warm\common\model\BaseModel as Model;

/**
 * 系统定时任务日志模型类
 * 
 * 该模型用于记录和管理定时任务的执行日志信息，包括：
 * 1. 任务执行状态（成功、失败等）
 * 2. 任务执行参数
 * 3. 执行时间和结果
 */
class SystemCrontabLog extends Model
{
    /**
     * 执行状态常量定义
     * 
     * @var array
     */
    const EXECUTION_STATUS = [
        '1' => '成功',
        '2' => '失败',
    ];

    /**
     * 与模型关联的表名
     * 
     * @var string
     */
    protected $table = 'system_crontab_log';

    protected $casts = [
        'parameter' => 'json',
        'exception_info' => 'json',
    ];
}