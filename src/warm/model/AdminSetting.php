<?php

namespace warm\model;

class AdminSetting extends BaseModel
{
    protected $table = 'admin_settings';

    protected $primaryKey = 'key';

    protected $guarded = [];

    protected $casts = [
        'values' => 'json',
    ];

    protected function asJson($value): bool|string
    {
        return json_encode($value, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    }
}
