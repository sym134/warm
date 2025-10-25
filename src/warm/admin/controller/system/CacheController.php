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
        return $this->response()->success(
            amis()->Form()->title('清除缓存')->api($this->getStorePath())
                ->mode('horizontal')
                ->body([
                    amis()->CheckboxControl('storage', '存储器')->value(1),
                ])
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
        return $this->autoResponse(1, '清理');
    }
}
