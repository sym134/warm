<?php

namespace warm\admin\support\cores;

use Illuminate\Support\Arr;
use Illuminate\Support\Fluent;
use warm\admin\support\Helper;

/**
 * 上下文管理类
 * 
 * 用于管理系统运行时上下文数据，提供数据的存储、获取、缓存等功能
 * 继承自Fluent类，支持链式调用
 * 
 * @property array $apis API列表
 */
class Context extends Fluent
{
    /**
     * 设置上下文数据
     * 
     * @param string|array $key 键名或键值对数组
     * @param mixed $value 值（当$key为字符串时使用）
     * @return static 返回当前实例以支持链式调用
     */
    public function set($key, $value = null): static
    {
        $data = is_array($key) ? $key : [$key => $value];

        foreach ($data as $key => $value) {
            Arr::set($this->attributes, $key, $value);
        }

        return $this;
    }

    /**
     * 获取上下文数据
     * 
     * @param string $key 键名
     * @param mixed $default 默认值
     * @return mixed 获取的数据
     */
    public function get($key, $default = null)
    {
        return Arr::get($this->attributes, $key, $default);
    }

    /**
     * 记住并缓存数据
     * 
     * 如果数据已存在则直接返回，否则执行回调函数并缓存结果
     * 
     * @param string $key 键名
     * @param \Closure $callback 回调函数
     * @return mixed 缓存的数据
     */
    public function remember($key, \Closure $callback)
    {
        if (($value = $this->get($key)) !== null) {
            return $value;
        }

        return tap($callback(), function ($value) use ($key) {
            $this->set($key, $value);
        });
    }

    /**
     * 获取数组类型数据
     * 
     * @param string $key 键名
     * @param mixed $default 默认值
     * @return array 数组类型的数据
     */
    public function getArray($key, $default = null): array
    {
        return Helper::array($this->get($key, $default), false);
    }

    /**
     * 向数组中添加元素
     * 
     * @param string $key 键名
     * @param mixed $value 值
     * @param mixed $k 键（可选）
     * @return static 返回当前实例以支持链式调用
     */
    public function add($key, $value, $k = null): static
    {
        $results = $this->getArray($key);

        if ($k === null) {
            $results[] = $value;
        } else {
            $results[$k] = $value;
        }

        return $this->set($key, $results);
    }

    /**
     * 合并数组
     * 
     * @param string $key 键名
     * @param array $value 要合并的数组
     * @return static 返回当前实例以支持链式调用
     */
    public function merge($key, array $value): static
    {
        $results = $this->getArray($key);

        return $this->set($key, array_merge($results, $value));
    }

    /**
     * 删除指定键的数据
     * 
     * @param string|array $keys 键名或键名数组
     * @return void
     */
    public function forget($keys): void
    {
        Arr::forget($this->attributes, $keys);
    }

    /**
     * 清空所有上下文数据
     * 
     * @return void
     */
    public function flush(): void
    {
        $this->attributes = [];
    }
}