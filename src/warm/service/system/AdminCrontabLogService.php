<?php

namespace warm\service\system;

use warm\model\system\AdminCrontabLog;
use warm\service\AdminService;

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
