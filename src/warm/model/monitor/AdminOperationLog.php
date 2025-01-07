<?php

namespace warm\model\monitor;

use Illuminate\Database\Eloquent\SoftDeletes;
use warm\model\BaseModel;

class AdminOperationLog extends BaseModel
{
    use SoftDeletes;

    protected $table = 'admin_operation_log';
    public const UPDATED_AT = null;
}
