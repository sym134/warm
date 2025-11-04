<?php

namespace warm\common\service;

/**
 * 基础服务类
 * 
 * 为所有服务类提供基础功能，包括错误处理和返回码管理
 * 
 * @author heimiao
 * @date 2025-01-10 14:52
 */
class BaseService
{
    /**
     * 错误信息
     * 
     * @var string
     */
    protected static string $error;

    /**
     * 返回状态码
     * 
     * @var int
     */
    protected static int $returnCode = 0;

    /**
     * 返回数据
     * 
     * @var mixed
     */
    protected static mixed $returnData;

    /**
     * 获取错误信息
     *
     * @return string 错误信息
     *
     * @author heimiao
     * @date 2025-01-10 14:52
     */
    public static function getError() : string
    {
        if (false === self::hasError()) {
            return '系统错误';
        }
        return self::$error;
    }

    /**
     * 设置错误信息
     *
     * @param string $error 错误信息
     * @return void
     *
     * @author heimiao
     * @date 2025-01-10 14:52
     */
    public static function setError($error) : void
    {
        !empty($error) && self::$error = $error;
    }

    /**
     * 检查是否存在错误
     *
     * @return bool 是否存在错误
     *
     * @author heimiao
     * @date 2025-01-10 14:52
     */
    public static function hasError() : bool
    {
        return !empty(self::$error);
    }

    /**
     * 设置返回码
     *
     * @param int $code 返回状态码
     * @return void
     *
     * @author heimiao
     * @date 2025-01-10 14:52
     */
    public static function setReturnCode($code) : void
    {
        self::$returnCode = $code;
    }

    /**
     * 获取返回码
     *
     * @return int 返回状态码
     *
     * @author heimiao
     * @date 2025-01-10 14:52
     */
    public static function getReturnCode() : int
    {
        return self::$returnCode;
    }

    /**
     * 获取返回数据
     *
     * @return mixed 返回数据
     *
     * @author heimiao
     * @date 2025-01-10 14:52
     */
    public static function getReturnData(): mixed
    {
        return self::$returnData;
    }

    /**
     * 创建服务实例
     *
     * @return static 服务实例
     */
    public static function make(): static
    {
        return new static;
    }
}