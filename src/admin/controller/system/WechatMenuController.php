<?php

namespace warm\admin\controller\system;

use support\Response;
use warm\admin\controller\AdminController;
use warm\admin\renderer\Page;
use warm\admin\service\system\WechatMenuService;

/**
 * 微信菜单控制器
 * @extends AdminController<WechatMenuService>
 */
class WechatMenuController extends AdminController
{
    /**
     * @var string 服务类名
     */
    protected string $serviceName = WechatMenuService::class;

    /**
     * 微信菜单管理页面
     *
     * @return Page
     */
    public function index(): Response
    {
        // 如果是获取数据的操作，返回列表数据
        if ($this->actionOfGetData()) {
            return $this->response()->success($this->service->list());
        }

        // 返回页面
        return $this->response()->success($this->menuPage());
    }

    /**
     * 菜单管理页面
     *
     * @return Page
     */
    public function menuPage(): Page
    {
        $page = $this->basePage()
            ->title(translator('wechat_menu.title'))
            ->body([
                $this->menuForm()
            ]);

        return $page;
    }

    /**
     * 菜单表单
     *
     * @return array
     */
    private function menuForm(): array
    {
        return [
            amis()->CRUD()
                ->id('menu-crud')
                ->api($this->getListGetDataPath())
                ->syncLocation(false)
                ->childrenColumnName('children')
                ->expandable([
                    'expandableOn' => 'this.children && this.children.length > 0'
                ])
                ->headerToolbar([
                    amis()->Button()
                        ->label(translator('wechat_menu.actions.publish_to_wechat'))
                        ->level('success')
                        ->icon('fa fa-upload')
                        ->actionType('ajax')
                        ->api($this->getPublishPath())
                        ->confirmText(translator('wechat_menu.actions.publish_confirm'))
                        ->onEvent([
                            'success' => [
                                'actions' => [
                                    [
                                        'actionType' => 'toast',
                                        'args' => [
                                            'toastType' => 'success',
                                            'msg' => translator('wechat_menu.actions.publish_success')
                                        ]
                                    ]
                                ]
                            ],
                            'error' => [
                                'actions' => [
                                    [
                                        'actionType' => 'toast',
                                        'args' => [
                                            'toastType' => 'error',
                                            'msg' => translator('wechat_menu.actions.publish_failed') . '${event.data.msg || event.data.message || "' . translator('admin.unknown_error') . '"}'
                                        ]
                                    ]
                                ]
                            ]
                        ]),
                    amis()->Button()
                        ->label(translator('wechat_menu.actions.add_menu'))
                        ->level('primary')
                        ->actionType('dialog')
                        ->dialog([
                            'title' => translator('wechat_menu.actions.add_menu'),
                            'body' => [
                                'type' => 'form',
                                'api' => $this->getStorePath(),
                                'onEvent' => [
                                    'submitSucc' => [
                                        'actions' => [
                                            [
                                                'actionType' => 'reload',
                                                'target' => 'menu-crud'
                                            ],
                                            [
                                                'actionType' => 'reload',
                                                'target' => 'menu-preview-service'
                                            ],
                                            [
                                                'actionType' => 'custom',
                                                'script' => 'setTimeout(() => { if (window.refreshMenuPreview) { window.refreshMenuPreview(); } }, 500);'
                                            ]
                                        ]
                                    ]
                                ],
                                'body' => [
                                    amis()->InputText('name', translator('wechat_menu.form.menu_name'))
                                        ->required(true)
                                        ->placeholder(translator('wechat_menu.form.menu_name_placeholder')),
                                    amis()->Select('parent_id', translator('wechat_menu.form.parent_menu'))
                                        ->options([
                                            ['label' => translator('wechat_menu.form.first_level_menu'), 'value' => 0]
                                        ])
                                        ->source('system/wechat_menu/parent_options')
                                        ->value(0)
                                        ->description(translator('wechat_menu.form.parent_menu_description')),
                                    amis()->Select('type', translator('wechat_menu.form.rule_type'))
                                        ->options([
                                            ['label' => translator('wechat_menu.form.rule_type_click'), 'value' => 'click'],
                                            ['label' => translator('wechat_menu.form.rule_type_view'), 'value' => 'view'],
                                            ['label' => translator('wechat_menu.form.rule_type_miniprogram'), 'value' => 'miniprogram']
                                        ])
                                        ->value('click')
                                        ->required(true),
                                    amis()->InputText('key', translator('wechat_menu.form.key'))
                                        ->visibleOn('this.type == "click"')
                                        ->required(true)
                                        ->placeholder(translator('wechat_menu.form.key_placeholder')),
                                    amis()->InputText('url', translator('wechat_menu.form.url'))
                                        ->visibleOn('this.type == "view"')
                                        ->required(true)
                                        ->placeholder(translator('wechat_menu.form.url_placeholder')),
                                    amis()->InputText('appid', translator('wechat_menu.form.appid'))
                                        ->visibleOn('this.type == "miniprogram"')
                                        ->required(true)
                                        ->placeholder(translator('wechat_menu.form.appid_placeholder')),
                                    amis()->InputText('pagepath', translator('wechat_menu.form.pagepath'))
                                        ->visibleOn('this.type == "miniprogram"')
                                        ->required(true)
                                        ->placeholder(translator('wechat_menu.form.pagepath_placeholder')),
                                    amis()->InputText('miniprogram_url', translator('wechat_menu.form.miniprogram_url'))
                                        ->visibleOn('this.type == "miniprogram"')
                                        ->required(true)
                                        ->placeholder(translator('wechat_menu.form.miniprogram_url_placeholder')),
                                    amis()->InputNumber('sort', translator('wechat_menu.form.sort'))
                                        ->value(0)
                                        ->min(0)
                                        ->description(translator('wechat_menu.form.sort_description')),
                                ]
                            ]
                        ])
                ])
                ->columns([
                    amis()->TableColumn('name', translator('wechat_menu.list.menu_name'))
                        ->width(150),
                    amis()->TableColumn('type', translator('wechat_menu.list.type'))
                        ->type('mapping')
                        ->map([
                            'click' => translator('wechat_menu.list.type_click'),
                            'view' => translator('wechat_menu.list.type_view'),
                            'miniprogram' => translator('wechat_menu.list.type_miniprogram')
                        ])
                        ->width(100),
                    amis()->TableColumn('key', translator('wechat_menu.list.key'))
                        ->tpl('${key || "-"}'),
                    amis()->TableColumn('url', translator('wechat_menu.list.url'))
                        ->tpl('${url || "-"}')
                        ->breakpoint('*')
                        ->popOver([
                            'trigger' => 'hover',
                            'body' => '${url}'
                        ]),
                    amis()->TableColumn('appid', translator('wechat_menu.list.appid'))
                        ->tpl('${appid || "-"}')
                        ->breakpoint('*'),
                    amis()->TableColumn('pagepath', translator('wechat_menu.list.pagepath'))
                        ->tpl('${pagepath || "-"}')
                        ->breakpoint('*'),
                    amis()->TableColumn('sort', translator('wechat_menu.list.sort'))
                        ->width(80),
                    amis()->TableColumn('id', translator('wechat_menu.list.actions'))
                        ->type('operation')
                        ->buttons([
                            amis()->Button()
                                ->label(translator('admin.edit'))
                                ->level('link')
                                ->actionType('dialog')
                                ->dialog([
                                    'title' => translator('wechat_menu.actions.edit_menu'),
                                    'body' => [
                                        'type' => 'form',
                                        'api' => $this->getUpdatePath(),
                                        'initApi' => $this->getEditGetDataPath(),
                                        'onEvent' => [
                                            'submitSucc' => [
                                                'actions' => [
                                                    [
                                                        'actionType' => 'reload',
                                                        'target' => 'menu-crud'
                                                    ],
                                                    [
                                                        'actionType' => 'reload',
                                                        'target' => 'menu-preview-service'
                                                    ],
                                                    [
                                                        'actionType' => 'custom',
                                                        'script' => 'setTimeout(() => { if (window.refreshMenuPreview) { window.refreshMenuPreview(); } }, 500);'
                                                    ]
                                                ]
                                            ]
                                        ],
                                        'body' => [
                                            amis()->InputText('name', translator('wechat_menu.form.menu_name'))->required(true),
                                            amis()->Select('parent_id', translator('wechat_menu.form.parent_menu'))
                                                ->options([
                                                    ['label' => translator('wechat_menu.form.first_level_menu'), 'value' => 0]
                                                ])
                                                ->source('system/wechat_menu/parent_options')
                                                ->description(translator('wechat_menu.form.parent_menu_description_edit')),
                                            amis()->Select('type', translator('wechat_menu.form.rule_type'))
                                                ->options([
                                                    ['label' => translator('wechat_menu.form.rule_type_click'), 'value' => 'click'],
                                                    ['label' => translator('wechat_menu.form.rule_type_view'), 'value' => 'view'],
                                                    ['label' => translator('wechat_menu.form.rule_type_miniprogram'), 'value' => 'miniprogram']
                                                ])
                                                ->value('click')
                                                ->required(true),
                                            amis()->InputText('key', translator('wechat_menu.form.key'))
                                                ->visibleOn('this.type == "click"')
                                                ->required(true),
                                            amis()->InputText('url', translator('wechat_menu.form.url'))
                                                ->visibleOn('this.type == "view"')
                                                ->required(true),
                                            amis()->InputText('appid', translator('wechat_menu.form.appid'))
                                                ->visibleOn('this.type == "miniprogram"')
                                                ->required(true)
                                                ->placeholder(translator('wechat_menu.form.appid_placeholder')),
                                            amis()->InputText('pagepath', translator('wechat_menu.form.pagepath'))
                                                ->visibleOn('this.type == "miniprogram"')
                                                ->required(true)
                                                ->placeholder(translator('wechat_menu.form.pagepath_placeholder')),
                                            amis()->InputText('miniprogram_url', translator('wechat_menu.form.miniprogram_url'))
                                                ->visibleOn('this.type == "miniprogram"')
                                                ->required(true)
                                                ->placeholder(translator('wechat_menu.form.miniprogram_url_placeholder')),
                                            amis()->InputNumber('sort', translator('wechat_menu.form.sort'))
                                                ->value(0)
                                                ->min(0)
                                                ->description(translator('wechat_menu.form.sort_description')),
                                        ]
                                    ]
                                ]),
                            amis()->Button()
                                ->label(translator('wechat_menu.actions.delete_menu'))
                                ->level('link')
                                ->actionType('ajax')
                                ->api($this->getDeletePath())
                                ->confirmText(translator('wechat_menu.actions.delete_confirm'))
                        ])
                ])
        ];
    }

    /**
     * 获取父菜单选项
     *
     * @return Response
     */
    public function parentOptions(): Response
    {
        $menus = $this->service->getModel()
            ->where('parent_id', 0)
            ->orderBy('sort', 'asc')
            ->get();

        $options = [['label' => translator('wechat_menu.form.first_level_menu'), 'value' => 0]];
        foreach ($menus as $menu) {
            $options[] = [
                'label' => $menu->name,
                'value' => $menu->id
            ];
        }

        return $this->response()->success($options);
    }

    /**
     * 发布菜单到微信
     *
     * @return Response
     */
    public function publish(): Response
    {
        $result = $this->service->publish();
        return $this->autoResponse($result, translator('wechat_menu.messages.publish_success'));
    }
}

