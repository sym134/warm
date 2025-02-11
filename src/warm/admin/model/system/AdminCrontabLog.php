<?php

namespace warm\admin\model\system;

use warm\admin\model\BaseModel as Model;

/**
 * 定时任务日志
 */
class AdminCrontabLog extends Model
{

    protected $table = 'crontab_log';

    protected $casts = [
        'parameter' => 'json',
    ];

    public const UPDATED_AT = null;
}
