<?php

namespace warm\admin\controller;

use support\Response;
use warm\admin\Admin;
use warm\admin\renderer\Form;
use warm\admin\renderer\ListSelect;
use warm\admin\renderer\Page;
use warm\admin\service\AdminMenuService;
use warm\admin\service\AdminPageService;
use warm\admin\trait\IconifyPickerTrait;

/**
 * 管理菜单控制器
 * 
 * 用于管理系统菜单的增删改查操作
 * 提供菜单拖拽排序、图标选择等功能
 * 
 * @property AdminMenuService $service 管理菜单服务类实例
 */
class AdminMenuController extends AdminController
{
    use IconifyPickerTrait;

    /**
     * @var string $serviceName 服务类名称
     * 指定当前控制器使用的服务类
     */
    protected string $serviceName = AdminMenuService::class;

    /**
     * 菜单列表页面
     * 
     * 展示系统菜单列表，支持拖拽排序、快速编辑等功能
     * 
     * @return Page 返回菜单列表页面
     */
    public function list(): Page
    {
        $crud = $this->baseCRUD()
            ->perPage(999)
            ->draggable()
            ->saveOrderApi([
                'url'  => '/system/admin_menus/save_order',
                'data' => ['ids' => '${ids}'],
            ])
            ->loadDataOnce()
            ->syncLocation(false)
            ->footerToolbar([])
            ->headerToolbar([$this->createButton(true, 'lg'), ...$this->baseHeaderToolBar()])
            ->filterTogglable(false)
            ->footerToolbar(['statistics'])
            ->bulkActions([$this->bulkDeleteButton()->reload('window')])
            ->columns([
                amis()->TableColumn('id', 'ID')->sortable(),
                amis()->TableColumn('title', translator('admin.admin_menu.title')),
                amis()->TableColumn('icon', translator('admin.admin_menu.icon'))
                    ->type('flex')
                    ->className('text-center h-full')
                    ->justify('start')
                    ->items('center')
                    ->items([
                        amis()->Wrapper()->size('none')->body(
                            amis()->CustomSvgIcon()->icon('${icon}')->className('mr-2 text-xl h-full')
                        ),
                        amis()->Tpl()->tpl('${icon}'),
                    ]),
                amis()->TableColumn('url', translator('admin.admin_menu.url')),
                amis()->TableColumn('order', translator('admin.admin_menu.order'))->quickEdit(
                    amis()->InputNumber()->min(0)->saveImmediately()
                ),
                amis()->TableColumn('visible', translator('admin.admin_menu.visible'))->quickEdit(
                    amis()->Switch()->mode('inline')->saveImmediately()
                ),
                amis()->TableColumn('is_home', translator('admin.admin_menu.is_home'))->quickEdit(
                    amis()->Switch()->mode('inline')->saveImmediately()
                ),
                $this->rowActions([
                    $this->rowEditButton(true, 'lg'),
                    $this->rowDeleteButton(),
                ]),
            ]);

        return $this->baseList($crud);
    }

    /**
     * 菜单表单页面
     * 
     * 定义菜单新增/编辑表单结构，包含标题、图标、父级菜单、排序等字段
     * 支持多种菜单类型（路由、页面、iframe等）
     * 
     * @return Form 返回菜单表单
     */
    public function form(): Form
    {
        return $this->baseForm()->body([
            amis()->Group()->body([
                amis()->InputText('title', translator('admin.admin_menu.title'))->required(),
                $this->iconifyPicker('icon', translator('admin.admin_menu.icon')),
            ]),
            amis()->Group()->body([
                amis()->TreeSelect('parent_id', translator('admin.admin_menu.parent_id'))
                    ->labelField('title')
                    ->valueField('id')
                    ->showIcon(false)
                    ->value(0)
                    ->source('/system/admin_menus?_action=getData'),
                amis()->InputNumber('order', translator('admin.admin_menu.order'))
                    ->required()
                    ->displayMode('enhance')
                    ->description(translator('admin.order_asc'))
                    ->min(0)
                    ->value(0),
            ]),
            amis()->ListSelect('url_type', translator('admin.admin_menu.type'))
                ->options(Admin::adminMenuModel()::getType())
                ->value(Admin::adminMenuModel()::TYPE_ROUTE),
            amis()->InputText('url', translator('admin.admin_menu.url'))
                ->required()
                ->validateOnChange()
                ->validations(['matchRegexp' => '/^(http(s)?\:\/)?(\/)+/'])
                ->validationErrors(['matchRegexp' => translator('admin.need_start_with_slash')])
                ->placeholder('eg: /admin_menus')->hiddenOn('url_type != ' . Admin::adminMenuModel()::TYPE_LINK),

            amis()->InputText('url', translator('admin.admin_menu.route'))
                ->required()
                ->validateOnChange()
                ->validations(['matchRegexp' => '/^(http(s)?\:\/)?(\/)+/'])
                ->validationErrors(['matchRegexp' => translator('admin.need_start_with_slash')])
                ->placeholder('eg: /admin_menus')->hiddenOn('url_type == ' . Admin::adminMenuModel()::TYPE_LINK),

            amis()->InputText('component', translator('admin.admin_menu.component'))
                ->description(translator('admin.admin_menu.component_desc'))
                ->value('amis')->hiddenOn('url_type != ' . Admin::adminMenuModel()::TYPE_ROUTE),

            amis()->Select('component', translator('admin.admin_menu.page'))
                ->required()
                ->options(AdminPageService::make()->options())
                ->menuTpl('${label} <span class="text-gray-300 pl-2">${value}</span>')
                ->selectFirst()
                ->searchable()
                ->visibleOn('url_type == ' . Admin::adminMenuModel()::TYPE_PAGE),

            amis()->Group()->body([
                amis()->InputText('iframe_url', 'IframeUrl')
                    ->required()
                    ->validateOnChange()
                    ->validations(['matchRegexp' => '/^(http(s)?\:\/)?(\/)+/'])
                    ->validationErrors(['matchRegexp' => translator('admin.need_start_with_slash')])
                    ->placeholder('eg: https://www.qq.com')
                    ->hiddenOn('url_type != ' . Admin::adminMenuModel()::TYPE_IFRAME),
            ]),

            amis()->Switch('keep_alive', translator('admin.admin_menu.keep_alive'))
                ->onText(translator('admin.yes'))
                ->offText(translator('admin.no'))
                ->description(translator('admin.admin_menu.iframe_description'))
                ->value(0),

            amis()->Switch('visible', translator('admin.admin_menu.visible'))
                ->onText(translator('admin.admin_menu.show'))
                ->offText(translator('admin.admin_menu.hide'))
                ->value(1),
            amis()->Switch('is_home', translator('admin.admin_menu.is_home'))
                ->onText(translator('admin.yes'))
                ->offText(translator('admin.no'))
                ->description(translator('admin.admin_menu.is_home_description'))
                ->value(0),
            amis()->Switch('is_full', translator('admin.admin_menu.is_full'))
                ->onText(translator('admin.yes'))
                ->offText(translator('admin.no'))
                ->description(translator('admin.admin_menu.is_full_description'))
                ->value(0),
        ])->onEvent([
            'submitSucc' => [
                'actions' => [
                    'actionType' => 'custom',
                    'script'     => 'window.location.reload()',
                ],
            ],
        ]);
    }

    /**
     * 菜单详情页面
     * 
     * 展示菜单详细信息
     * 
     * @return Form 返回菜单详情表单
     */
    public function detail(): Form
    {
        return $this->baseDetail()->body([]);
    }

    /**
     * 保存排序
     * 
     * 处理菜单拖拽排序后的保存操作
     * 
     * @return Response 返回操作结果响应
     */
    public function saveOrder(): Response
    {
        return $this->autoResponse($this->service->reorder(request()->input('ids')));
    }
}
