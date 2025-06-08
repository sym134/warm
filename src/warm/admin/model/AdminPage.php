<?php

namespace warm\admin\model;

use Illuminate\Database\Eloquent\Concerns\HasTimestamps;
use warm\common\model\BaseModel;

class AdminPage extends BaseModel
{
    use HasTimestamps;

    protected $casts = [
        'schema' => 'json',
    ];
}
