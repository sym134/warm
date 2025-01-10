<?php

namespace warm\admin\service\monitor;

use warm\admin\model\monitor\AdminLoginLog;
use warm\admin\service\AdminService;

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
