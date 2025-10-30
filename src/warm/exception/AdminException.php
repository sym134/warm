<?php

namespace warm\exception;

use support\Response;
use warm\admin\Admin;

/**
 * 管理后台异常类
 * 
 * 自定义的管理后台异常处理类，继承自系统异常类
 * 提供统一的异常响应格式，支持自定义数据和提示信息控制
 */
class AdminException extends \Exception
{
    /**
     * 异常附带数据
     * 
     * @var mixed
     */
    private mixed $data;
    
    /**
     * 是否不显示提示信息
     * 
     * @var mixed
     */
    private mixed $doNotDisplayToast;

    /**
     * 构造函数
     * 
     * 初始化管理后台异常实例
     * 
     * @param string $message 异常消息
     * @param array $data 异常附带数据
     * @param int $doNotDisplayToast 是否不显示提示信息，0-显示，1-不显示
     */
    public function __construct(string $message = "", $data = [], $doNotDisplayToast = 0)
    {
        parent::__construct($message);

        $this->data              = $data;
        $this->doNotDisplayToast = $doNotDisplayToast;
    }

    /**
     * 渲染异常响应
     * 
     * 将异常转换为标准的管理后台响应格式
     * 
     * @return Response 响应对象
     */
    public function render(): Response
    {
        return Admin::response()->doNotDisplayToast($this->doNotDisplayToast)->fail($this->getMessage(), $this->data);
    }
}