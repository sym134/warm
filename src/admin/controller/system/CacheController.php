<?php

namespace warm\admin\controller\system;

use support\Request;
use support\Response;
use warm\admin\controller\AdminController;
use warm\admin\service\system\CacheService;

/**
 * 缓存控制器
 * 
 * 用于管理系统缓存的清理操作
 * 提供缓存清理表单和执行功能
 */
class CacheController extends AdminController
{
    /**
     * 缓存清理表单页面
     * 
     * 展示缓存清理选项表单
     * 
     * @return Response 返回缓存清理表单页面
     */
    public function index(): Response
    {
        // 获取可清理的缓存类别
        $categories = CacheService::getClearableCategories();
        
        // 构建表单选项
        $checkboxes = [];
        foreach ($categories as $key => $category) {
            $checkboxes[] = amis()
                ->Checkbox($key, $category['name'])
                ->value(0)
                ->description($category['description']);
        }
        
        // 添加说明文字
        $description = amis()
            ->Alert()
            ->level('info')
            ->body(translator('system.cache.description', [
                'system' => translator('system.cache.system'),
                'wechat' => translator('system.cache.wechat'),
                'payment' => translator('system.cache.payment'),
                'all' => translator('system.cache.all')
            ]));
        
        // 将说明文字添加到表单顶部
        array_unshift($checkboxes, $description);
        
        return $this->response()->success(
            amis()->Form()
                ->title(translator('system.cache.title'))
                ->api($this->getStorePath())
                ->mode('horizontal')
                ->body($checkboxes)
        );
    }

    /**
     * 执行缓存清理
     * 
     * 根据用户选择的选项执行缓存清理操作
     * 
     * @param Request $request HTTP请求对象
     * @return Response 返回操作结果响应
     */
    public function store(Request $request): Response
    {
        CacheService::clear($request->all());
        return $this->autoResponse(1, translator('system.cache.clear_success'));
    }
}
