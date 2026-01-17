<?php

namespace warm\admin\controller\monitor;

use warm\admin\controller\AdminController;
use warm\admin\model\monitor\AdminLoginLog;
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
            amis()->InputText('username', '用户名'),
            amis()->InputText('ip', '登录IP地址'),
            amis()->InputText('ip_location', 'IP所属地'),
            amis()->InputText('os', '操作系统'),
            amis()->InputText('browser', '浏览器'),
            amis()->InputText('status', '登录状态'),
            amis()->InputText('message', '提示消息'),
            amis()->InputText('login_time', '登录时间'),
            amis()->InputText('remark', '备注'),
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
            amis()->InputText('id', 'ID')->static(),
            amis()->InputText('username', '用户名')->static(),
            amis()->InputText('ip', '登录IP地址')->static(),
            amis()->InputText('ip_location', 'IP所属地')->static(),
            amis()->InputText('os', '操作系统')->static(),
            amis()->InputText('browser', '浏览器')->static(),
            amis()->InputText('status', '登录状态')->static(),
            amis()->InputText('message', '提示消息')->static(),
            amis()->InputText('login_time', '登录时间')->static(),
            amis()->InputText('remark', '备注')->static(),
            amis()->InputText('created_at', translator('admin.created_at'))->static(),
            amis()->InputText('updated_at', translator('admin.updated_at'))->static(),
        ]);
    }
}
