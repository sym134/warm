<?php

namespace warm\admin\model\system;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\SoftDeletes;
use warm\admin\model\BaseModel as Model;
use warm\admin\service\system\AdminCrontabService;

/**
 * 定时任务
 */
class AdminCrontab extends Model
{
    use SoftDeletes;

    protected $appends = ['execution_cycle_text'];
    const TASK_TYPE = [
        1 => '访问URL-GET',
        2 => '访问URL-POST',
        3 => '类任务',
    ];

    const TASK_STATUS = [
        1 => '正常',
        2 => '停止',
    ];
    protected $casts = [
        'parameter' => 'json',
    ];

    protected $table = 'crontab';

    public function executionCycleText(): Attribute
    {
        return Attribute::get(function () {
            return AdminCrontabService::make()->crontabExpressionToText($this->attributes['execution_cycle'], $this->attributes['rule']);
        });
    }
}
