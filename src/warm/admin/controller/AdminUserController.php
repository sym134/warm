<?php

namespace warm\admin\controller;

use warm\admin\renderer\Form;
use warm\admin\renderer\Page;
use warm\admin\service\AdminRoleService;
use warm\admin\service\AdminUserService;

/**
 * 管理员用户控制器
 * 
 * 用于管理后台管理员用户的增删改查操作
 * 继承自AdminController，提供完整的用户管理功能
 * 
 * @property AdminUserService $service 管理员用户服务类实例
 */
class AdminUserController extends AdminController
{
    /**
     * @var string $serviceName 服务类名称
     * 指定当前控制器使用的服务类
     */
    protected string $serviceName = AdminUserService::class;

    /**
     * 用户列表页面
     * 
     * 展示管理员用户列表，支持搜索、排序、快速编辑等功能
     * 
     * @return Page 返回用户列表页面
     */
    public function list(): Page
    {
        $crud = $this->baseCRUD()
            ->headerToolbar([
                $this->createButton(true),
                ...$this->baseHeaderToolBar(),
            ])
            ->filter($this->baseFilter()->body(
                amis()->TextControl('keyword', translator('admin.keyword'))
                    ->size('md')
                    ->placeholder(translator('admin.admin_user.search_username'))
            ))
            ->itemCheckableOn('${id != 1}')
            ->columns([
                amis()->TableColumn('id', 'ID')->sortable(),
                amis()->TableColumn('avatar', translator('admin.admin_user.avatar'))->type('avatar')->src('${avatar}'),
                amis()->TableColumn('username', translator('admin.username')),
                amis()->TableColumn('name', translator('admin.admin_user.name')),
                amis()->TableColumn('roles', translator('admin.admin_user.roles'))->type('each')->items(
                    amis()->Tag()->label('${name}')->className('my-1')
                ),
                amis()->TableColumn('enabled', translator('admin.extensions.card.status'))->quickEdit(
                    amis()->SwitchControl()->mode('inline')->disabledOn('${id == 1}')->saveImmediately(true)
                ),
                amis()->TableColumn('created_at', translator('admin.created_at'))->type('datetime')->sortable(),
                $this->rowActions([
                    $this->rowEditButton(true)
                        ->hiddenOn('${administrator && ' . !admin_user()->isAdministrator() . '}'),
                    $this->rowDeleteButton()->hiddenOn('${id == 1}'),
                ]),
            ]);

        return $this->baseList($crud);
    }

    /**
     * 用户表单页面
     * 
     * 定义用户新增/编辑表单结构，包含头像、用户名、姓名、密码等字段
     * 
     * @return Form 返回用户表单
     */
    public function form(): Form
    {
        return $this->baseForm()->body([
            amis()->ImageControl('avatar', translator('admin.admin_user.avatar'))->receiver($this->uploadImagePath()),
            amis()->TextControl('username', translator('admin.username'))->required(),
            amis()->TextControl('name', translator('admin.admin_user.name'))->required(),
            amis()->TextControl('password', translator('admin.password'))->type('input-password'),
            amis()->TextControl('confirm_password', translator('admin.confirm_password'))->type('input-password'),
            amis()->SelectControl('roles', translator('admin.admin_user.roles'))
                ->searchable()
                ->multiple()
                ->labelField('name')
                ->valueField('id')
                ->joinValues(false)
                ->extractValue()
                ->disabledOn('${id == 1}')
                ->options(AdminRoleService::make()->query()->get(['id', 'name'])),
            amis()->SwitchControl('enabled', translator('admin.extensions.card.status'))
                ->onText(translator('admin.extensions.enable'))
                ->offText(translator('admin.extensions.disable'))
                ->disabledOn('${id == 1}')
                ->value(1),
        ]);
    }

    /**
     * 用户详情页面
     * 
     * 展示用户详细信息
     * 
     * @return Form 返回用户详情表单
     */
    public function detail(): Form
    {
        return $this->baseDetail()->body([]);
    }
}
