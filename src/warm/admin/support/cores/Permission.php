<?php

namespace warm\admin\support\cores;

use Illuminate\Support\Str;
use InvalidArgumentException;
use ReflectionClass;
use warm\admin\Admin;
use Webman\Http\Request;

/**
 * 权限管理类
 * 
 * 用于处理系统权限验证，包括身份验证和权限拦截
 * 提供路由权限检查和路径格式化等功能
 */
class Permission
{
    /** @var array 身份验证排除路径 */
    public array $authExcept = [
        'login',
        'logout',
        'no-content',
        '_settings',
        'captcha',
        '_download_export',
    ];

    /** @var array 权限验证排除路径 */
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
     * 缓存控制器属性以提高性能
     * 
     * @var array
     */
    protected array $controllerProperties = [];

    /**
     * 身份验证拦截
     * 
     * @param Request $request 请求对象
     * @return array 包含是否需要拦截和用户信息的数组
     */
    public function authIntercept(Request $request): array
    {
        if (!Admin::warmConfig('app.auth.enable')) {
            return [false, null];
        }

        // 检查是否可以通过控制器属性跳过登录验证
        if ($this->shouldSkipLogin($request)) {
            return [false, Admin::guard()->user()]; // 不需要登录验证，直接返回当前用户
        }

        $excepted = collect(Admin::warmConfig('app.auth.except', []))
            ->merge($this->authExcept)
            ->map(fn($path) => $this->pathFormatting($path))
            ->contains(fn($except) => collect($except == '/' ? $except : trim($except, '/'))->contains(fn($pattern) => Str::is($pattern, trim($request->path(), '/'))));
        $user = Admin::guard()->user();
        return [!$excepted && empty($user), $user];
    }

    /**
     * 权限拦截
     * 
     * @param Request $request 请求对象
     * @param mixed $args 参数
     * @return bool 是否需要拦截
     */
    public function permissionIntercept(Request $request, mixed $args): bool
    {
        if (Admin::warmConfig('app.auth.permission') === false) {
            return false;
        }

        if ($request->path() == Admin::warmConfig('app.route.prefix')) {
            return false;
        }

        // 检查是否可以通过控制器属性跳过权限验证
        if ($this->shouldSkipPermission($request)) {
            return false; // 不需要权限验证
        }

        $excepted = collect(Admin::warmConfig('app.auth.except', []))
            ->merge($this->permissionExcept)
            ->merge(Admin::warmConfig('app.show_development_tools') ? ['/dev_tools*'] : [])
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

    /**
     * 获取控制器属性
     * 
     * @param Request $request 请求对象
     * @return array 控制器属性数组
     */
    protected function getControllerProperties(Request $request): array
    {
        // 获取当前路由对应的控制器和方法
        $controller = $request->controller ?? null;
        $action = $request->action ?? null;

        // 确保控制器和方法都存在
        if (!$controller || !$action) {
            return []; // 返回空数组
        }

        // 生成缓存键
        $cacheKey = is_object($controller) ? get_class($controller) : (string) $controller;

        // 检查是否已缓存
        if (isset($this->controllerProperties[$cacheKey])) {
            return $this->controllerProperties[$cacheKey];
        }

        try {
            // 使用反射获取控制器类信息
            $reflection = new ReflectionClass($controller);
            $properties = $reflection->getDefaultProperties();
            
            // 缓存结果
            $this->controllerProperties[$cacheKey] = $properties;
            
            return $properties;
        } catch (\ReflectionException $e) {
            // 如果反射失败，返回空数组
            return [];
        }
    }

    /**
     * 检查是否应该跳过登录验证
     * 
     * @param Request $request 请求对象
     * @return bool 是否应该跳过登录验证
     */
    protected function shouldSkipLogin(Request $request): bool
    {
        $properties = $this->getControllerProperties($request);
        
        // 检查是否存在 noNeedLogin 属性且包含当前方法
        $noNeedLogin = $properties['noNeedLogin'] ?? [];
        
        $action = $request->action ?? null;
        if (!$action) {
            return false; // 默认不跳过
        }
        
        return in_array($action, $noNeedLogin);
    }

    /**
     * 检查是否应该跳过权限验证
     * 
     * @param Request $request 请求对象
     * @return bool 是否应该跳过权限验证
     */
    protected function shouldSkipPermission(Request $request): bool
    {
        $properties = $this->getControllerProperties($request);
        
        // 检查是否存在 noNeedAuth 属性且包含当前方法
        $noNeedAuth = $properties['noNeedAuth'] ?? [];
        
        $action = $request->action ?? null;
        if (!$action) {
            return false; // 默认不跳过
        }
        
        return in_array($action, $noNeedAuth);
    }

    /**
     * 检查路由权限
     * 
     * @param Request $request 请求对象
     * @return bool 是否有路由权限
     */
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

    /**
     * 路径格式化
     * 
     * @param string $path 路径
     * @return string 格式化后的路径
     */
    private function pathFormatting(string $path): string
    {
        $prefix = trim(Admin::warmConfig('app.route.prefix'), '/');

        $prefix = ($prefix === '/') ? '' : $prefix;

        $path = trim($path, '/');

        if ($path === '') {
            return $prefix ?: '/';
        }
        return $prefix . '/' . $path;
    }
}