<?php

namespace warm\admin\model;

class Plugin extends BaseModel
{
    protected $fillable = ['name', 'is_enabled', 'options'];

    protected $casts = [
        'options' => 'json',
    ];

    protected $table = 'admin_extensions';
}
