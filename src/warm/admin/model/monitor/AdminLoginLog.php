<?php

namespace warm\admin\model\monitor;

use Illuminate\Database\Eloquent\SoftDeletes;
use warm\admin\model\BaseModel as Model;

/**
 * 登录日志
 */
class AdminLoginLog extends Model
{
    use SoftDeletes;
    const STATUS = [
        1 => '登陆成功',
        2 => '登陆失败',
        3 => '用户未启用',
    ];
    public const UPDATED_AT = null;
    protected $table = 'admin_login_log';
}
