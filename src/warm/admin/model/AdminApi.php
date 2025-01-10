<?php

namespace warm\admin\model;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Concerns\HasTimestamps;
use warm\admin\support\apis\AdminBaseApi;

class AdminApi extends BaseModel
{
    use HasTimestamps;

    protected $appends = ['template_title', 'method'];

    protected $casts = [
        'args' => 'json',
    ];

    const METHODS = ['get', 'head', 'post', 'put', 'patch', 'delete', 'options'];

    public function templateTitle(): Attribute
    {
        return Attribute::get(function () {
            if (!class_exists($this->template)) return '';
            if (!(new \ReflectionClass($this->template))->isSubclassOf(AdminBaseApi::class)) return '';

            $api = appw($this->template);

            return $api->getMethod() . ' - ' . $api->getTitle();
        });
    }

    public function method(): Attribute
    {
        return Attribute::get(function () {
            if (!class_exists($this->template)) return '';
            if (!(new \ReflectionClass($this->template))->isSubclassOf(AdminBaseApi::class)) return 'any';

            $method = appw($this->template)->getMethod();

            return in_array($method, self::METHODS) ? $method : 'any';
        });
    }
}
