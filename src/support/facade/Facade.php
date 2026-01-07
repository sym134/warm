<?php

namespace warm\support\facade;

use support\Container;

/**
 * Facade管理类
 * 
 * 门面模式的基类，提供简化的静态接口访问底层复杂子系统
 * 负责创建和管理各种服务的实例
 */
class Facade
{
    /**
     * 始终创建新的对象实例
     * 
     * 控制是否每次都创建新的实例而不是使用单例
     * 
     * @var bool
     */
    protected static bool $alwaysNewInstance = false;

    /**
     * 创建Facade实例
     * 
     * 根据类名和参数创建或获取对应的实例
     * 
     * @static
     * @access protected
     * @param string $class 类名或标识
     * @param array $args 构造参数
     * @param bool $newInstance 是否每次创建新的实例
     * @return object 类实例
     */
    protected static function createFacade(string $class = '', array $args = [], bool $newInstance = false): object
    {
        $class = $class ?: static::class;

        $facadeClass = static::getFacadeClass();

        if ($facadeClass) {
            $class = $facadeClass;
        }

        if (static::$alwaysNewInstance) {
            $newInstance = true;
        }

         if ($newInstance){
             return Container::make($class, $args);
         }
        return Container::get($class, $args, $newInstance);
    }

    /**
     * 获取当前Facade对应类名
     * 
     * 子类需要重写此方法来指定实际的类名
     * 
     * @access protected
     * @return string|null 类名
     */
    protected static function getFacadeClass()
    {
    }

    /**
     * 带参数实例化当前Facade类
     * 
     * @access public
     * @param mixed ...$args 构造参数
     * @return object|null 类实例
     */
    public static function instance(...$args)
    {
        if (__CLASS__ != static::class) {
            return self::createFacade('', $args);
        }
    }

    /**
     * 调用类的实例
     * 
     * 获取指定类的实例，支持单例或每次都创建新实例
     * 
     * @access public
     * @param string $class 类名或者标识
     * @param bool|array $args 变量参数
     * @param bool $newInstance 是否每次创建新的实例
     * @return object 类实例
     */
    public static function make(string $class, bool|array $args = [], bool $newInstance = false): object
    {
        if (__CLASS__ != static::class) {
            return self::__callStatic('make', func_get_args());
        }

        if (true === $args) {
            // 总是创建新的实例化对象
            $newInstance = true;
            $args = [];
        }

        return self::createFacade($class, $args, $newInstance);
    }

    /**
     * 调用实际类的方法
     * 
     * 将对门面类的静态方法调用转发给实际的类实例
     * 
     * @param string $method 方法名
     * @param array $params 方法参数
     * @return mixed 方法调用结果
     */
    public static function __callStatic($method, $params)
    {
        return call_user_func_array([static::createFacade(), $method], $params);
    }
}