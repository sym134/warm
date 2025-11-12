<?php

namespace warm\admin\support\apis;

/**
 * 管理API接口类
 * 
 * 定义管理API必须实现的接口方法，确保所有API具有一致的结构和行为。
 * 所有具体的API实现类都必须实现此接口。
 * 
 * @package warm\admin\support\apis
 */
interface AdminApiInterface
{
    /**
     * 接口处理逻辑
     * 
     * 执行API的核心业务逻辑，处理请求并返回响应结果
     * 
     * @return mixed API处理结果
     */
    public function handle(): mixed;

    /**
     * 接口参数设置 (表单结构)
     * 
     * 定义API所需的参数结构，通常用于生成前端表单
     * 
     * @return mixed 参数表单结构
     */
    public function argsSchema(): mixed;
}