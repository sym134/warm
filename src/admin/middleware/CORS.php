<?php

namespace warm\admin\middleware;

use Webman\MiddlewareInterface;
use Webman\Http\Request;
use Webman\Http\Response;

/**
 * CORS中间件类
 * 
 * 用于处理跨域资源共享(CORS)请求，允许前端应用从不同域访问后端API
 * 实现了HTTP OPTIONS预检请求的处理，并为响应添加必要的CORS头部信息
 */
class CORS implements MiddlewareInterface
{
    /**
     * 处理CORS请求
     * 
     * @param Request $request HTTP请求对象
     * @param callable $handler 请求处理器
     * @return Response HTTP响应对象
     */
    public function process(Request $request, callable $handler): Response
    {
        // 如果是OPTIONS预检请求则返回一个空响应，否则继续向洋葱模型中心穿越，并得到一个响应
        $response = $request->method() == 'OPTIONS' ? response('') : $handler($request);

        // 给响应添加跨域相关的HTTP头
        $response->withHeaders([
            // 允许携带凭证信息（如cookies）
            'Access-Control-Allow-Credentials' => 'true',
            // 允许的来源域名，从请求头获取或默认为'*'
            'Access-Control-Allow-Origin' => $request->header('origin', '*'),
            // 允许的HTTP方法，从请求头获取或默认为'*'
            'Access-Control-Allow-Methods' => $request->header('access-control-request-method', '*'),
            // 允许的请求头，从请求头获取或默认为'*'
            'Access-Control-Allow-Headers' => $request->header('access-control-request-headers', '*'),
        ]);

        return $response;
    }
}