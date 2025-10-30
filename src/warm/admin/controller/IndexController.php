<?php

namespace warm\admin\controller;

use support\Request;
use support\Response;
use warm\admin\Admin;
use warm\admin\model\AdminPlugin;
use warm\admin\service\AdminPageService;

/**
 * 索引控制器类
 * 
 * 提供系统基础功能接口，如菜单获取、设置管理、图标搜索等
 */
class IndexController extends AdminController
{
    /**
     * 获取菜单列表
     * 
     * 返回系统菜单结构数据
     * 
     * @return Response 菜单数据响应
     */
    public function menus(): Response
    {
        return $this->response()->success(Admin::menu()->all());
    }

    /**
     * 空内容响应
     * 
     * 返回空内容的成功响应
     * 
     * @return Response 空内容响应
     */
    public function noContentResponse(): Response
    {
        return $this->response()->successMessage();
    }

    /**
     * 获取系统设置
     * 
     * 返回系统配置信息，包括导航、资源、语言设置等
     * 
     * @return Response 系统设置响应
     */
    public function settings(): Response
    {
        $prefix = '';
        // 默认语言选项
        $localeOptions = Admin::warmConfig('app.layout.locale_options') ?? [
            'en' => 'English',
            'zh_CN' => '简体中文',
        ];
        
        // 返回系统设置数据
        return $this->response()->success([
            'nav' => Admin::getNav(),
            'assets' => Admin::getAssets(),
            'app_name' => Admin::warmConfig('app.name'),
            'locale' => systemConfig()->get('admin_locale', Admin::warmConfig('app.translation.local')),
            'layout' => Admin::warmConfig('app.layout'),
            'logo' => url(Admin::warmConfig('app.logo')),

            'login_captcha' => Admin::warmConfig('app.auth.login_captcha'),
            'locale_options' => map2options($localeOptions),
            'show_development_tools' => Admin::warmConfig('app.show_development_tools'),
            'system_theme_setting' => Admin::config()->get($prefix . 'system_theme_setting'),
            'enabled_extensions' => AdminPlugin::query()->where('is_enabled', 1)->pluck('key')?->toArray(),
        ]);
    }

    /**
     * 保存设置项
     *
     * 保存用户提交的系统设置
     *
     * @param Request $request HTTP请求对象
     * @return Response 保存结果响应
     */
    public function saveSettings(Request $request): Response
    {
        // 获取所有请求数据
        $data = $request->all();
        
        // 批量保存设置
        Admin::config()->setMany($data);
        
        // 返回成功响应
        return $this->response()->successMessage();
    }

    /**
     * 下载导出文件
     *
     * 提供文件下载功能
     *
     * @param Request $request HTTP请求对象
     * @return Response 文件下载响应
     */
    public function downloadExport(Request $request): Response
    {
        // 获取文件路径信息
        $pathInfo = pathinfo($request->input('path'));
        $downloadName = $pathInfo['basename'] ?? '';
        
        // 返回文件下载响应
        return response()->download(base_path($request->input('path')), $downloadName);
    }

    /**
     * 图标搜索
     *
     * 根据关键词搜索Iconify图标
     *
     * @return Response 图标搜索结果响应
     */
    public function iconifySearch(): Response
    {
        // 获取搜索关键词，默认为'home'
        $query = request()->input('query', 'home');

        // 读取图标数据文件
        $icons = file_get_contents(admin_path('/support/iconify.json'));
        $icons = json_decode($icons, true);

        // 筛选匹配的图标
        $items = [];
        foreach ($icons as $item) {
            if (str_contains($item, $query)) {
                $items[] = ['icon' => $item];
            }
            // 限制返回结果数量
            if (count($items) > 999) {
                break;
            }
        }

        // 计算总数
        $total = count($items);

        // 返回搜索结果
        return $this->response()->success(compact('items', 'total'));
    }

    /**
     * 获取页面结构
     *
     * 根据标识符获取页面结构数据
     *
     * @return Response 页面结构响应
     */
    public function pageSchema(): Response
    {
        // 通过页面服务获取指定标识符的页面结构
        return $this->response()->success(AdminPageService::make()->get(request()->get('sign')));
    }
}