<?php

namespace warm\admin\support\code_generator;

use warm\admin\service\AdminMenuService;

/**
 * 路由生成器
 * 
 * 用于生成和处理路由相关功能，包括菜单创建和路由刷新
 */
class RouteGenerator
{
    /**
     * 处理路由生成
     *
     * 根据菜单信息创建菜单项并生成路由
     *
     * @param array $menuInfo 菜单信息
     * @return string 路由文件路径
     * @throws \Exception
     */
    public static function handle(array $menuInfo): string
    {
        if (!$menuInfo['enabled']) {
            return '';
        }

        // 创建菜单
        $adminMenuService = AdminMenuService::make();

        $_url = ltrim($menuInfo['route'], '');
        if (!$adminMenuService->getModel()->query()->where('url', $_url)->exists()) {
               $adminMenuService->store([
                'title'        => $menuInfo['title'],
                'icon'         => $menuInfo['icon'],
                'parent_id'    => $menuInfo['parent_id'],
                'url'          => $_url,
                'order' => 100,
            ]);
        }

        if ($adminMenuService->hasError()) {
            abort(500, $adminMenuService->getError());
        }
        return runCommand('warm:gen-route')['output'];
    }

    /**
     * 刷新路由
     * 
     * 重新生成路由文件
     * 
     * @return void
     */
    public static function refresh(): void
    {
        runCommand('warm:gen-route');
    }
}