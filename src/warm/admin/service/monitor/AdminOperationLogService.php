<?php

namespace warm\admin\service\monitor;

use warm\admin\model\monitor\AdminOperationLog;
use warm\admin\service\AdminService;

/**
 * 操作日志服务类
 * 
 * 提供操作日志管理功能，支持创建时间范围搜索
 */
class AdminOperationLogService extends AdminService
{
    /**
     * 模型类名
     * 
     * @var string
     */
    protected string $modelName = AdminOperationLog::class;

    /**
     * 搜索处理
     * 
     * 重写搜索方法，支持创建时间范围搜索
     * 
     * @param mixed $query 查询构造器
     * @return void
     */
    public function searchable($query): void
    {
        collect(array_keys(request()->all()))
            ->intersect($this->getTableColumns())
            ->map(function ($field) use ($query) {
                $query->when(request()->input($field), function ($query) use ($field) {
                    if ($field === 'created_at') {
                        $created_at = explode(',', request()->input($field));
                        $query->whereBetween($field, [$created_at[0], $created_at[1]]);
                    } else {
                        $query->where($field, 'like', '%' . request()->input($field) . '%');
                    }
                });
            });
    }
}