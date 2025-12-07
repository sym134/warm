<?php

namespace warm\admin\middleware;

use warm\bootstrap\LaravelBridge;
use Webman\Http\Request;
use Webman\Http\Response;
use Webman\MiddlewareInterface;

class Config implements MiddlewareInterface
{

    /**
     * @inheritDoc
     */
    public function process(Request $request, callable $handler): Response
    {
        $app = LaravelBridge::app();
        // todo 查询缓存配置
        $app['config']->set('filesystems', config('filesystems'));
        return $handler($request);
    }
}