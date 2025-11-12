<?php

namespace warm\admin\middleware;

use warm\admin\Admin;
use Webman\Http\Request;
use Webman\Http\Response;
use Webman\MiddlewareInterface;

/**
 * 权限中间件
 * 
 * 负责检查用户是否有权限访问特定的路由或资源。
 * 在请求处理前进行权限验证，如果用户没有权限则返回未授权错误。
 * 
 * Author:sym
 * Date:2024/12/2 22:03
 * Company:极智科技
 */
class Permission implements MiddlewareInterface
{
    /**
     * 处理权限验证的中间件方法
     * 
     * 在请求被处理前检查用户权限：
     * 1. 调用权限服务的permissionIntercept方法进行权限拦截检查
     * 2. 如果权限检查失败，返回未授权错误响应
     * 3. 如果权限检查通过，继续处理请求
     * 
     * @param Request $request HTTP请求对象
     * @param callable $handler 请求处理回调函数
     * @return Response HTTP响应对象
     */
    public function process(Request $request, callable $handler): Response
    {
        // 调用权限服务进行权限拦截检查
        if (Admin::permission()->permissionIntercept($request, '')) {
            // 如果权限检查失败，返回未授权错误响应
            return Admin::response()->fail(translator('::admin.unauthorized'));
        }
        // 权限检查通过，继续处理请求
        return $handler($request);
    }
}