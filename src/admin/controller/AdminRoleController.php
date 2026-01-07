<?php

namespace warm\admin\controller;

use support\Response;
use warm\admin\renderer\DrawerAction;
use warm\admin\renderer\Form;
use warm\admin\renderer\InputTree;
use warm\admin\renderer\Page;
use warm\admin\service\AdminPermissionService;
use warm\admin\service\AdminRoleService;

/**
 * 管理员角色控制器
 * 
 * 用于管理系统中管理员角色的增删改查操作
 * 提供角色权限分配功能
 * 
 * @property AdminRoleService $service 管理员角色服务类实例
 */
class AdminRoleController extends AdminController
{
    /**
     * @var string $serviceName 服务类名称
     * 指定当前控制器使用的服务类
     */
    protected string $serviceName = AdminRoleService::class;

    /**
     * 角色列表页面
     * 
     * 展示管理员角色列表，支持排序和权限分配
     * 
     * @return Page 返回角色列表页面
     */
    public function list(): Page
    {
        $crud = $this->baseCRUD()
            ->headerToolbar([
                $this->createButton(true),
                ...$this->baseHeaderToolBar(),
            ])
            ->filterTogglable(false)
            ->itemCheckableOn('${slug !== "administrator"}')
            ->columns([
                amis()->TableColumn()->label('ID')->name('id')->sortable(),
                amis()->TableColumn()->label(translator('admin.admin_role.name'))->name('name'),
                amis()->TableColumn()->label(translator('admin.admin_role.slug'))->name('slug')->type('tag'),
                amis()->TableColumn()
                    ->label(translator('admin.created_at'))
                    ->name('created_at')
                    ->type('datetime')
                    ->sortable(),
                amis()->TableColumn()
                    ->label(translator('admin.updated_at'))
                    ->name('updated_at')
                    ->type('datetime')
                    ->sortable(),
                $this->rowActions([
                    $this->setPermission()->hiddenOn('${slug == "administrator"}'),
                    $this->rowEditButton(true),
                    $this->rowDeleteButton()->hiddenOn('${slug == "administrator"}'),
                ]),
            ]);

        return $this->baseList($crud)->css([
            '.tree-full'                   => [
                'overflow' => 'hidden !important',
            ],
            '.cxd-TreeControl > .cxd-Tree' => [
                'height'     => '100% !important',
                'max-height' => '100% !important',
            ],
        ]);
    }

    /**
     * 设置权限操作
     * 
     * 提供一个抽屉式界面用于为角色分配权限
     * 
     * @return DrawerAction 返回抽屉操作组件
     */
    protected function setPermission(): DrawerAction
    {
        return amis()->DrawerAction()
            ->label(translator('admin.admin_role.set_permissions'))
            ->icon('fa-solid fa-gear')
            ->level('link')
            ->drawer(
                amis()->Drawer()
                    ->title(translator('admin.admin_role.set_permissions'))
                    ->resizable()
                    ->closeOnOutside()
                    ->closeOnEsc()
                    ->body([
                        amis()->Form()
                            ->api(admin_url('system/admin_role_save_permissions'))
                            ->initApi($this->getEditGetDataPath())
                            ->mode('normal')
                            ->data(['id' => '${id}'])
                            ->body([
                                amis()->InputTree()
                                    ->name('permissions')
                                    ->label()
                                    ->multiple()
                                    ->heightAuto()
                                    ->options(AdminPermissionService::make()->getTree())
                                    ->searchable()
                                    ->cascade()
                                    ->joinValues(false)
                                    ->extractValue()
                                    ->size('full')
                                    ->className('h-full b-none')
                                    ->inputClassName('h-full tree-full')
                                    ->labelField('name')
                                    ->valueField('id'),
                            ]),
                    ])
            );
    }

    /**
     * 保存权限设置
     * 
     * 处理角色权限分配的保存操作
     * 
     * @return Response 返回操作结果响应
     */
    public function savePermissions(): Response
    {
        $result = $this->service->savePermissions(request()->input('id'), request()->input('permissions'));

        return $this->autoResponse($result, translator('admin.save'));
    }

    /**
     * 角色表单页面
     * 
     * 定义角色新增/编辑表单结构，包含名称和标识字段
     * 
     * @return Form 返回角色表单
     */
    public function form(): Form
    {
        return $this->baseForm()->body([
            amis()->InputText()->label(translator('admin.admin_role.name'))->name('name')->required(),
            amis()->InputText()
                ->label(translator('admin.admin_role.slug'))
                ->name('slug')
                ->description(translator('admin.admin_role.slug_description'))
                ->required(),
        ]);
    }

    /**
     * 角色详情页面
     * 
     * 展示角色详细信息
     * 
     * @return Form 返回角色详情表单
     */
    public function detail(): Form
    {
        return $this->baseDetail()->body([]);
    }
}
