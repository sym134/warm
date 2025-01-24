<?php

namespace warm\common\service;

class BaseService
{
    /**
     * 错误信息
     * @var string
     */
    protected static string $error;

    /**
     * 返回状态码
     * @var int
     */
    protected static int $returnCode = 0;


    protected static mixed $returnData;

    /**
     *
     * @return string
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
     *
     * @param $error
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
     *
     * @return bool
     *
     * @author heimiao
     * @date 2025-01-10 14:52
     */
    public static function hasError() : bool
    {
        return !empty(self::$error);
    }


    /**
     *
     * @param $code
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
     *
     * @return int
     *
     * @author heimiao
     * @date 2025-01-10 14:52
     */
    public static function getReturnCode() : int
    {
        return self::$returnCode;
    }

    /**
     *
     * @return mixed
     *
     * @author heimiao
     * @date 2025-01-10 14:52
     */
    public static function getReturnData(): mixed
    {
        return self::$returnData;
    }
}