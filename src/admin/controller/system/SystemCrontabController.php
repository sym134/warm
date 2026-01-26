<?php

namespace warm\admin\controller\system;

use support\Response;
use warm\admin\controller\AdminController;
use warm\admin\model\system\SystemCrontab;
use warm\admin\renderer\form\Form;
use warm\admin\renderer\Page;
use warm\admin\service\system\SystemCrontabService;

/**
 * 定时任务控制器
 *
 * 用于管理系统定时任务的增删改查操作
 * 提供任务创建、编辑、执行和日志查看等功能
 *
 * @property SystemCrontabService $service 定时任务服务类实例
 */
class SystemCrontabController extends AdminController
{
    /**
     * @var string $serviceName 服务类名称
     * 指定当前控制器使用的服务类
     */
    protected string $serviceName = SystemCrontabService::class;

    /**
     * 定时任务列表页面
     *
     * 展示系统中所有定时任务，支持筛选和立即执行功能
     *
     * @return Page 返回定时任务列表页面
     */
    public function list(): Page
    {
        $crud = $this->baseCRUD()
            ->filterTogglable(false)
            ->headerToolbar([
                $this->createButton(),
                ...$this->baseHeaderToolBar(),
            ])
            ->filter(
                $this->baseFilter()->api(['method' => 'get', 'url' => admin_url('/system/crontab')])->body([
                    amis()->InputText('name', translator('crontab.name'))->clearable(true),
                    amis()->Select('task_type', translator('crontab.task_type'))->options(SystemCrontab::TASK_TYPE)->clearable(true),
                    amis()->Select('task_status', translator('crontab.task_status'))->options([
                        1 => '启用',
                        2 => '禁用'
                    ])->clearable(true),
                ])
            )
            ->columns([
                amis()->TableColumn('id', 'ID')->sortable(),
                amis()->TableColumn('name', translator('crontab.name')),
                amis()->TableColumn('created_by', translator('crontab.created_by')),
                amis()->TableColumn('task_type', translator('crontab.task_type'))->type('mapping')->map(SystemCrontab::TASK_TYPE),
                amis()->TableColumn('execution_cycle_text', translator('crontab.execution_cycle')),
                amis()->TableColumn('task_status', translator('crontab.task_status'))->quickEdit(['mode' => 'inline', 'type' => 'switch', 'saveImmediately' => true]),
                amis()->TableColumn('created_at', translator('admin.created_at'))->sortable(),
                $this->rowActions([
                    amis()->Button()->id('u:a53d1837f6be')->label(translator('crontab.run'))->icon('fa-solid fa-play')->level('link')
                        ->onEvent(['click' => [
                            'actions' => [[
                                'ignoreError' => '',
                                'outputVar' => 'responseResult',
                                'actionType' => 'ajax',
                                'options' => [],
                                'api' => ['url' => admin_url('/system/crontab_run'), 'method' => 'get', 'data' => ['id' => '${id}',],],
                            ],],
                        ],])
                        ->confirmText('确认立即执行'),
                    amis()->Action()->actionType('drawer')->drawer(
                        amis()->Drawer()->title(translator('crontab.execution_log'))->body()->size('xl')->resizable()
                    )->label(translator('crontab.execution_log'))->icon('fa-solid fa-clock-rotate-left')->level('link'),
                    $this->rowEditButton(true, 'lg'),
                    $this->rowDeleteButton(),
                ]),
            ]);

        return $this->baseList($crud);
    }

    /**
     * 定时任务表单页面
     *
     * 定义定时任务新增/编辑表单结构，包含任务类型、执行周期、目标等字段
     *
     * @param bool $isEdit 是否为编辑模式
     * @return Form 返回定时任务表单
     */
    public function form(bool $isEdit = false): Form
    {
        return $this->baseForm()->mode('horizontal')->data([
            'week' => 1,
            'day' => 1,
            'hour' => 1,
            'minute' => 30,
            'second' => 1,
        ])->body([
            amis()->Select('task_type', translator('crontab.task_type'))->options(SystemCrontab::TASK_TYPE)->value(1)
                ->required()
                ->onEvent([
                    'change' => [
                        'actions' => [
                            [
                                'actionType' => 'setValue',
                                'componentId' => 'name',
                                'args' => ['value' => '${event.data.selectedItems.label}'],
                            ],
                        ],
                    ],
                ]),
            amis()->InputText('name', translator('crontab.name'))->id('name')->required()->value(SystemCrontab::TASK_TYPE[1])
                ->description(translator('crontab.name_description')),
            amis()->Group()->label(translator('crontab.execution_cycle'))->body([
                amis()->Select('execution_cycle')->mode('inline')->options([
                    'day' => translator('crontab.execution_cycle_options.day'),
                    'day-n' => translator('crontab.execution_cycle_options.day-n'),
                    'hour' => translator('crontab.execution_cycle_options.hour'),
                    'hour-n' => translator('crontab.execution_cycle_options.hour-n'),
                    'minute-n' => translator('crontab.execution_cycle_options.minute-n'),
                    'week' => translator('crontab.execution_cycle_options.week'),
                    'month' => translator('crontab.execution_cycle_options.month'),
                    'second-n' => translator('crontab.execution_cycle_options.second-n'),
                ])->value('day'),

                amis()->Select('week')->mode('inline')->options([
                    0 => '星期日',
                    1 => '星期一',
                    2 => '星期二',
                    3 => '星期三',
                    4 => '星期四',
                    5 => '星期五',
                    6 => '星期六',
                ])->value(1)->visibleOn('execution_cycle===\'week\''),
                amis()->InputGroup()->mode('inline')->visibleOn('execution_cycle===\'day-n\'||execution_cycle===\'month\'')->body([
                    amis()->InputNumber('day')->mode('inline')->value(1)->min(1)->max(31),
                    amis()->Button()->level('secondary')->label(translator('crontab.day')),
                ]),
                amis()->InputGroup()->mode('inline')->visibleOn('execution_cycle!==\'hour\'&&execution_cycle!==\'minute-n\'&&execution_cycle!==\'second-n\'')->body([
                    amis()->InputNumber('hour')->value(1)->min(0)->max(23),
                    amis()->Button()->level('secondary')->label(translator('crontab.hour')),
                ]),
                amis()->InputGroup()->mode('inline')->visibleOn('execution_cycle!==\'second-n\'')->body([
                    amis()->InputNumber('minute')->value(30)->min(0)->max(59),
                    amis()->Button()->level('secondary')->label(translator('crontab.minute')),
                ]),
                amis()->InputGroup()->mode('inline')->visibleOn('execution_cycle==\'second-n\'')->body([
                    amis()->InputNumber('second')->value(1)->min(1)->max(59),
                    amis()->Button()->level('secondary')->label(translator('crontab.second')),
                ]),

            ]),
            amis()->InputText('target', translator('crontab.target'))->required()->description(translator('crontab.target_description')),
            amis()->JsonSchema()->name('parameter')->set('name', 'parameter')->label(translator('crontab.parameter')),
            amis()->Switch('task_status', translator('crontab.task_status'))->trueValue(1)->falseValue(2)->required()->value(1),
            amis()->InputText('remark', translator('crontab.remark')),
        ]);
    }

    /**
     * 定时任务详情页面
     *
     * 展示定时任务的详细信息
     *
     * @return Form 返回定时任务详情表单
     */
    public function detail(): Form
    {
        return $this->baseDetail()->body([]);
    }

    /**
     * 立即执行定时任务
     *
     * 手动触发指定定时任务的执行
     *
     * @return Response 返回执行结果响应
     */
    public function run(): Response
    {
        if ($this->service->run(request()->get('id'))) {
            return $this->response()->successMessage(translator('crontab.run') . translator('admin.successfully'));
        }
        return $this->response()->fail(translator('crontab.run') . translator('admin.failed') . $this->service->getError());
    }
}