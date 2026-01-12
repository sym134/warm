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
     * 获取所有API模板（内置 + 自定义）
     *
     * @return array 所有API模板类
     */
    public static function getAllApis(): array
    {
        // 内置API模板类
        $builtinApis = [
            DataListApi::class,
            DataCreateApi::class,
            DataDetailApi::class,
            DataDeleteApi::class,
            DataUpdateApi::class,
            OptionsApi::class,
            GetSettingsApi::class,
            SaveSettingsApi::class,
        ];

        // 加载自定义API模板类
        $customApis = self::loadCustomApis();

        // 合并内置和自定义API模板
        return array_merge($builtinApis, $customApis);
    }


    /**
     * 加载自定义API模板
     *
     * 读取 ApiTemplates 目录下的 PHP 文件并返回类名数组
     *
     * @return array 自定义API模板类
     */
    private static function loadCustomApis(): array
    {
        $apiTemplateDir = self::path();

        if (!is_dir($apiTemplateDir)) {
            return [];
        }

        // 获取目录下的所有PHP文件并逐一加载
        $files = collect(scandir($apiTemplateDir))
            ->filter(fn($file) => !in_array($file, ['.', '..']) && str_ends_with($file, '.php'));

        // 遍历文件，加载类并返回
        $apis = [];
        foreach ($files as $file) {
            $class = 'App\\ApiTemplates\\' . str_replace('.php', '', $file);
            if (class_exists($class)) {
                $apis[] = $class;  // 添加类到数组中
            }
        }

        return $apis;
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