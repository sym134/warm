<?php

namespace warm\admin\trait;

use warm\admin\Admin;
use warm\admin\renderer\Action;
use warm\admin\renderer\CRUD;
use warm\admin\renderer\expand\Operation;
use warm\admin\renderer\form\ConditionBuilder;
use warm\admin\renderer\form\Form;
use warm\admin\renderer\Page;
use warm\admin\renderer\Service;

/**
 * 元素Trait
 * 
 * 提供Admin系统中常用的界面元素和操作按钮的生成方法
 * 包括页面基础元素、操作按钮、表单、列表等组件的创建功能
 */
trait ElementTrait
{
    /**
     * 基础页面
     *
     * @return Page 页面实例
     */
    protected function basePage(): Page
    {
        return amis()->Page()->className('m:overflow-auto');
    }

    /**
     * 返回列表按钮
     *
     * @return Action 返回按钮实例
     */
    protected function backButton(): Action
    {
        $path   = str_replace(Admin::warmConfig('app.route.prefix'), '', request()->path());
        $script = sprintf('window.$owl.hasOwnProperty(\'closeTabByPath\') && window.$owl.closeTabByPath(\'%s\')', $path);

        return amis()
            ->Action()->actionType('pre')
            ->label(translator('admin.back'))
            ->icon('fa-solid fa-chevron-left')
            ->level('primary')
            ->onClick('window.history.back();' . $script);
    }

    /**
     * 批量删除按钮
     * 
     * @return Action 批量删除按钮实例
     */
    protected function bulkDeleteButton(): Action
    {
        return amis()->Action()->actionType('dialog')
            ->label(translator('admin.delete'))
            ->icon('fa-solid fa-trash-can')
            ->dialog(
                amis()->Dialog()
                    ->title(translator('admin.delete'))
                    ->bodyClassName('py-2')
                    ->actions([
                        amis()->Action()->actionType('cancel')->label(translator('admin.cancel')),
                        amis()->Action()->actionType('submit')->label(translator('admin.delete'))->level('danger'),
                    ])
                    ->body([
                        amis()->Form()->wrapWithPanel(false)->api($this->getBulkDeletePath())->body([
                            amis()->Tpl()->className('py-2')->tpl(translator('admin.confirm_delete')),
                        ]),
                    ])
            );
    }

    /**
     * 新增按钮
     *
     * @param bool|string $dialog 是否弹窗, 弹窗: true|dialog, 抽屉: drawer
     * @param string $dialogSize 弹窗大小, 默认: md, 可选值: xs | sm | md | lg | xl | full
     * @param string $title 弹窗标题 & 按钮文字, 默认: 新增
     *
     * @return Action 新增按钮实例
     */
    protected function createButton(bool|string $dialog = false, string $dialogSize = 'md', string $title = ''): Action
    {
        $title  = $title ?: translator('admin.create');
        $action = amis()->Action()->actionType('link')->link($this->getCreatePath());

        if ($dialog) {
            $form = $this->form(false)->canAccessSuperData(false)->api($this->getStorePath())->onEvent([]);

            if ($dialog === 'drawer') {
                $action = amis()->Action()->actionType('drawer')->drawer(
                    amis()->Drawer()->title($title)->body($form)->size($dialogSize)
                );
            } else {
                $action = amis()->Action()->actionType('dialog')->dialog(
                    amis()->Dialog()->title($title)->body($form)->size($dialogSize)
                );
            }
        }

        $action->label($title)->icon('fa fa-add')->level('primary');

        return $action;
    }

    /**
     * 行编辑按钮
     *
     * @param bool|string $dialog 是否弹窗, 弹窗: true|dialog, 抽屉: drawer
     * @param string $dialogSize 弹窗大小, 默认: md, 可选值: xs | sm | md | lg | xl | full
     * @param string $title 弹窗标题 & 按钮文字, 默认: 编辑
     *
     * @return Action 行编辑按钮实例
     */
    protected function rowEditButton(bool|string $dialog = false, string $dialogSize = 'md', string $title = ''): Action
    {
        $title  = $title ?: translator('admin.edit');
        $action = amis()->Action()->actionType('link')->link($this->getEditPath());

        if ($dialog) {
            $form = $this->form(true)
                ->api($this->getUpdatePath())
                ->initApi($this->getEditGetDataPath())
                ->redirect('')
                ->onEvent([]);

            if ($dialog === 'drawer') {
                $action = amis()->Action()->actionType('drawer')->drawer(
                    amis()->Drawer()->title($title)->body($form)->size($dialogSize)
                );
            } else {
                $action = amis()->Action()->actionType('dialog')->dialog(
                    amis()->Dialog()->title($title)->body($form)->size($dialogSize)
                );
            }
        }

        $action->label($title)->level('link');

        return $action;
    }

    /**
     * 行详情按钮
     *
     * @param bool|string $dialog 是否弹窗, 弹窗: true|dialog, 抽屉: drawer
     * @param string $dialogSize 弹窗大小, 默认: md, 可选值: xs | sm | md | lg | xl | full
     * @param string $title 弹窗标题 & 按钮文字, 默认: 详情
     *
     * @return Action 行详情按钮实例
     */
    protected function rowShowButton(bool|string $dialog = false, string $dialogSize = 'md', string $title = ''): Action
    {
        $title  = $title ?: translator('admin.show');
        $action = amis()->Action()->actionType('link')->link($this->getShowPath());

        if ($dialog) {
            if ($dialog === 'drawer') {
                $action = amis()->Action()->actionType('drawer')->drawer(
                    amis()->Drawer()->title($title)->body($this->detail('$id'))->size($dialogSize)
                );
            } else {
                $action = amis()->Action()->actionType('dialog')->dialog(
                    amis()->Dialog()->title($title)->body($this->detail('$id'))->size($dialogSize)
                );
            }
        }

        $action->label($title)->level('link');

        return $action;
    }

    /**
     * 行删除按钮
     *
     * @param string $title 按钮标题
     *
     * @return Action 行删除按钮实例
     */
    protected function rowDeleteButton(string $title = ''): Action
    {
        return amis()->Action()->actionType('dialog')
            ->label($title ?: translator('admin.delete'))
            ->level('link')
            ->className('text-danger')
            ->dialog(
                amis()->Dialog()
                    ->title()
                    ->bodyClassName('py-2')
                    ->actions([
                        amis()->Action()->actionType('cancel')->label(translator('admin.cancel')),
                        amis()->Action()->actionType('submit')->label(translator('admin.delete'))->level('danger'),
                    ])
                    ->body([
                        amis()->Form()->wrapWithPanel(false)->api($this->getDeletePath())->body([
                            amis()->Tpl()->className('py-2')->tpl(translator('admin.confirm_delete')),
                        ]),
                    ])
            );
    }

    /**
     * 操作列
     *
     * @param bool|array|string $dialog 是否弹窗, 弹窗: true|dialog, 抽屉: drawer
     * @param string $dialogSize 弹窗大小, 默认: md, 可选值: xs | sm | md | lg | xl | full
     *
     * @return Operation 操作列实例
     */
    protected function rowActions(bool|array|string $dialog = false, string $dialogSize = 'md'): Operation
    {
        if (is_array($dialog)) {
            return amis()->Operation()->label(translator('admin.actions'))->buttons($dialog);
        }

        return amis()->Operation()->label(translator('admin.actions'))->buttons([
            $this->rowShowButton($dialog, $dialogSize),
            $this->rowEditButton($dialog, $dialogSize),
            $this->rowDeleteButton(),
        ]);
    }

    /**
     * 基础筛选器
     *
     * @return Form 筛选器表单实例
     */
    protected function baseFilter(): Form
    {
        return amis()->Form()
            ->panelClassName('base-filter')
            ->title()
            ->actions([
                amis()->Button()->label(translator('admin.reset'))->actionType('clear-and-submit'),
                amis('submit')->label(translator('admin.search'))->level('primary'),
            ]);
    }

    /**
     * 基础筛选器 - 条件构造器
     *
     * @return ConditionBuilder 条件构造器控件实例
     */
    protected function baseFilterConditionBuilder(): ConditionBuilder
    {
        return amis()->ConditionBuilder('filter_condition_builder');
    }

    
    protected function baseCRUD(): CRUD
    {
        $crudId = str_replace('/', '.', request()->path()) . '.crud';

        $crud = amis()->CRUD()
            ->id($crudId)
            ->perPage(20)
            ->alwaysShowPagination()
            ->affixHeader(false)
            ->filterTogglable(true)
            ->filterDefaultVisible(false)
            ->api($this->getListGetDataPath())
            ->quickSaveApi($this->getQuickEditPath())
            ->quickSaveItemApi($this->getQuickEditItemPath())
            ->bulkActions([$this->bulkDeleteButton()])
            ->footerToolbar([
                'statistics',
                // 重写实现 CRUD 自带的页码切换组件, 解决下拉被遮挡的问题
                amis()->Form()->wrapWithPanel(false)->body([
                    amis()->Select('perPage')
                        ->options(array_map(
                            fn($i) => ['label' => $i . ' ' . translator('admin.per_page_suffix'), 'value' => $i],
                            [10, 20, 30, 50, 100, 200]
                        ))
                        ->set('overlayPlacement', 'top')
                        ->onEvent([
                            'change' => [
                                'actions' => [
                                    [
                                        'actionType'  => 'reload',
                                        'componentId' => $crudId,
                                        'data'        => ['perPage' => '${event.data.value}'],
                                    ],
                                ],
                            ],
                        ]),
                ])->target('window'),
                'pagination',
            ])
            ->headerToolbar([
                $this->createButton('drawer'),
                ...$this->baseHeaderToolBar(),
            ]);

        if (isset($this->service)) {
            $crud->set('primaryField', $this->service->primaryKey());
        }

        return $crud;
    }

    /**
     * 基础顶部工具栏
     *
     * @return array 顶部工具栏元素数组
     */
    protected function baseHeaderToolBar(): array
    {
        return [
            'bulkActions',
            amis('reload')->align('right'),
            amis('filter-toggler')->align('right'),
        ];
    }

    /**
     * 基础表单
     *
     * @param bool $back 是否添加返回按钮事件
     *
     * @return Form 表单实例
     */
    protected function baseForm(bool $back = true): Form
    {
        $path = str_replace(Admin::warmConfig('app.route.prefix'), '', request()->path());

        $form = amis()->Form()->panelClassName('px-10 m:px-0 no-border')->title(' ')->promptPageLeave();

        if ($back) {
            $form->onEvent([
                'submitSucc' => [
                    'actions' => [
                        ['actionType' => 'custom', 'script' => 'window.history.back()'],
                        [
                            'actionType' => 'custom',
                            'script'     => sprintf('window.$owl.hasOwnProperty(\'closeTabByPath\') && window.$owl.closeTabByPath(\'%s\')', $path),
                        ],
                    ],
                ],
            ]);
        }

        return $form;
    }

    /**
     * 基础详情表单
     * 
     * @return Form 详情表单实例
     */
    protected function baseDetail(): Form
    {
        return amis()->Form()
            ->panelClassName('px-48 m:px-0')
            ->title(' ')
            ->mode('horizontal')
            ->actions()
            ->initApi($this->getShowGetDataPath());
    }

    /**
     * 基础列表
     *
     * @param mixed $crud CRUD实例
     *
     * @return Page 页面实例
     */
    protected function baseList(mixed $crud): Page
    {
        return amis()->Page()->className('m:overflow-auto')->body($crud);
    }

    /**
     * 导出按钮
     *
     * @param bool $disableSelectedItem 是否禁用选中项导出
     *
     * @return Service 服务实例
     */
    protected function exportAction(bool $disableSelectedItem = false): Service
    {
        // 获取主键名称
        $primaryKey = $this->service->primaryKey();
        // 下载路径
        $downloadPath = admin_url('_download_export', true);
        // 导出接口地址
        $exportPath = $this->getExportPath();
        // 无数据提示
        $pageNoData = translator('admin.export.page_no_data');
        // 选中行无数据提示
        $selectedNoData = translator('admin.export.selected_rows_no_data');
        // 按钮点击事件
        $event = fn($script) => ['click' => ['actions' => [['actionType' => 'custom', 'script' => $script]]]];
        // 导出处理动作
        $doAction = "doAction([{actionType:'setValue',componentId:'export-action',args:{value:{showExportLoading:true}}},{actionType:'ajax',args:{api:{url:url.toString(),method:'get'}}},{actionType:'setValue',componentId:'export-action',args:{value:{showExportLoading:false}}},{actionType:'custom',expression:'\${event.data.responseResult.responseStatus===0}',script:'window.open(\'$downloadPath?path=\'+event.data.responseResult.responseData.path)'}])";
        // 按钮
        $buttons = [
            // 导出全部
            amis()->Button()->label(translator('admin.export.all'))->onEvent(
                $event("let data=event.data;let params=Object.keys(data).filter(key=>key!=='page' && key!=='__super').reduce((obj,key)=>{obj[key]=data[key];return obj;},{});let url=new URL('{$exportPath}',window.location.origin);Object.keys(params).forEach(key=>url.searchParams.append(key,(typeof params[key] == 'string' ? params[key] : JSON.stringify(params[key]))));{$doAction}")
            ),
            // 导出本页
            amis()->Button()->label(translator('admin.export.page'))->onEvent(
                $event("let ids=event.data.items.map(item=>item.{$primaryKey});if(ids.length===0){return doAction({actionType:'toast',args:{msgType:'warning',msg:'{$pageNoData}'}})};let url=new URL('{$exportPath}',window.location.origin);url.searchParams.append('_ids',ids.join(','));{$doAction}")
            ),
        ];
        // 导出选中项
        if (!$disableSelectedItem) {
            $buttons[] = amis()->Button()->label(translator('admin.export.selected_rows'))->onEvent(
                $event("let ids=event.data.selectedItems.map(item=>item.{$primaryKey});if(ids.length===0){return doAction({actionType:'toast',args:{msgType:'warning',msg:'{$selectedNoData}'}})};let url=new URL('{$exportPath}',window.location.origin);url.searchParams.append('_ids',ids.join(','));{$doAction}")
            );
        }

        return amis()->Service()
            ->id('export-action')
            ->set('align', 'right')
            ->set('data', ['showExportLoading' => false])
            ->body(
                amis()->Spinner()->set('showOn', '${showExportLoading}')->overlay()->body(
                    amis()->DropdownButton()
                        ->label(translator('admin.export.title'))
                        ->set('icon', 'fa-solid fa-download')
                        ->buttons($buttons)
                        ->closeOnClick()
                        ->align('right')
                )
            );
    }
}