<?php

namespace warm\admin\controller\system;

use warm\admin\controller\AdminController;
use warm\admin\model\system\SystemCrontabLog;
use warm\admin\renderer\form\Form;
use warm\admin\renderer\Page;
use warm\admin\service\system\SystemCrontabLogService;

/**
 * 定时任务日志控制器
 *
 * 用于管理系统定时任务的执行日志
 * 提供日志列表查看和详情展示功能
 *
 * @property SystemCrontabLogService $service 定时任务日志服务类实例
 */
class SystemCrontabLogController extends AdminController
{
    /**
     * @var string $serviceName 服务类名称
     * 指定当前控制器使用的服务类
     */
    protected string $serviceName = SystemCrontabLogService::class;

    /**
     * 日志列表页面
     *
     * 展示定时任务执行日志列表，支持按执行状态筛选
     *
     * @param mixed|null $id 定时任务ID，用于筛选特定任务的日志
     * @return Page 返回日志列表页面
     */
    public function list(mixed $id = null): Page
    {
        // 设置查询路径
        $this->queryPath = '/system/crontab_log';
        $crud = $this->baseCRUD()
            ->headerToolbar([
                ...$this->baseHeaderToolBar(),
            ])
            ->api([
                'url' => $this->getListGetDataPath(),
                'data' => [
                    'crontab_id' => $id,
                    'execution_status' => '${execution_status}',
                ],
            ])
            ->filterTogglable(false)
            ->filter(
                $this->baseFilter()->submitOnChange(false)->body([
                    amis()->Select('execution_status', translator('crontab.crontab_log.execution_status'))
                        ->options(map2options(SystemCrontabLog::EXECUTION_STATUS))->clearable(true),
                ])
            )
            ->columns([
                amis()->TableColumn('id', 'ID')->sortable(),
                amis()->TableColumn('crontab_id', translator('crontab.crontab_log.crontab_id')),
                amis()->TableColumn('target', translator('crontab.crontab_log.target')),
                amis()->TableColumn('parameter', translator('crontab.crontab_log.parameter'))->type('json'),
                amis()->TableColumn('execution_status', translator('crontab.crontab_log.execution_status'))->type('status')->map([1 => 'success', 2 => 'fail'])->labelMap(SystemCrontabLog::EXECUTION_STATUS),
                amis()->TableColumn('created_at', translator('admin.created_at'))->sortable(),
                $this->rowActions([
                    $this->rowShowButton(true),
                    $this->rowDeleteButton(),
                ]),
            ]);

        return $this->baseList($crud);
    }

    public function form():Form
    {
        return $this->baseForm()->body([
            amis()->InputText('id', 'ID')->static(),
            amis()->InputText('crontab_id', translator('crontab.crontab_log.crontab_id'))->static(),
            amis()->InputText('target', translator('crontab.crontab_log.target'))->static(),
            amis()->InputGroup()->label(translator('crontab.crontab_log.parameter'))->body([
                amis()->Json()->name('parameter'),
            ])
        ]);
    }

    /**
     * 日志详情页面
     *
     * 展示定时任务执行日志的详细信息
     *
     * @return Form 返回日志详情表单
     */
    public function detail(): Form
    {
        return $this->baseDetail()->body([
            amis()->InputText('id', 'ID')->static(),
            amis()->InputText('crontab_id', translator('crontab.crontab_log.crontab_id'))->static(),
            amis()->InputText('target', translator('crontab.crontab_log.target'))->static(),
            amis()->InputGroup()->label(translator('crontab.crontab_log.parameter'))->body([
                amis()->Json()->name('parameter'),
            ]),
            amis()->InputGroup()->label(translator('crontab.crontab_log.exception_info'))->body([
                amis()->Json()->name('exception_info'),
            ]),
            amis()->Select('execution_status', translator('crontab.crontab_log.execution_status'))->options(map2options(SystemCrontabLog::EXECUTION_STATUS))->static(),
        ]);
    }
}