<?php

namespace warm\admin\model\system;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\SoftDeletes;
use warm\admin\service\system\SystemCrontabService;
use warm\common\model\BaseModel as Model;

/**
 * 系统定时任务模型类
 * 
 * 该模型用于存储和管理系统的定时任务配置信息，包括：
 * 1. 任务类型（URL访问、类任务等）
 * 2. 任务状态（正常、停止等）
 * 3. 执行周期和规则
 * 4. 任务参数配置
 * 
 * 支持软删除功能。
 */
class SystemCrontab extends Model
{
    use SoftDeletes;

    /**
     * 需要追加到模型数组/JSON表示中的访问器
     * 
     * @var array
     */
    protected $appends = ['execution_cycle_text'];
    
    /**
     * 任务类型常量定义
     * 
     * @var array
     */
    const TASK_TYPE = [
        1 => '访问URL-GET',
        2 => '访问URL-POST',
        3 => '类任务',
    ];

    /**
     * 任务状态常量定义
     * 
     * @var array
     */
    const TASK_STATUS = [
        1 => '正常',
        2 => '停止',
    ];
    
    /**
     * 需要进行类型转换的字段
     * 
     * 将数据库中的JSON字符串自动转换为PHP数组
     * 
     * @var array
     */
    protected $casts = [
        'parameter' => 'json',  // 任务参数
    ];

    /**
     * 与模型关联的表名
     * 
     * @var string
     */
    protected $table = 'system_crontab';

    /**
     * 执行周期文本访问器
     * 
     * 将执行周期和规则转换为可读的文本描述
     * 
     * @return Attribute 执行周期文本属性访问器
     */
    public function executionCycleText(): Attribute
    {
        return Attribute::get(function () {
            // 调用服务类将cron表达式转换为文本描述
            return SystemCrontabService::make()->crontabExpressionToText($this->attributes['execution_cycle'], $this->attributes['rule']);
        });
    }
}