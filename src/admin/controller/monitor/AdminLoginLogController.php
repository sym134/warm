<?php

namespace warm\admin\controller\monitor;

use warm\admin\controller\AdminController;
use warm\admin\renderer\form\Form;
use warm\admin\renderer\Page;
use warm\admin\service\monitor\AdminLoginLogService;

/**
 * 管理员登录日志控制器
 * 
 * 用于查看和管理后台管理员的登录日志信息
 * 提供登录日志列表查看和详情展示功能
 * 
 * @property AdminLoginLogService $service 管理员登录日志服务类实例
 */
class AdminLoginLogController extends AdminController
{
    /**
     * @var string $serviceName 服务类名称
     * 指定当前控制器使用的服务类
     */
    protected string $serviceName = AdminLoginLogService::class;

    /**
     * 登录日志列表页面
     * 
     * 展示管理员登录日志列表，包含用户、IP地址、操作系统、浏览器等信息
     * 
     * @return Page 返回登录日志列表页面
     */
    public function list(): Page
    {
        $crud = $this->baseCRUD()
            ->filterTogglable(false)
            ->headerToolbar([
                ...$this->baseHeaderToolBar(),
            ])
            ->columns([
                amis()->TableColumn('id', translator('monitor.login_log.id'))->sortable(),
                amis()->TableColumn('username', translator('monitor.login_log.username')),
                amis()->TableColumn('ip', translator('monitor.login_log.ip')),
                amis()->TableColumn('ip_location', translator('monitor.login_log.ip_location')),
                amis()->TableColumn('os', translator('monitor.login_log.os')),
                amis()->TableColumn('browser', translator('monitor.login_log.browser')),
                amis()->TableColumn('status', translator('monitor.login_log.status'))->type('mapping')->map([
                    1 => translator('monitor.login_log.status_success'),
                    2 => translator('monitor.login_log.status_failed'),
                    3 => translator('monitor.login_log.status_disabled'),
                ]),
                amis()->TableColumn('message', translator('monitor.login_log.message')),
                amis()->TableColumn('login_time', translator('monitor.login_log.login_time')),
                // amis()->TableColumn('remark', translator('monitor.login_log.remark')),
                amis()->TableColumn('created_at', translator('admin.created_at'))->type('datetime')->sortable(),
            ]);

        return $this->baseList($crud);
    }

    /**
     * 登录日志表单页面
     * 
     * 定义登录日志信息的表单结构
     * 
     * @param bool $isEdit 是否为编辑模式
     * @return Form 返回登录日志表单
     */
    public function form($isEdit = false): Form
    {
        return $this->baseForm()->body([
            amis()->InputText('username', translator('monitor.login_log.username')),
            amis()->InputText('ip', translator('monitor.login_log.ip')),
            amis()->InputText('ip_location', translator('monitor.login_log.ip_location')),
            amis()->InputText('os', translator('monitor.login_log.os')),
            amis()->InputText('browser', translator('monitor.login_log.browser')),
            amis()->InputText('status', translator('monitor.login_log.status')),
            amis()->InputText('message', translator('monitor.login_log.message')),
            amis()->InputText('login_time', translator('monitor.login_log.login_time')),
            amis()->InputText('remark', translator('monitor.login_log.remark')),
        ]);
    }

    /**
     * 登录日志详情页面
     * 
     * 展示登录日志的详细信息
     * 
     * @return Form 返回登录日志详情表单
     */
    public function detail(): Form
    {
        return $this->baseDetail()->body([
            amis()->InputText('id', translator('monitor.login_log.id'))->static(),
            amis()->InputText('username', translator('monitor.login_log.username'))->static(),
            amis()->InputText('ip', translator('monitor.login_log.ip'))->static(),
            amis()->InputText('ip_location', translator('monitor.login_log.ip_location'))->static(),
            amis()->InputText('os', translator('monitor.login_log.os'))->static(),
            amis()->InputText('browser', translator('monitor.login_log.browser'))->static(),
            amis()->InputText('status', translator('monitor.login_log.status'))->static(),
            amis()->InputText('message', translator('monitor.login_log.message'))->static(),
            amis()->InputText('login_time', translator('monitor.login_log.login_time'))->static(),
            amis()->InputText('remark', translator('monitor.login_log.remark'))->static(),
            amis()->InputText('created_at', translator('admin.created_at'))->static(),
            amis()->InputText('updated_at', translator('admin.updated_at'))->static(),
        ]);
    }
}
