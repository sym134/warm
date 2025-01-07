<?php

namespace warm\service\monitor;

use warm\model\monitor\AdminLoginLog;
use warm\service\AdminService;

/**
 * 登录日志
 *
 * @method AdminLoginLog getModel()
 * @method AdminLoginLog|\Illuminate\Database\Query\Builder query()
 */
class AdminLoginLogService extends AdminService
{
	protected string $modelName = AdminLoginLog::class;
}
