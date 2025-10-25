<?php

namespace warm\admin\controller\dev_tools;

use warm\admin\controller\AdminController;
use warm\admin\service\AdminPageService;

/**
 * 页面管理控制器
 * 
 * 负责处理自定义页面的配置和管理功能，包括：
 * 1. 页面记录的增删改查
 * 2. 页面内容的可视化编辑
 * 
 * @property AdminPageService $service 页面管理服务类实例
 */
class PagesController extends AdminController
{
    protected string $serviceName = AdminPageService::class;

    /**
     * 页面记录列表
     * 
     * 构建并返回页面记录的列表页面，包含：
     * 1. 数据表格展示页面记录
     * 2. 工具栏按钮（创建等）
     * 3. 行操作按钮（编辑、删除）
     * 
     * @return mixed 页面列表对象
     */
    public function list()
    {
        // 构建CRUD表格
        $crud = $this->baseCRUD()
            ->filterTogglable(false)
            ->headerToolbar([
                $this->createButton(true),
                ...$this->baseHeaderToolBar(),
            ])
            ->columns([
                amis()->TableColumn('id', 'ID')->sortable(),
                amis()->TableColumn('title', translator('admin.pages.title'))->searchable(),
                amis()->TableColumn('sign', translator('admin.pages.sign'))->searchable(),
                amis()->TableColumn('updated_at', translator('admin.created_at'))->type('datetime')->sortable(true),
                $this->rowActions([
                    $this->rowEditButton(true),
                    $this->rowDeleteButton(),
                ]),
            ]);

        // 返回基础列表页面
        return $this->baseList($crud);
    }

    /**
     * 页面配置表单
     * 
     * 构建用于创建和编辑页面配置的表单，包含：
     * 1. 页面标题
     * 2. 页面标识
     * 3. 页面内容（使用自定义AMIS编辑器）
     * 
     * @return mixed 表单对象
     */
    public function form()
    {
        return $this->baseForm()->body([
            // 页面标题输入框
            amis()->TextControl('title', translator('admin.pages.title'))->required(),
            // 页面标识输入框
            amis()->TextControl('sign', translator('admin.pages.sign'))->required(),
            // 页面内容配置，使用自定义AMIS编辑器
            amis()->SubFormControl('page', translator('admin.pages.page'))->form(
                amis()->Form()->className('h-full')->set('size', 'full')->title('')->body(
                    amis('custom-amis-editor')
                        ->name('schema')
                        ->label('')
                        ->mode('normal')
                        ->className('h-full')
                )
            )->required(),
        ]);
    }

    /**
     * 页面详情
     * 
     * 构建并返回页面记录的详情页面。
     * 
     * @param mixed $id 页面记录ID
     * @return mixed 详情表单对象
     */
    public function detail($id)
    {
        return $this->baseDetail()->body([]);
    }
}