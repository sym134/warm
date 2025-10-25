<?php

namespace warm\admin\service\monitor;

use warm\admin\model\monitor\AdminLoginLog;
use warm\admin\service\AdminService;

/**
 * 登录日志服务类
 * 
 * 提供登录日志管理功能
 * 
 * @method AdminLoginLog getModel() 获取模型实例
 * @method AdminLoginLog|\Illuminate\Database\Query\Builder query() 获取查询构造器
 */
class AdminLoginLogService extends AdminService
{
    /**
     * 模型类名
     * 
     * @var string
     */
	protected string $modelName = AdminLoginLog::class;
}