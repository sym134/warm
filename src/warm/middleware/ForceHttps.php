<?php

namespace warm\middleware;

use warm\Admin;
use Webman\Http\Request;
use Webman\Http\Response;
use Webman\MiddlewareInterface;

/**
 * 判断https
 * ForceHttps
 * warm\middleware
 *
 * Author:sym
 * Date:2024/12/2 22:03
 * Company:极智科技
 */
class ForceHttps implements MiddlewareInterface
{
    public function process(Request $request, callable $handler): Response
    {
        if ($request->protocolVersion() === '1.1' && Admin::config('app.https')) {
            return Admin::response()->additional(['code' => 301])->fail('请使用https');
        }

        return $handler($request);
    }
}
