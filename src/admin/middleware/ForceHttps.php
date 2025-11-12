<?php

namespace warm\admin\middleware;

use warm\admin\Admin;
use Webman\Http\Request;
use Webman\Http\Response;
use Webman\MiddlewareInterface;

/**
 * 强制HTTPS中间件
 * 
 * 负责检查是否启用了HTTPS强制模式，
 * 如果系统配置要求使用HTTPS但当前请求不是HTTPS，则返回错误提示。
 * 
 * Author:sym
 * Date:2024/12/2 22:03
 * Company:极智科技
 */
class ForceHttps implements MiddlewareInterface
{
    /**
     * 处理HTTPS强制检查的中间件方法
     * 
     * 在请求处理前检查是否需要强制使用HTTPS：
     * 1. 检查当前请求协议版本是否为HTTP/1.1
     * 2. 检查系统是否配置了强制HTTPS
     * 3. 如果需要强制HTTPS但当前不是HTTPS，则返回错误提示
     * 4. 否则继续处理请求
     * 
     * @param Request $request HTTP请求对象
     * @param callable $handler 请求处理回调函数
     * @return Response HTTP响应对象
     */
    public function process(Request $request, callable $handler): Response
    {
        // 检查当前请求协议版本是否为HTTP/1.1且系统配置了强制HTTPS
        if ($request->protocolVersion() === '1.1' && Admin::warmConfig('app.https')) {
            // 如果需要强制HTTPS但当前不是HTTPS，则返回错误提示
            return Admin::response()->additional(['code' => 301])->fail(translator('::admin.https_is_not_enabled'));
        }

        // 继续处理请求
        return $handler($request);
    }
}