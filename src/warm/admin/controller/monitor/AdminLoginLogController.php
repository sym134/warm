<?php

namespace warm\admin\controller\monitor;

use warm\admin\controller\AdminController;
use warm\admin\model\monitor\AdminLoginLog;
use warm\admin\service\monitor\AdminLoginLogService;
use warm\renderer\Form;
use warm\renderer\Page;

/**
 * 登录日志
 *
 * @property AdminLoginLogService $service
 */
class AdminLoginLogController extends AdminController
{
    protected string $serviceName = AdminLoginLogService::class;

    public function list(): Page
    {
        $crud = $this->baseCRUD()
            ->filterTogglable(false)
            ->headerToolbar([
                ...$this->baseHeaderToolBar(),
            ])
            ->columns([
                amis()->TableColumn('id', 'ID')->sortable(),
                amis()->TableColumn('username', '用户名'),
                amis()->TableColumn('ip', '登录IP地址'),
                amis()->TableColumn('ip_location', 'IP所属地'),
                amis()->TableColumn('os', '操作系统'),
                amis()->TableColumn('browser', '浏览器'),
                amis()->TableColumn('status', '登录状态')->type('mapping')->map(AdminLoginLog::STATUS),
                amis()->TableColumn('message', '提示消息'),
                amis()->TableColumn('login_time', '登录时间'),
                // amis()->TableColumn('remark', '备注'),
                amis()->TableColumn('created_at', admin_trans('admin.created_at'))->type('datetime')->sortable(),
            ]);

        return $this->baseList($crud);
    }

    public function form($isEdit = false): Form
    {
        return $this->baseForm()->body([
            amis()->TextControl('username', '用户名'),
            amis()->TextControl('ip', '登录IP地址'),
            amis()->TextControl('ip_location', 'IP所属地'),
            amis()->TextControl('os', '操作系统'),
            amis()->TextControl('browser', '浏览器'),
            amis()->TextControl('status', '登录状态'),
            amis()->TextControl('message', '提示消息'),
            amis()->TextControl('login_time', '登录时间'),
            amis()->TextControl('remark', '备注'),
        ]);
    }

    public function detail(): Form
    {
        return $this->baseDetail()->body([
            amis()->TextControl('id', 'ID')->static(),
            amis()->TextControl('username', '用户名')->static(),
            amis()->TextControl('ip', '登录IP地址')->static(),
            amis()->TextControl('ip_location', 'IP所属地')->static(),
            amis()->TextControl('os', '操作系统')->static(),
            amis()->TextControl('browser', '浏览器')->static(),
            amis()->TextControl('status', '登录状态')->static(),
            amis()->TextControl('message', '提示消息')->static(),
            amis()->TextControl('login_time', '登录时间')->static(),
            amis()->TextControl('remark', '备注')->static(),
            amis()->TextControl('created_at', admin_trans('admin.created_at'))->static(),
            amis()->TextControl('updated_at', admin_trans('admin.updated_at'))->static(),
        ]);
    }
}
