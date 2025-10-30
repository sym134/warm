<?php

use warm\admin\model\AdminMenu;
use warm\admin\model\AdminRole;
use warm\admin\model\AdminPermission;
use warm\admin\model\AdminUser;

/**
 * Warm Admin 应用配置文件
 * 
 * 包含 Warm Admin 系统的核心配置项，如应用基本信息、路由配置、认证配置、
 * 数据库配置、模型配置等。
 */
return [
    // 是否启用应用
    'enable' => true,

    // 应用名称
    'name'           => 'warm',

    // 应用 logo
    'logo'           => '/admin-assets/logo.png',

    // 默认头像
    'default_avatar' => '/admin-assets/default-avatar.png',

    // 应用安装目录
    'directory'      => app_path('admin'),

    // 应用路由配置
    'route'          => [
        // 路由前缀
        'prefix'               => '/admin-api',
        // 路由域名
        'domain'               => null,
        // 控制器命名空间
        'namespace'            => 'app\\admin\\controller',
        // 中间件
        'middleware'           => ['admin'],
        // 不包含额外路由, 配置后, 不会追加新增/详情/编辑页面路由
        'without_extra_routes' => [
            '/dashboard',
        ],
    ],

    // 认证配置
    'auth' => [
        // 是否开启验证码
        'login_captcha' => env('ADMIN_LOGIN_CAPTCHA', true),
        // 是否开启认证
        'enable'        => true,
        // 是否开启鉴权
        'permission'    => true,
        // 认证守卫
        'guard'         => 'admin',
        // 认证排除路径
        'except'        => [

        ],
    ],

    // 是否启用 HTTPS
    'https'                                => env('ADMIN_HTTPS', false),

    // 是否显示 [开发者工具]
    'show_development_tools'               => env('ADMIN_SHOW_DEVELOPMENT_TOOLS', true),

    // 是否显示 [权限] 功能中的自动生成按钮
    'show_auto_generate_permission_button' => env('ADMIN_SHOW_AUTO_GENERATE_PERMISSION_BUTTON', true),

    // 插件配置
    'plugin'                            => [
        // 插件目录
        'dir' => base_path('plugin'),
    ],

    // 布局配置
    'layout' => [
        // 浏览器标题, 功能名称使用 %title% 代替
        'title'              => '%title% | warm',
        // 头部配置
        'header'             => [
            // 是否显示 [刷新] 按钮
            'refresh'       => true,
            // 是否显示 [暗色模式] 按钮
            'dark'          => true,
            // 是否显示 [全屏] 按钮
            'full_screen'   => true,
            // 是否显示 [多语言] 按钮
            'locale_toggle' => true,
            // 是否显示 [主题配置] 按钮
            'theme_config'  => true,
        ],
        // 多语言选项
        'locale_options'     => [
            'en'    => 'English',
            'zh_CN' => '简体中文',
        ],
        /*
         * keep_alive 页面缓存黑名单
         *
         * eg:
         * 列表: /user
         * 详情: /user/:id
         * 编辑: /user/:id/edit
         * 新增: /user/create
         */
        'keep_alive_exclude' => [],
        // 底部信息
        'footer'             => '<a href="" target="_blank">warm</a>',
    ],

    // 数据库配置
    'database' => [
        // 数据库连接
        'connection' => env('DB_CONNECTION', 'mysql'),
    ],

    // 模型配置
    'models' => [
        // 管理员用户模型
        'admin_user'       => AdminUser::class,
        // 管理员角色模型
        'admin_role'       => AdminRole::class,
        // 管理员菜单模型
        'admin_menu'       => AdminMenu::class,
        // 管理员权限模型
        'admin_permission' => AdminPermission::class,
    ],

    // 模块配置
    'modules' => [
    ],
];