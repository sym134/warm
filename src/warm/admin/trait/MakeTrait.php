<?php

namespace warm\admin\trait;

/**
 * 实例创建Trait
 * 
 * 提供便捷的静态工厂方法，用于创建类的实例
 * 支持传递构造函数参数创建实例
 */
trait MakeTrait
{
    /**
     * 创建类实例
     * 
     * @return static 类实例
     */
    public static function make(): static
    {
        return new static(...func_get_args());
    }
}