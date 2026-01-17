<?php

namespace warm\admin\controller\dev_tools;

use support\Request;
use support\Response;
use Throwable;
use warm\admin\Admin;
use warm\admin\controller\AdminController;
use warm\admin\plugin\PluginService;
use warm\admin\renderer\Action;
use warm\admin\renderer\CRUD;

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
    /**
     * 服务类名称
     * 
     * @var string
     */
    protected string $serviceName = PluginService::class;

    /**
     * 插件管理首页
     *
     * 根据请求类型返回不同的响应：
     * - 如果是获取数据的请求，返回插件列表数据
     * - 如果是页面请求，返回完整的插件管理页面
     *
     * @return Response 响应对象
     */
    public function index(): Response
    {
        // 判断是否为获取数据的请求
        if ($this->actionOfGetData()) {
            // 获取插件列表数据
            $data = $this->service->list();
            // 处理每个插件数据
            // foreach ($data['items'] as $key => $plugin) {
            //     $data['items'][$key] = $this->each($plugin);
            // }

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
     * @param mixed $plugin 插件对象
     * @return array 处理后的插件信息数组
     */
    protected function each(mixed $plugin): array
    {
        // 获取插件配置信息
        $property = $this->service->configApp($plugin->key);
        // 返回格式化后的插件信息
        return [
            'id' => $plugin->id,
            'logo' => $property['logo'] ?? '',
            'name' => $property['name'] ?? '',
            'key' => $plugin->key,
            'version' => $property['version'] ?? '',
            'description' => $property['description'] ?? '',
            'authors' => $property['authors'] ?? ['name' => '', 'email' => ''],
            'homepage' => $property['homepage'] ?? '',
            'doc' => $property['doc'] ?? '',
            'is_enabled' => $property['enable'] ?? false,
        ];
    }

    /**
     * 构建插件列表页面
     *
     * 创建一个包含过滤器、工具栏和数据列的CRUD表格，
     * 用于展示和管理插件。
     *
     * @return CRUD CRUD表格对象
     */
    public function list(): CRUD
    {
        return amis()->CRUD()
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
                    amis()->InputText()
                        ->name('name')
                        ->label(translator('admin.plugins.form.name'))
                        ->placeholder(translator('admin.plugins.filter_placeholder'))
                        ->size('md'),
                ])
            )
            ->headerToolbar([
                $this->createPlugin(),
                // $this->localInstall(),
                // $this->moreExtend(),
                amis('reload')->align('right'),
                amis('filter-toggler')->align('right'),
            ])
            ->columns([
                // 插件基本信息列
                amis()->TableColumn('name', translator('admin.plugins.form.name'))
                    ->type('tpl')
                    ->tpl('
<div class="flex">
    <div> <img src="${logo}" class="w-10 mr-4"/> </div>
    <div>
        <div><a href="${homepage}" target="_blank">${name | truncate:30}</a></div>
        <div class="text-gray-400">${key}</div>
    </div>
</div>
'),
                // 作者信息列
                // amis()->TableColumn('author', translator('admin.plugins.card.author'))
                //     ->type('tpl')
                //     ->tpl('<div>${authors.name}</div> <span class="text-gray-400">${authors.email}</span>'),
                // amis()->TableColumn('version', translator('admin.plugins.card.version')),
                // amis()->TableColumn('description', translator('admin.plugins.form.description')),
                // 行操作按钮列
                $this->rowActions([
                    // 查看文档按钮
                    // amis()->DrawerAction()->label(translator('admin.show'))->className('p-0')->level('link')->drawer(
                    //     amis()->Drawer()
                    //         ->size('lg')
                    //         ->title('README')
                    //         ->actions([])
                    //         ->closeOnOutside()
                    //         ->closeOnEsc()
                    //         ->body(amis()->Markdown()->name('${doc | raw}')->options([
                    //             'html' => true,
                    //             'breaks' => true,
                    //         ]))
                    // ),
                    // 插件设置按钮
                    amis()->DrawerAction()
                        ->label(translator('admin.plugins.setting'))
                        ->level('link')
                        ->visibleOn('${is_enabled}')
                        ->drawer(
                            amis()
                                ->Drawer()
                                ->title(translator('admin.plugins.setting'))
                                ->resizable()
                                ->closeOnOutside()
                                ->body(
                                    amis()->Service()
                                        ->schemaApi([
                                            'url' => admin_url('dev_tools/plugin/config_form'),
                                            'method' => 'post',
                                            'data' => [
                                                'id' => '${id}',
                                            ],
                                        ])
                                )
                                ->actions([])
                        ),
                    // 启用/禁用按钮
                    amis()->Action()->actionType('ajax')
                        ->label('${is_enabled ? "' . translator('admin.plugins.disable') . '" : "' . translator('admin.plugins.enable') . '"}')
                        ->level('link')
                        ->className(["text-success" => '${!is_enabled}', "text-danger" => '${is_enabled}'])
                        ->api([
                            'url' => admin_url('dev_tools/plugin/enable'),
                            'method' => 'post',
                            'data' => [
                                'id' => '${id}',
                                'enabled' => '${!is_enabled}',
                            ],
                        ])
                        ->confirmText('${is_enabled ? "' . translator('admin.plugins.disable_confirm') . '" : "' . translator('admin.plugins.enable_confirm') . '"}'),
                    // 卸载按钮
                    amis()->Action()->actionType('ajax')
                        ->label(translator('admin.plugins.uninstall'))
                        ->level('link')
                        ->className('text-danger')
                        ->api([
                            'url' => admin_url('dev_tools/plugin/uninstall'),
                            'method' => 'post',
                            'data' => ['id' => '${id}'],
                        ])
                        ->hiddenOn('${is_enabled}')
                        ->confirmText(translator('admin.plugins.uninstall_confirm')),
                ]),
            ]);
    }

    /**
     * 创建插件按钮
     *
     * 创建一个用于创建新插件的对话框按钮。
     *
     * @return Action 对话框按钮对象
     */
    public function createPlugin(): Action
    {
        return amis()->Action()->actionType('dialog')
            ->label(translator('admin.plugins.create_plugin'))
            ->icon('fa fa-add')
            ->level('success')
            ->dialog(
                amis()->Dialog()->title(translator('admin.plugins.create_plugin'))->body(
                    amis()->Form()->mode('normal')->api($this->getStorePath())->body([
                        // 提示信息
                        amis()->Alert()
                            ->level('info')
                            ->showIcon()
                            ->body(translator('admin.plugins.create_tips', ['dir' => Admin::warmConfig('app.plugin.dir')])),
                        // 插件名称输入框
                        amis()->InputText()->name('key')->label(translator('admin.plugins.form.key'))
                            ->placeholder('foo')
                            ->required(),
                        amis()->InputText()->name('name')->label(translator('admin.plugins.form.name'))
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
     * @return Response 响应对象
     */
    public function enable(Request $request): Response
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

    /**
     * 卸载插件
     *
     * 根据插件ID卸载指定插件
     *
     * @param Request $request HTTP请求对象
     * @return Response 响应对象
     */
    public function uninstall(Request $request): Response
    {
        $this->service->uninstall($request->post('id'));

        return $this->response()->successMessage(translator('admin.action_success'));
    }

    /**
     * 本地安装插件按钮
     *
     * 创建一个用于本地安装插件的对话框按钮
     *
     * @return Action 对话框按钮对象
     */
    public function localInstall(): Action
    {
        return amis()->Action()->actionType('dialog')
            ->label(translator('admin.plugins.local_install'))
            ->icon('fa-solid fa-cloud-arrow-up')
            ->dialog(
                amis()->Dialog()->title(translator('admin.plugins.local_install'))->showErrorMsg(false)->body(
                    amis()->Form()->mode('normal')->api('post:' . admin_url('dev_tools/plugins/install'))->body([
                        amis()->InputFile()->name('file')->label()->required()->drag()->accept('.zip'),
                    ])
                )
            );
    }

    /**
     * 更多插件
     * 
     * 跳转到扩展市场页面
     *
     * @return Action URL跳转按钮对象
     */
    public function moreExtend(): Action
    {
        return amis()->Action()->actionType('url')
            ->url('https://xxxxx.com/ext')
            ->label(translator('admin.plugins.more_plugins'))
            ->icon('fa-regular fa-lightbulb')
            ->level('success')
            ->blank();
    }

    /**
     * 安装插件
     *
     * 处理上传的插件压缩包并进行安装
     *
     * @param Request $request HTTP请求对象
     * @return Response 响应对象
     */
    public function install(Request $request): Response
    {
        $file = $request->file('file');

        if (!$file) {
            return $this->response()->fail(translator('admin.plugins.validation.file'));
        }
        try {
            $this->service->localInstall($file);
            return $this->response()->successMessage(
                translator('admin.successfully_message', ['attribute' => translator('admin.plugins.install')])
            );
        } catch (Throwable $e) {
            return $this->response()->fail($e->getMessage());
        } finally {
            if (!empty($path)) {
                @unlink($path);
            }
        }
    }

    /**
     * 获取插件配置
     *
     * 根据插件名称获取插件配置信息
     *
     * @param Request $request HTTP请求对象
     * @return Response 响应对象
     */
    public function getConfig(Request $request): Response
    {
        $config = Admin::plugin($request->post('plugin'))->config();
        return $this->response()->success($config);
    }

    /**
     * 保存插件配置
     *
     * 保存指定插件的配置信息
     *
     * @param Request $request HTTP请求对象
     * @return Response 响应对象
     */
    public function saveConfig(Request $request): Response
    {
        $data = collect($request->all())->except(['plugin'])->toArray();

        Admin::plugin($request->post('plugin'))->saveConfig($data);
        return $this->response()->successMessage(translator('admin.save_success'));
    }

    /**
     * 获取插件配置表单
     *
     * 根据插件ID获取插件的配置表单
     *
     * @param Request $request HTTP请求对象
     * @return Response 响应对象
     */
    public function configForm(Request $request): Response
    {
        $plugin = $this->service->getPluginById($request->post('id'));
        $form = Admin::plugin($plugin->key)->settingForm();

        return $this->response()->success($form);
    }
}