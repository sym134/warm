<?php

namespace warm\model;

use Illuminate\Database\Eloquent\Concerns\HasTimestamps;

class AdminPage extends BaseModel
{
    use HasTimestamps;

    protected $casts = [
        'schema' => 'json',
    ];
}
