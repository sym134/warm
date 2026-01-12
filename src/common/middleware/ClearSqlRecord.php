<?php

namespace warm\common\middleware;

use Webman\MiddlewareInterface;
use Webman\Http\Response;
use Webman\Http\Request;
use warm\admin\support\SqlRecord;

/**
 * SQL记录清空中间件
 * 
 * 用于在每次请求开始时清空SQL记录，防止协程环境下多个请求之间的数据污染。
 * 在webman框架的协程环境中，静态变量会被多个请求共享，因此需要在每个请求
 * 开始时清空，确保每个请求只记录自己的SQL语句。
 */
class ClearSqlRecord implements MiddlewareInterface
{
    /**
     * 处理请求
     * 
     * @param Request $request HTTP请求对象
     * @param callable $handler 下一个中间件或控制器处理器
     * @return Response HTTP响应对象
     */
    public function process(Request $request, callable $handler): Response
    {
        // 在请求开始时清空SQL记录，防止协程环境下的数据污染
        if (config('app.debug')) {
            SqlRecord::clear();
        }
        
        try {
            // 继续处理请求
            $response = $handler($request);
            
            return $response;
        } catch (\Throwable $e) {
            // 重新抛出异常
            throw $e;
        } finally {
            // 无论请求处理成功还是异常，都在最后清空SQL记录（避免内存泄漏）
            // 注意：如果使用 JsonResponse，它会在响应中读取SQL记录后清空
            // 这里的清空作为保险，确保即使不使用 JsonResponse 也能清空记录
            if (config('app.debug')) {
                SqlRecord::clear();
            }
        }
    }
}

