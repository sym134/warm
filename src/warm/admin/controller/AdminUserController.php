<?php

namespace warm\admin\controller;

use warm\admin\renderer\Form;
use warm\admin\renderer\Page;
use warm\admin\service\AdminRoleService;
use warm\admin\service\AdminUserService;

/**
 * @property AdminUserService $service
 */
class AdminUserController extends AdminController
{
    protected string $serviceName = AdminUserService::class;

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

    public function detail(): Form
    {
        return $this->baseDetail()->body([]);
    }
}
