<?php

namespace warm\admin\model\monitor;

use Illuminate\Database\Eloquent\SoftDeletes;
use warm\common\model\BaseModel as Model;

/**
 * 管理登录日志模型类
 * 
 * 该模型用于记录和管理后台用户的登录日志信息，包括：
 * 1. 登录用户信息
 * 2. 登录时间、IP地址
 * 3. 登录状态（成功/失败）
 * 
 * 支持软删除功能。
 */
class AdminLoginLog extends Model
{
    use SoftDeletes;
    
    /**
     * 登录状态常量定义
     * 
     * @var array
     */
    const STATUS = [
        1 => '登陆成功',
        2 => '登陆失败',
        3 => '用户未启用',
    ];
    
    /**
     * 禁用 updated_at 字段
     * 
     * @var null
     */
    public const UPDATED_AT = null;
    
    /**
     * 与模型关联的表名
     * 
     * @var string
     */
    protected $table = 'admin_login_log';
}