<?php

namespace warm\admin\middleware;

use Webman\Http\Request;
use Webman\Http\Response;
use Webman\MiddlewareInterface;

/**
 * 数据库连接中间件
 * 
 * 负责处理多租户系统的数据库连接逻辑。
 * 根据请求的域名或其他标识来确定应该连接哪个租户的数据库。
 * 
 * 注意：当前实现中相关逻辑被注释掉了，需要根据实际业务需求进行实现。
 * 
 * Author:sym
 * Date:2024/6/17 下午3:47
 * Company:极智网络科技
 */
class ConnectionDatabase implements MiddlewareInterface
{
    /**
     * 处理数据库连接的中间件方法
     * 
     * 在请求处理前确定数据库连接：
     * 1. 获取请求的域名或其他租户标识
     * 2. 根据标识查找对应的租户信息
     * 3. 设置请求的租户数据库连接
     * 4. 继续处理请求
     * 
     * 注意：当前实现中相关逻辑被注释掉了，需要根据实际业务需求进行实现。
     * 
     * @param Request $request HTTP请求对象
     * @param callable $handler 请求处理回调函数
     * @return Response HTTP响应对象
     */
    public function process(Request $request, callable $handler): Response
    {
        // 获取请求的域名用于确定租户
        // $request->header('x-site-domain')
        // $domain = $request->host(true)?? 'https://newtrain.tinywan.com';
        
        // 根据域名查找租户信息
        // $platform = TenantModel::where('domain', $domain)->field('id, domain, website')->findOrEmpty();
        
        // 如果找到了租户信息，则设置请求的租户标识
        // if (!$platform->isEmpty()) {
        //     $request->tenant = $platform['website'];
        // }
        
        // 继续处理请求
        return $handler($request);
    }

}