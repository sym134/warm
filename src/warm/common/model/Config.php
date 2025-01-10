<?php

namespace warm\common\model;

use warm\admin\model\BaseModel;

class Config extends BaseModel
{
    protected $table = 'config';

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
