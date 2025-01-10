<?php

namespace warm\admin\service\system;

use warm\admin\model\system\AdminCrontabLog;
use warm\admin\service\AdminService;

/**
 * 定时任务日志
 *
 * @method AdminCrontabLog getModel()
 * @method AdminCrontabLog|\Illuminate\Database\Query\Builder query()
 */
class AdminCrontabLogService extends AdminService
{
	protected string $modelName = AdminCrontabLog::class;
}
