<?php

use warm\admin\Admin;
use warm\admin\controller\AdminMenuController;
use warm\admin\controller\AdminPermissionController;
use warm\admin\controller\AdminRoleController;
use warm\admin\controller\AdminUserController;
use warm\admin\controller\AuthController;
use warm\admin\controller\dev_tools\ApiController;
use warm\admin\controller\dev_tools\CodeGeneratorController;
use warm\admin\controller\dev_tools\EditorController;
use warm\admin\controller\dev_tools\PagesController;
use warm\admin\controller\dev_tools\PluginController;
use warm\admin\controller\dev_tools\RelationshipController;
use warm\admin\controller\HomeController;
use warm\admin\controller\IndexController;
use warm\admin\controller\monitor\AdminLoginLogController;
use warm\admin\controller\monitor\AdminOperationLogController;
use warm\admin\controller\notice\SmsConfigController;
use warm\admin\controller\notice\WechatOfficialAccountConfigController;
use warm\admin\controller\notice\WechatMiniProgramConfigController;
use warm\admin\controller\system\SystemCrontabController;
use warm\admin\controller\system\SystemCrontabLogController;
use warm\admin\controller\system\CacheController;
use warm\admin\controller\system\SystemFileController;
use warm\admin\controller\system\SystemStorageController;
use warm\admin\controller\system\WechatMenuController;
use warm\admin\controller\system\WechatReplyController;
use warm\admin\controller\system\PaymentConfigController;
use Webman\Route;

/**
 * Warm Admin 路由配置文件
 *
 * 定义系统的所有路由规则，包括认证路由、系统管理路由、
 * 开发工具路由等。使用 Webman 的路由系统进行配置。
 */

// 首页路由
Route::get('/admin', fn() => Admin::view());

// 后台路由组
Route::group(Admin::warmConfig('app.route.prefix'), function () {

    // 认证相关路由
    Route::get('/login', [AuthController::class, 'loginPage']);
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/logout', [AuthController::class, 'logout']);
    Route::get('/captcha', [AuthController::class, 'reloadCaptcha']);
    Route::get('/current-user', [AuthController::class, 'currentUser']);

    // 基础路由
    Route::get('/menus', [IndexController::class, 'menus']);
    Route::get('/_settings', [IndexController::class, 'settings']);
    Route::post('/_settings', [IndexController::class, 'saveSettings']);
    Route::get('/no-content', [IndexController::class, 'noContentResponse']);
    Route::get('/_download_export', [IndexController::class, 'downloadExport']);
    Route::get('/_iconify_search', [IndexController::class, 'iconifySearch']);
    Route::get('/page_schema', [IndexController::class, 'pageSchema']);

    // 文件上传路由
    Route::any('/upload_file', [IndexController::class, 'uploadFile']);
    Route::any('/upload_chunk_start', [IndexController::class, 'chunkUploadStart']);
    Route::any('/upload_chunk', [IndexController::class, 'chunkUpload']);
    Route::any('/upload_chunk_finish', [IndexController::class, 'chunkUploadFinish']);
    Route::any('/upload_rich', [IndexController::class, 'uploadRich']);
    Route::any('/upload_image', [IndexController::class, 'uploadImage']);

    // 用户设置路由
    Route::get('/user_setting', [AuthController::class, 'userSetting']);
    Route::put('/user_setting', [AuthController::class, 'saveUserSetting']);

    // 首页路由
    Route::resource('/dashboard', HomeController::class);

    // 系统管理路由组
    Route::group('/system', function () {
        Route::get('/', [AdminUserController::class, 'index']);

        // 管理员用户管理
        Route::resource('/admin_users', AdminUserController::class);
        // 缓存管理
        Route::resource('/cache', CacheController::class);
        // 菜单管理
        Route::post('/admin_menus/save_order', [AdminMenuController::class, 'saveOrder']);
        Route::resource('/admin_menus', AdminMenuController::class);
        // 角色管理
        Route::resource('/admin_roles', AdminRoleController::class);
        // 权限管理
        Route::resource('/admin_permissions', AdminPermissionController::class);

        // 角色权限保存
        Route::post('/admin_role_save_permissions', [AdminRoleController::class, 'savePermissions']);
        // 权限自动生成
        Route::post('/_admin_permissions_auto_generate', [AdminPermissionController::class, 'autoGenerate']);

        // 定时任务管理
        Route::resource('/crontab', SystemCrontabController::class);
        Route::get('/crontab_run', [SystemCrontabController::class, 'run']);
        Route::resource('/crontab_log', SystemCrontabLogController::class);

        // 支付配置
        Route::get('/payment_config', [PaymentConfigController::class, 'list']);
        Route::get('/payment_config/getData', [PaymentConfigController::class, 'getData']);
        Route::put('/payment_config/update', [PaymentConfigController::class, 'update']);

        // 存储管理
        Route::get('/storage', [SystemStorageController::class,'index']);
        Route::put('/storage/update', [SystemStorageController::class,'updateConfig']);
        
        // 文件管理 - 先定义静态路由，避免被 resource 的动态路由遮蔽
        Route::group('/file', function () {
            // 获取文件分组列表
            Route::get('/groups', [SystemFileController::class, 'groups']);
            // 文件上传
            Route::post('/upload', [SystemFileController::class, 'upload']);
            // 文件下载
            Route::get('/download', [SystemFileController::class, 'download']);
            // 文件重命名
            Route::post('/rename', [SystemFileController::class, 'rename']);
            // 文件移动
            Route::post('/move', [SystemFileController::class, 'move']);
            // 创建分组
            Route::post('/createGroup', [SystemFileController::class, 'createGroup']);
            // 重命名分组
            Route::post('/renameGroup', [SystemFileController::class, 'renameGroup']);
            // 删除分组
            Route::delete('/deleteGroup', [SystemFileController::class, 'deleteGroup']);
        });
        // 文件管理资源路由（必须放在静态路由之后）
        Route::resource('/file', SystemFileController::class);

        // 微信菜单管理
        Route::get('/wechat_menu', [WechatMenuController::class, 'index']);
        Route::get('/wechat_menu/parent_options', [WechatMenuController::class, 'parentOptions']);
        Route::post('/wechat_menu', [WechatMenuController::class, 'store']);
        Route::put('/wechat_menu/{id}', [WechatMenuController::class, 'update']);
        Route::delete('/wechat_menu/{id}', [WechatMenuController::class, 'destroy']);
        Route::post('/wechat_menu/publish', [WechatMenuController::class, 'publish']);
    });

    Route::group('/setting', function () {
        Route::group('/other_config', function () {
            Route::group('/notice',function (){
                Route::resource('/n', \warm\admin\controller\notice\NoticeConfigController::class);
            });
            // 短信配置
            Route::group('/sms', function () {
                Route::resource('/sms_config', SmsConfigController::class);

                // 短信配置
                Route::get('/index', [SmsConfigController::class, 'gatewayForm']);
                // 网关表单
                Route::get('/gateway_form', [SmsConfigController::class, 'gatewayForm']);
                // 网关保存
                Route::post('/save_gateway', [SmsConfigController::class, 'saveGateway']);
                // 短信配置保存
                Route::put('/save', [SmsConfigController::class, 'save']);
            });
        });

    });

    // 应用设置路由组
        Route::group('/app', function () {
        Route::group('/wechat',function (){
            // 微信回复管理
            Route::get('/reply/{key}', [WechatReplyController::class, 'reply']);
            Route::get('/get_reply', [WechatReplyController::class, 'getReply']);
            // 保存关注回复、默认回复
            Route::post('/save_reply', [WechatReplyController::class, 'saveReply']);
            Route::resource('/keyword', WechatReplyController::class);

            // 微信小程序配置
            Route::get('/mini_program_config', [WechatMiniProgramConfigController::class, 'index']);
            Route::post('/mini_program_config/save', [WechatMiniProgramConfigController::class, 'save']);
            // 微信公众号配置
            Route::get('/official_account_config', [WechatOfficialAccountConfigController::class, 'index']);
            Route::post('/official_account_config/save', [WechatOfficialAccountConfigController::class, 'save']);
            Route::post('/official_account_config/upload', [WechatOfficialAccountConfigController::class, 'upload']);
        });



    });

    // 日志监控路由组
    Route::group('/log_monitoring', function () {
        // 登录日志
        Route::resource('/admin_login_log', AdminLoginLogController::class);
        // 操作日志
        Route::resource('/admin_operation_log', AdminOperationLogController::class);
    });

    // 开发者工具（仅在启用时加载）
    if (Admin::warmConfig('app.show_development_tools')) {
        Route::group('/dev_tools', function () {
            // 代码生成器
            Route::resource('/code_generator', CodeGeneratorController::class);
            Route::group('/code_generator', function () {
                Route::post('/preview', [CodeGeneratorController::class, 'preview']);
                Route::post('/generate', [CodeGeneratorController::class, 'generate']);
                Route::post('/clear', [CodeGeneratorController::class, 'clear']);
                Route::post('/clone', [CodeGeneratorController::class, 'clone']);
                Route::post('/gen_record_options', [CodeGeneratorController::class, 'genRecordOptions']);
                Route::post('/form_data', [CodeGeneratorController::class, 'formData']);
                Route::post('/get_record', [CodeGeneratorController::class, 'getRecord']);
                Route::post('/get_property_options', [CodeGeneratorController::class, 'getPropertyOptions']);

                // 组件属性管理
                Route::group('/component_property', function () {
                    Route::post('/save', [CodeGeneratorController::class, 'saveComponentProperty']);
                    Route::post('/list', [CodeGeneratorController::class, 'getComponentProperty']);
                    Route::post('/del', [CodeGeneratorController::class, 'delComponentProperty']);
                });

                // 通用字段管理
                Route::group('/common_field', function () {
                    Route::post('/save', [CodeGeneratorController::class, 'saveColumnProperty']);
                    Route::post('/list', [CodeGeneratorController::class, 'getColumnProperty']);
                    Route::post('/del', [CodeGeneratorController::class, 'delColumnProperty']);
                });
            });

            // 插件管理
            Route::resource('/plugin', PluginController::class);
            Route::group('/plugin', function () {
                Route::post('/enable', [PluginController::class, 'enable']);
                Route::post('/install', [PluginController::class, 'install']);
                Route::post('/uninstall', [PluginController::class, 'uninstall']);
                Route::post('/get_config', [PluginController::class, 'getConfig']);
                Route::post('/save_config', [PluginController::class, 'saveConfig']);
                Route::post('/config_form', [PluginController::class, 'configForm']);
            });

            // 可视化编辑器
            Route::post('/editor_parse', [EditorController::class, 'index']);

            // 页面管理
            Route::resource('/pages', PagesController::class);

            // 关联关系管理
            Route::resource('/relationships', RelationshipController::class);
            Route::group('/relation', function () {
                Route::get('/model_options', [RelationshipController::class, 'modelOptions']);
                Route::get('/column_options', [RelationshipController::class, 'columnOptions']);
                Route::get('/all_models', [RelationshipController::class, 'allModels']);
                Route::post('/generate_model', [RelationshipController::class, 'generateModel']);
            });

            // API管理
            Route::resource('/apis', ApiController::class);
            Route::group('/api', function () {
                Route::get('/templates', [ApiController::class, 'template']);
                Route::get('/args_schema', [ApiController::class, 'argsSchema']);
                Route::post('/add_template', [ApiController::class, 'addTemplate']);
            });
        });
    }
})->middleware(Admin::middleware());

// 微信消息接收路由（公共接口，不需要登录验证）
Route::any('/wechat/message', [\warm\common\controller\WechatMessageController::class, 'handle']);

// 加载应用下的路由配置
require_once config_path('plugin/jizhi/warm/route/autoRoute.php');