<?php

namespace warm\admin\model\monitor;

use Illuminate\Database\Eloquent\SoftDeletes;
use warm\common\model\BaseModel;

/**
 * 管理操作日志模型类
 * 
 * 该模型用于记录和管理后台用户的操作日志信息，包括：
 * 1. 操作用户信息
 * 2. 操作时间、IP地址
 * 3. 操作内容和路径
 * 
 * 支持软删除功能。
 */
class AdminOperationLog extends BaseModel
{
    use SoftDeletes;

    /**
     * 与模型关联的表名
     * 
     * @var string
     */
    protected $table = 'admin_operation_log';
    
    /**
     * 禁用 updated_at 字段
     * 
     * @var null
     */
    public const UPDATED_AT = null;
}