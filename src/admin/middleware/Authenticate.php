<?php

namespace warm\admin\middleware;

use warm\admin\Admin;
use Webman\Event\Event;
use Webman\Http\Request;
use Webman\Http\Response;
use Webman\MiddlewareInterface;

/**
 * 身份认证中间件
 * 
 * 负责验证用户身份，确保只有已登录用户才能访问受保护的资源。
 * 同时会记录用户操作日志。
 */
class Authenticate implements MiddlewareInterface
{
    /**
     * 处理身份认证的中间件方法
     * 
     * 在请求处理前进行身份验证：
     * 1. 调用权限服务的authIntercept方法进行身份拦截检查
     * 2. 如果身份验证失败，返回请先登录错误响应
     * 3. 如果身份验证通过，将用户信息附加到请求对象
     * 4. 触发用户操作日志事件
     * 5. 继续处理请求
     * 
     * @param Request $request HTTP请求对象
     * @param callable $handler 请求处理回调函数
     * @return Response HTTP响应对象
     */
    public function process(Request $request, callable $handler): Response
    {
        // 调用权限服务进行身份拦截检查，返回状态和用户信息
        [$state, $user] = Admin::permission()->authIntercept($request);
        
        // 如果身份验证失败（状态为true表示需要登录）
        if ($state) {
            // 返回请先登录错误响应
            return Admin::response()->additional(['code' => 401])->fail(translator('::admin.please_login'));
        }
        
        // 将用户信息附加到请求对象
        $request->user = $user;
        
        // 记录用户操作日志事件
        Event::emit('user.operateLog', true);
        
        // 继续处理请求
        return $handler($request);
    }
}