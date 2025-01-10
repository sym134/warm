<?php

namespace warm\admin\controller;

use support\Response;
use warm\admin\service\AdminPermissionService;
use warm\admin\service\AdminRoleService;
use warm\renderer\DrawerAction;
use warm\renderer\Form;
use warm\renderer\Page;

/**
 * @property AdminRoleService $service
 */
class AdminRoleController extends AdminController
{
    protected string $serviceName = AdminRoleService::class;

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
                amis()->TableColumn()->label(admin_trans('admin.admin_role.name'))->name('name'),
                amis()->TableColumn()->label(admin_trans('admin.admin_role.slug'))->name('slug')->type('tag'),
                amis()->TableColumn()
                    ->label(admin_trans('admin.created_at'))
                    ->name('created_at')
                    ->type('datetime')
                    ->sortable(),
                amis()->TableColumn()
                    ->label(admin_trans('admin.updated_at'))
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

    protected function setPermission(): DrawerAction
    {
        return amis()->DrawerAction()
            ->label(admin_trans('admin.admin_role.set_permissions'))
            ->icon('fa-solid fa-gear')
            ->level('link')
            ->drawer(
                amis()->Drawer()
                    ->title(admin_trans('admin.admin_role.set_permissions'))
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
                                amis()->TreeControl()
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

    public function savePermissions(): Response
    {
        $result = $this->service->savePermissions(request()->input('id'), request()->input('permissions'));

        return $this->autoResponse($result, admin_trans('admin.save'));
    }

    public function form(): Form
    {
        return $this->baseForm()->body([
            amis()->TextControl()->label(admin_trans('admin.admin_role.name'))->name('name')->required(),
            amis()->TextControl()
                ->label(admin_trans('admin.admin_role.slug'))
                ->name('slug')
                ->description(admin_trans('admin.admin_role.slug_description'))
                ->required(),
        ]);
    }

    public function detail(): Form
    {
        return $this->baseDetail()->body([]);
    }
}
