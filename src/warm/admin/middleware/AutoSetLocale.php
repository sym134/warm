<?php

namespace warm\admin\middleware;

use warm\admin\Admin;
use Webman\Http\Request;
use Webman\Http\Response;
use Webman\MiddlewareInterface;

/**
 * 语言切换
 * AutoSetLocale
 * warm\middleware
 *
 * Author:sym
 * Date:2024/12/2 22:03
 * Company:极智科技
 */
class AutoSetLocale implements MiddlewareInterface
{
    public function process(Request $request, callable $handler): Response
    {
        $locale = request()->header('locale', Admin::config('app.translation.locale')); // 获取客户端要求的语言包
        // 切换语言
        locale($locale);
        return $handler($request);
    }
}
