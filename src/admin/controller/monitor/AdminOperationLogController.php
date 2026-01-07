<?php

namespace warm\admin\controller\monitor;

use warm\admin\controller\AdminController;
use warm\admin\renderer\Form;
use warm\admin\renderer\Page;
use warm\admin\service\monitor\AdminOperationLogService;

/**
 * 管理员操作日志控制器
 * 
 * 用于查看和管理后台管理员的操作日志信息
 * 提供操作日志列表查看和筛选功能
 */
class AdminOperationLogController extends AdminController
{
    /**
     * @var string $serviceName 服务类名称
     * 指定当前控制器使用的服务类
     */
    protected string $serviceName = AdminOperationLogService::class;

    /**
     * 操作日志列表页面
     * 
     * 展示管理员操作日志列表，支持按用户名、服务名称、IP等条件筛选
     * 
     * @return Page 返回操作日志列表页面
     */
    public function list(): Page
    {
        $crud = $this->baseCRUD()
            ->headerToolbar([
                ...$this->baseHeaderToolBar(),
            ])
            ->filterDefaultVisible(true)
            ->filter(
                $this->baseFilter()->body([
                    amis()->InputText('username', translator('admin.admin_operation_log.username'))
                        ->size('md'),
                    amis()->InputText('service_name', translator('admin.admin_operation_log.service_name'))
                        ->size('md'),
                    amis()->InputText('ip', translator('admin.admin_operation_log.ip')),
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

    /**
     * 操作日志表单页面
     * 
     * 定义操作日志信息的表单结构
     * 
     * @return Form 返回操作日志表单
     */
    public function form(): Form
    {
        return $this->baseForm()
            ->body();
    }
}
