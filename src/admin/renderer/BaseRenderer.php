<?php

namespace warm\admin\renderer;

use Illuminate\Support\Traits\Macroable;
use JsonSerializable;
use warm\admin\support\cores\AdminPipeline;

class BaseRenderer implements JsonSerializable
{
    use Macroable {
        __call as macroCall;
    }

    public string $type;

    public array $amisSchema = [];

    public static function make(): static
    {
        return new static();
    }

    public function __call($method, $parameters)
    {
        if (static::hasMacro($method)) {
            return $this->macroCall($method, $parameters);
        }

        return $this->set($method, array_shift($parameters));
    }

    public function set($name, $value)
    {
        if ($name == 'map' && is_array($value) && array_keys($value) == array_keys(array_keys($value))) {
            $value = (object)$value;
        }

        if ($name === 'options' && is_array($value)) {
            $value = $this->normalizeOption($value);
        }

        $this->amisSchema[$name] = $value;

        return $this;
    }

    public function jsonSerialize(): mixed
    {
        return $this->filteredResults();
    }

    public function toJson(): bool|string
    {
        return json_encode($this->amisSchema);
    }

    public function toArray()
    {
        return $this->amisSchema;
    }

    /**
     * @param string $sign 权限标识
     * @param mixed $replaceValue 无权限时替换的值
     *
     * @return $this
     */
    public function permission(string $sign, mixed $replaceValue = '')
    {
        $this->amisSchema['warm_permission'] = $sign;
        $this->amisSchema['warm_permission_replace_value'] = $replaceValue;

        return $this;
    }

    public function filteredResults()
    {
        $permissionKey = 'warm_permission';

        if (key_exists($permissionKey, $this->amisSchema)) {
            if (!admin_user()->can($this->amisSchema[$permissionKey])) {
                return data_get($this->amisSchema, 'warm_permission_replace_value', '');
            }
        }

        return AdminPipeline::handle(static::class, $this->amisSchema);
    }

    public function normalizeOption($input)
    {
        // 非数组或对象，直接返回
        if (!is_array($input) && !is_object($input)) {
            return $input;
        }

        // 将对象转换为数组，确保可迭代
        $input = (array) $input;

        // 如果数组中包含嵌套数组（即不是一维数组），直接返回
        if (count(array_filter($input, 'is_array')) > 0) {
            return $input;
        }

        return array_map(function ($value, $key) {
            return (is_array($value) && isset($value['label'], $value['value']))
                ? $value  // 如果已是标准格式，直接保留
                : ['label' => $value, 'value' => $key]; // 否则转换
        }, $input, array_keys($input));
    }

}
