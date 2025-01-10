<?php

namespace warm\admin\support\cores;

use Illuminate\Support\Str;
use InvalidArgumentException;
use warm\admin\Admin;
use Webman\Http\Request;

class Permission
{
    public array $authExcept = [
        'login',
        'logout',
        'no-content',
        '_settings',
        'captcha',
        '_download_export',
    ];

    public array $permissionExcept = [
        'menus',
        'current-user',
        'user_setting',
        'login',
        'logout',
        'no-content',
        '_settings',
        'upload_image',
        'upload_file',
        'upload_rich',
        'captcha',
        '_download_export',
    ];

    /**
     * 身份验证拦截
     *
     * @param $request
     *
     * @return array
     */
    public function authIntercept($request): array
    {
        if (!Admin::config('app.auth.enable')) {
            return [false, null];
        }

        $excepted = collect(Admin::config('app.auth.except', []))
            ->merge($this->authExcept)
            ->map(fn($path) => $this->pathFormatting($path))
            ->contains(fn($except) => collect($except == '/' ? $except : trim($except, '/'))->contains(fn($pattern) => Str::is($pattern, trim($request->path(), '/'))));
        $user = Admin::guard()->user();
        return [!$excepted && empty($user), $user];
    }

    /**
     * 权限拦截
     *
     * @param Request          $request
     * @param                  $args
     *
     * @return bool
     */
    public function permissionIntercept(Request $request, $args): bool
    {
        if (Admin::config('app.auth.permission') === false) {
            return false;
        }

        if ($request->path() == Admin::config('app.route.prefix')) {
            return false;
        }

        $excepted = collect(Admin::config('app.auth.except', []))
            ->merge($this->permissionExcept)
            ->merge(Admin::config('app.show_development_tools') ? ['/dev_tools*'] : [])
            ->map(fn($path) => $this->pathFormatting($path))
            ->contains(fn($except) => collect($except == '/' ? $except : trim($except, '/'))->contains(fn($pattern) => Str::is($pattern, trim($request->path(), '/'))));

        if ($excepted) {
            return false;
        }

        $user = $request->user;

        if (!empty($args) || $this->checkRoutePermission($request) || $user?->isAdministrator()) {
            return false;
        }

        return !$user?->allPermissions()->first(fn($permission) => $permission->shouldPassThrough($request));
    }

    protected function checkRoutePermission(Request $request): bool
    {
        $middlewarePrefix = 'admin.permission:';

        $middleware = collect($request->route
        ?->middleware())->first(fn($middleware) => Str::startsWith($middleware, $middlewarePrefix));

        if (!$middleware) {
            return false;
        }

        $args = explode(',', str_replace($middlewarePrefix, '', $middleware));

        $method = array_shift($args);

        if (!method_exists(Admin::adminPermissionModel(), $method)) {
            throw new InvalidArgumentException("Invalid permission method [$method].");
        }

        call_user_func([Admin::adminPermissionModel(), $method], $args);

        return true;
    }

    private function pathFormatting($path): string
    {
        $prefix = '/' . trim(Admin::config('app.route.prefix'), '/');

        $prefix = ($prefix === '/') ? '' : $prefix;

        $path = trim($path, '/');

        if (is_null($path) || $path === '') {
            return $prefix ?: '/';
        }
        return $prefix . '/' . $path;
    }
}
