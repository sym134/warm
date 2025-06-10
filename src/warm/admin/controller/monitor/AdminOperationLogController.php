<?php

namespace warm\admin\controller\monitor;

use warm\admin\controller\AdminController;
use warm\admin\renderer\Form;
use warm\admin\renderer\Page;
use warm\admin\service\monitor\AdminOperationLogService;

class AdminOperationLogController extends AdminController
{
    protected string $serviceName = AdminOperationLogService::class;

    public function list(): Page
    {
        $crud = $this->baseCRUD()
            ->headerToolbar([
                ...$this->baseHeaderToolBar(),
            ])
            ->filterDefaultVisible(true)
            ->filter(
                $this->baseFilter()->body([
                    amis()->TextControl('username', translator('admin.admin_operation_log.username'))
                        ->size('md'),
                    amis()->TextControl('service_name', translator('admin.admin_operation_log.service_name'))
                        ->size('md'),
                    amis()->TextControl('ip', translator('admin.admin_operation_log.ip')),
                    amis()->InputDatetimeRange()->name('created_at')->label(translator('admin.created_at'))
                        ->valueFormat('YYYY-MM-DD HH:mm:ss'),
                ])
            )
            ->columns([
                amis()->TableColumn('id', 'ID'),
                amis()->TableColumn('username', translator('admin.admin_operation_log.username')),
                amis()->TableColumn('app', translator('admin.admin_operation_log.app')),
                amis()->TableColumn('service_name', translator('admin.admin_operation_log.service_name')),
                amis()->TableColumn('router', translator('admin.admin_operation_log.router')),
                amis()->TableColumn('ip', translator('admin.admin_operation_log.ip')),
                amis()->TableColumn('ip_location', translator('admin.admin_operation_log.ip_location')),
                amis()->TableColumn('created_at', translator('admin.created_at'))->type('datetime')->sortable(true),


                // $this->rowActions([]),
            ]);

        return $this->baseList($crud);
    }

    public function form(): Form
    {
        return $this->baseForm()
            ->body();
    }
}
