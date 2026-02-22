<?php

namespace warm\admin\service\system;

use Illuminate\Database\Query\Builder;
use warm\admin\model\system\SystemCrontabLog;
use warm\admin\service\AdminService;

/**
 * 定时任务日志服务类
 * 
 * 提供定时任务日志管理功能
 * 
 * @method SystemCrontabLog getModel() 获取模型实例
 * @method SystemCrontabLog|Builder query() 获取查询构造器
 */
class SystemCrontabLogService extends AdminService
{
    /**
     * 模型类名
     * 
     * @var string
     */
	protected string $modelName = SystemCrontabLog::class;
}