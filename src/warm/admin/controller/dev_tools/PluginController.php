<?php

namespace warm\admin\controller\dev_tools;

use support\Request;
use support\Response;
use warm\admin\Admin;
use warm\admin\controller\AdminController;
use warm\admin\plugin\PluginService;
use warm\admin\renderer\CRUDTable;
use warm\admin\renderer\DialogAction;

/**
 * 插件管理控制器
 * 
 * 负责处理插件的管理功能，包括：
 * 1. 插件列表展示
 * 2. 插件启用/禁用
 * 3. 插件创建
 * 4. 插件卸载
 * 
 * @property PluginService $service 插件管理服务类实例
 */
class PluginController extends AdminController
{
    protected string $serviceName = PluginService::class;

    /**
     * 插件管理首页
     * 
     * 根据请求类型返回不同的响应：
     * - 如果是获取数据的请求，返回插件列表数据
     * - 如果是页面请求，返回完整的插件管理页面
     * 
     * @return Response 响应对象
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function index(): Response
    {
        // 判断是否为获取数据的请求
        if ($this->actionOfGetData()) {
            // 获取插件列表数据
            $data = $this->service->list();
            // 处理每个插件数据
            foreach ($data['items'] as $key => $extension) {
                $data['items'][$key] = $this->each($extension);
            }

            // 返回成功响应，包含插件列表数据
            return $this->response()->success($data);
        }

        // 构建页面并返回
        $page = $this->basePage()->body($this->list());

        return $this->response()->success($page);
    }

    /**
     * 处理单个插件数据
     * 
     * 提取插件的关键信息并格式化返回
     * 
     * @param mixed $extension 插件对象
     * @return array 处理后的插件信息数组
     */
    protected function each($extension)
    {
        // 获取插件配置信息
        $property = $this->service->configApp($extension->name);
        
        // 返回格式化后的插件信息
        return [
            'id'          => $extension->id,
            // 'alias'       => $extension->getAlias(),
            // 'logo'        => $extension->getLogoBase64(),
            'name'        => $extension->name,
            'version'     => $property['version'] ?? '',
            'description' => $property['description'] ?? '',
            'authors'     => $property['authors'] ?? '未知',
            'homepage'    => $property['homepage'] ?? '',
            'enabled'     => $extension['is_enabled'],
            // 'extension'   => $extension,
            // 'doc'         => $extension->getDocs(),
            // 'has_setting' => $extension->settingForm() instanceof Form,
            // 'used'        => $extension->used(),
        ];
    }

    /**
     * 构建插件列表页面
     * 
     * 创建一个包含过滤器、工具栏和数据列的CRUD表格，
     * 用于展示和管理插件。
     * 
     * @return CRUDTable CRUD表格对象
     */
    public function list(): CRUDTable
    {
        return amis()->CRUDTable()
            ->perPage(20)
            ->affixHeader(false)
            ->filterTogglable()
            ->filterDefaultVisible(false)
            ->api($this->getListGetDataPath())
            ->perPageAvailable([10, 20, 30, 50, 100, 200])
            ->footerToolbar(['switch-per-page', 'statistics', 'pagination'])
            ->loadDataOnce()
            ->source('${rows | filter:alias:match:keywords}')
            ->filter(
                $this->baseFilter()->body([
                    amis()->TextControl()
                        ->name('keywords')
                        ->label(translator('admin.extensions.form.name'))
                        ->placeholder(translator('admin.extensions.filter_placeholder'))
                        ->size('md'),
                ])
            )
            ->headerToolbar([
                $this->createExtend(),
                // $this->localInstall(),
                // $this->moreExtend(),
                amis('reload')->align('right'),
                amis('filter-toggler')->align('right'),
            ])
            ->columns([
                // 插件基本信息列
                amis()->TableColumn('alias', translator('admin.extensions.form.name'))
                    ->type('tpl')
                    ->tpl('
<div class="flex">
    <div> <img src="${logo}" class="w-10 mr-4"/> </div>
    <div>
        <div><a href="${homepage}" target="_blank">${alias | truncate:30}</a></div>
        <div class="text-gray-400">${name}</div>
    </div>
</div>
'),
                // 作者信息列
                amis()->TableColumn('author', translator('admin.extensions.card.author'))
                    ->type('tpl')
                    ->tpl('<div>${authors.name}</div> <span class="text-gray-400">${authors.email}</span>'),
                // 行操作按钮列
                $this->rowActions([
                    // 查看文档按钮
                    amis()->DrawerAction()->label(translator('admin.show'))->className('p-0')->level('link')->drawer(
                        amis()->Drawer()
                            ->size('lg')
                            ->title('README.md')
                            ->actions([])
                            ->closeOnOutside()
                            ->closeOnEsc()
                            ->body(amis()->Markdown()->name('${doc | raw}')->options([
                                'html'   => true,
                                'breaks' => true,
                            ]))
                    ),
                    // 插件设置按钮
                    amis()->DrawerAction()
                        ->label(translator('admin.extensions.setting'))
                        ->level('link')
                        ->visibleOn('${has_setting && enabled}')
                        ->drawer(
                            amis()
                                ->Drawer()
                                ->title(translator('admin.extensions.setting'))
                                ->resizable()
                                ->closeOnOutside()
                                ->body(
                                    amis()->Service()
                                        ->schemaApi([
                                            'url'    => admin_url('dev_tools/extensions/config_form'),
                                            'method' => 'post',
                                            'data'   => [
                                                'id' => '${id}',
                                            ],
                                        ])
                                )
                                ->actions([])
                        ),
                    // 启用/禁用按钮
                    amis()->AjaxAction()
                        ->label('${enabled ? "' . translator('admin.extensions.disable') . '" : "' . translator('admin.extensions.enable') . '"}')
                        ->level('link')
                        ->className(["text-success" => '${!enabled}', "text-danger" => '${enabled}'])
                        ->api([
                            'url'    => admin_url('dev_tools/plugin/enable'),
                            'method' => 'post',
                            'data'   => [
                                'id'      => '${id}',
                                'enabled' => '${!enabled}',
                            ],
                        ])
                        ->confirmText('${enabled ? "' . translator('admin.extensions.disable_confirm') . '" : "' . translator('admin.extensions.enable_confirm') . '"}'),
                    // 卸载按钮
                    amis()->AjaxAction()
                        ->label(translator('admin.extensions.uninstall'))
                        ->level('link')
                        ->className('text-danger')
                        ->api([
                            'url'    => admin_url('dev_tools/extensions/uninstall'),
                            'method' => 'post',
                            'data'   => ['id' => '${id}'],
                        ])
                        ->visibleOn('${used}')
                        ->confirmText(translator('admin.extensions.uninstall_confirm')),
                ]),
            ]);
    }

    /**
     * 创建扩展按钮
     *
     * 创建一个用于创建新插件的对话框按钮。
     *
     * @return DialogAction 对话框按钮对象
     */
    public function createExtend(): DialogAction
    {
        return amis()->DialogAction()
            ->label(translator('admin.extensions.create_extension'))
            ->icon('fa fa-add')
            ->level('success')
            ->dialog(
                amis()->Dialog()->title(translator('admin.extensions.create_extension'))->body(
                    amis()->Form()->mode('normal')->api($this->getStorePath())->body([
                        // 提示信息
                        amis()->Alert()
                            ->level('info')
                            ->showIcon()
                            ->body(translator('admin.extensions.create_tips', ['dir' => Admin::config('app.extension.dir')])),
                        // 插件名称输入框
                        amis()->TextControl()
                            ->name('name')
                            ->label(translator('admin.extensions.form.name'))
                            ->placeholder('foo')
                            ->required(),
                    ])
                )
            );
    }

    /**
     * 启用/禁用插件
     * 
     * 根据请求参数启用或禁用指定插件
     * 
     * @param Request $request HTTP请求对象
     * @return mixed 响应结果
     */
    public function enable(Request $request)
    {
        // 定义响应回调函数
        $response = fn($result) => $this->autoResponse($result, translator('admin.save'));
        // 调用服务启用插件并返回结果
        return $response($this->service->enable($request->all()) > 0);
    }

    /**
     * 新增插件
     *
     * 处理插件的创建操作，支持快速编辑等多种操作类型。
     *
     * @param Request $request HTTP请求对象
     *
     * @return Response 响应对象
     *
     * Author:sym
     * Date:2024/6/18 上午10:47
     * Company:极智科技
     */
    public function store(Request $request): Response
    {
        // 定义响应回调函数
        $response = fn($result) => $this->autoResponse($result, translator('admin.save'));

        // 处理快速编辑请求
        if ($this->actionOfQuickEdit()) {
            return $response($this->service->quickEdit($request->all()));
        }

        // 处理单项快速编辑请求
        if ($this->actionOfQuickEditItem()) {
            return $response($this->service->quickEditItem($request->all()));
        }

        // 处理插件存储请求
        if ($this->service->store($request->all())) {
            return $this->response()->successMessage(translator('admin.save') . translator('admin.successfully'));
        }

        // 返回失败响应
        return $this->response()->fail($this->service->getError() ?? translator('admin.save') . translator('admin.failed'));
    }
}