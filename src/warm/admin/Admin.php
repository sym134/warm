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

class Admin
{
    use AssetsTrait;

    public static function make(): static
    {
        return new static();
    }

    public static function response(): JsonResponse
    {
        return new JsonResponse();
    }

    /**
     * @return Menu;
     */
    public static function menu(): Menu
    {
        return appw('admin.menu');
    }

    /**
     * @return Permission
     */
    public static function permission(): Permission
    {
        return new Permission;
    }

    public static function guard()
    {
        return \WebmanAuth\facade\Auth::guard(self::config('app.auth.guard') ?: 'admin');
    }

    /**
     * @return AdminUser|null
     */
    public static function user(): ?AdminUser
    {
        return static::guard()->user();
    }

    /**
     * 上下文管理.
     *
     * @return Context
     */
    public static function context(): Context
    {
        return appw('admin.context');
    }

    /**
     * @return ConfigService
     */
    public static function warmConfig(): ConfigService
    {
        return appw('admin.config');
    }

    /**
     * @return string
     */
    public static function adminMenuModel(): string
    {
        return self::config('app.models.admin_menu', AdminMenu::class);
    }

    /**
     * @return string
     */
    public static function adminPermissionModel(): string
    {
        return self::config('app.models.admin_permission', AdminPermission::class);
    }

    /**
     * @return string
     */
    public static function adminRoleModel(): string
    {
        return self::config('app.models.admin_role', AdminRole::class);
    }

    /**
     * @return string
     */
    public static function adminUserModel(): string
    {
        return self::config('app.models.admin_user', AdminUser::class);
    }

    public static function config($key, $default = '')
    {
        $key = 'plugin.jizhi.warm.' . $key;
        return config($key, $default);
    }

    // 替换后台视图api
    public static function view($apiPrefix = ''): array|string|null
    {
        if (!$apiPrefix) {
            $apiPrefix = self::config('app.route.prefix');
        }

        if (is_file(public_path('admin-assets/index.html'))) {
            $view = file_get_contents(public_path('admin-assets/index.html'));
        } else {
            $view = file_get_contents(base_path('vendor/jizhi/admin/src/admin-assets/index.html'));
        }

        $script = '<script>window.$adminApiPrefix = "/' . $apiPrefix . '"</script>';

        return preg_replace('/<script>window.*?<\/script>/is', $script, $view);
    }

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
     * @return array
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
