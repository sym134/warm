<?php

namespace warm\admin;

use support\Db;
use warm\admin\model\{AdminPermission};
use warm\admin\middleware;
use warm\admin\model\AdminMenu;
use warm\admin\model\AdminRole;
use warm\admin\model\AdminUser;
use warm\admin\support\cores\Permission;
use warm\admin\trait\AssetsTrait;
use warm\common\service\ConfigService;
use warm\admin\support\cores\Context;
use warm\admin\support\cores\JsonResponse;
use warm\admin\support\cores\Menu;

/**
 * Class Admin
 *
 * 管理后台核心类，提供各种管理后台相关的功能和组件访问接口
 */
class Admin
{
    use AssetsTrait;

    /**
     * 创建Admin类实例
     *
     * @return static Admin类实例
     */
    public static function make(): static
    {
        return new static();
    }

    /**
     * 创建响应对象
     *
     * @return JsonResponse JSON响应对象，用于构建API返回数据
     */
    public static function response(): JsonResponse
    {
        return new JsonResponse();
    }

    /**
     * 获取菜单管理对象
     *
     * @return Menu 菜单管理对象
     */
    public static function menu(): Menu
    {
        return appw('admin.menu');
    }

    /**
     * 获取权限管理对象
     *
     * @return Permission 权限管理对象
     */
    public static function permission(): Permission
    {
        return new Permission;
    }

    /**
     * 获取认证守卫实例
     *
     * @return \WebmanAuth\Guard 认证守卫实例
     */
    public static function guard()
    {
        return \WebmanAuth\facade\Auth::guard(self::config('app.auth.guard') ?: 'admin');
    }

    /**
     * 获取当前登录用户信息
     *
     * @return AdminUser|null 管理员用户对象，未登录时返回null
     */
    public static function user(): ?AdminUser
    {
        return static::guard()->user();
    }

    /**
     * 上下文管理
     *
     * 获取上下文管理对象，用于存储和获取请求上下文数据
     *
     * @return Context 上下文管理对象
     */
    public static function context(): Context
    {
        return appw('admin.context');
    }

    /**
     * 获取配置服务对象
     *
     * @return ConfigService 配置服务对象
     */
    public static function warmConfig(): ConfigService
    {
        return appw('admin.config');
    }

    /**
     * 获取管理菜单模型类名
     *
     * @return string 管理菜单模型类名
     */
    public static function adminMenuModel(): string
    {
        return self::config('app.models.admin_menu', AdminMenu::class);
    }

    /**
     * 获取管理权限模型类名
     *
     * @return string 管理权限模型类名
     */
    public static function adminPermissionModel(): string
    {
        return self::config('app.models.admin_permission', AdminPermission::class);
    }

    /**
     * 获取管理角色模型类名
     *
     * @return string 管理角色模型类名
     */
    public static function adminRoleModel(): string
    {
        return self::config('app.models.admin_role', AdminRole::class);
    }

    /**
     * 获取管理用户模型类名
     *
     * @return string 管理用户模型类名
     */
    public static function adminUserModel(): string
    {
        return self::config('app.models.admin_user', AdminUser::class);
    }

    /**
     * 获取插件配置
     *
     * @param string $key 配置键名
     * @param mixed $default 默认值
     * @return mixed 配置值
     */
    public static function config($key, $default = '')
    {
        $key = 'plugin.jizhi.warm.' . $key;
        return config($key, $default);
    }

    /**
     * 替换后台视图API前缀
     *
     * 加载管理后台的HTML模板，并替换其中的API前缀配置
     *
     * @param string $apiPrefix API前缀
     * @return array|string|null 处理后的视图内容
     */
    public static function view($apiPrefix = ''): array|string|null
    {
        if (!$apiPrefix) {
            $apiPrefix = self::config('app.route.prefix');
        }
        
        if (is_file(public_path('admin-assets/index.html'))) {
            $view = file_get_contents(public_path('admin-assets/index.html'));
        } else {
            $view = file_get_contents(base_path('vendor/jizhi/warm/src/admin-assets/index.html'));
        }

        $script = '<script>window.$adminApiPrefix = "/' . $apiPrefix . '"</script>';

        return preg_replace('/<script>window.*?<\/script>/is', $script, $view);
    }

    /**
     * 检查数据库表是否存在
     *
     * 使用缓存优化，避免重复查询数据库
     *
     * @param string $table 表名
     * @return bool 表是否存在
     */
    public static function hasTable($table): bool
    {
        $key = 'admin_has_table_' . $table;
        if (cache()->has($key)) {
            return true;
        }

        $has = Db::schema()->hasTable($table);

        if ($has) {
            cache()->forever($key, true);
        }

        return $has;
    }

    /**
     * 中间件
     *
     * 获取管理后台需要加载的中间件列表
     *
     * @return array 中间件类列表
     *
     * Author:sym
     * Date:2024/6/18 上午7:43
     * Company:极智网络科技
     */
    public static function middleware(): array
    {
        return [
            middleware\ConnectionDatabase::class,
            middleware\ForceHttps::class,
            middleware\AutoSetLocale::class,
            middleware\Authenticate::class,
            middleware\Permission::class,
        ];
    }
}