<?php

namespace warm\admin\support\cores;

use warm\admin\Admin;
use warm\admin\support\apis\DataCreateApi;
use warm\admin\support\apis\DataDeleteApi;
use warm\admin\support\apis\DataDetailApi;
use warm\admin\support\apis\DataListApi;
use warm\admin\support\apis\DataUpdateApi;
use warm\admin\support\apis\GetSettingsApi;
use warm\admin\support\apis\OptionsApi;
use warm\admin\support\apis\SaveSettingsApi;

/**
 * API管理类
 * 
 * 用于管理系统中的API模板，包括内置API和自定义API模板
 * 负责加载和注册系统可用的API接口模板
 */
class Api
{
    /**
     * 启动API管理器
     * 
     * 注册系统内置API模板，并尝试加载自定义API模板
     * 
     * @return void
     */
    public static function boot(): void
    {
        Admin::context()->set('apis', [
            DataListApi::class,
            DataCreateApi::class,
            DataDetailApi::class,
            DataDeleteApi::class,
            DataUpdateApi::class,
            OptionsApi::class,
            GetSettingsApi::class,
            SaveSettingsApi::class,
        ]);

        if (!is_dir(self::path()))  return;

        collect(scandir(app_path('/ApiTemplates')))
            ->filter(fn($file) => !in_array($file, ['.', '..']) && str_ends_with($file, '.php'))
            ->each(function ($file) {
                $class = 'App\\ApiTemplates\\' . str_replace('.php', '', $file);
                try {
                    if (class_exists($class)) {
                        Admin::context()->add('apis', $class);
                    }
                } catch (\Throwable $e) {
                }
            });
    }

    /**
     * 获取API模板路径
     * 
     * @param string $file 文件名
     * @return string 完整路径
     */
    public static function path(string $file = ''): string
    {
        return app_path('/ApiTemplates') . ($file ? '/' . ltrim($file, '/') : '');
    }
}