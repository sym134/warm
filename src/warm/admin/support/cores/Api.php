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

// todo 导入api模板
class Api
{
    public static function boot(): void
    {
        appw('admin.context')->set('apis', [
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

    public static function path($file = ''): string
    {
        return app_path('/ApiTemplates') . ($file ? '/' . ltrim($file, '/') : '');
    }
}
