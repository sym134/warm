<?php

namespace warm\admin\model;

use Illuminate\Database\Eloquent\Concerns\HasTimestamps;

class AdminCodeGenerator extends BaseModel
{
    use HasTimestamps;

    protected $casts = [
        'columns'   => 'array',
        'needs'     => 'array',
        'menu_info' => 'array',
        'page_info' => 'array',
        'save_path' => 'array',
    ];
}
