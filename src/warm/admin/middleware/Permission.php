<?php

namespace warm\admin\middleware;

use warm\admin\Admin;
use Webman\Http\Request;
use Webman\Http\Response;
use Webman\MiddlewareInterface;

/**
 * 权限
 * Permission
 * warm\middleware
 *
 * Author:sym
 * Date:2024/12/2 22:03
 * Company:极智科技
 */
class Permission implements MiddlewareInterface
{
    public function process(Request $request, callable $handler): Response
    {
        if (Admin::permission()->permissionIntercept($request, '')) {
            return Admin::response()->fail(admin_trans('admin.unauthorized'));
        }
        return $handler($request);
    }
}

