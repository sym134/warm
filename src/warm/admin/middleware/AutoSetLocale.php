<?php

namespace warm\admin\middleware;

use warm\admin\Admin;
use warm\common\service\ConfigService;
use warm\framework\support\facade\Cache;
use Webman\Http\Request;
use Webman\Http\Response;
use Webman\MiddlewareInterface;

/**
 * 语言切换中间件
 * 
 * 负责根据请求头中的语言设置自动切换系统语言环境。
 * 支持多语言国际化功能，提升用户体验。
 * 
 * Author:sym
 * Date:2024/12/2 22:03
 * Company:极智科技
 */
class AutoSetLocale implements MiddlewareInterface
{
    /**
     * 处理语言环境切换的中间件方法
     * 
     * 在请求处理前自动设置语言环境：
     * 1. 从请求头中获取客户端要求的语言包
     * 2. 如果请求头中没有指定语言，则使用系统默认语言
     * 3. 切换系统语言环境
     * 4. 继续处理请求
     * 
     * @param Request $request HTTP请求对象
     * @param callable $handler 请求处理回调函数
     * @return Response HTTP响应对象
     */
    public function process(Request $request, callable $handler): Response
    {
        // 从请求头中获取客户端要求的语言包，如果没有则使用系统默认语言
        $locale = request()->header('locale', ConfigService::get('admin_locale'));

        // 切换系统语言环境
        locale($locale);
        
        // 继续处理请求
        return $handler($request);
    }
}