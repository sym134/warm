<?php

namespace warm\admin\controller\dev_tools;

use Exception;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use ReflectionClass;
use ReflectionException;
use ReflectionMethod;
use support\Request;
use support\Response;
use Throwable;
use warm\admin\controller\AdminController;
use warm\admin\plugin\PluginService;
use warm\admin\renderer\Action;
use warm\admin\renderer\Card;
use warm\admin\renderer\CRUD;
use warm\admin\renderer\Flex;
use warm\admin\renderer\form\Combo;
use warm\admin\renderer\form\Form;
use warm\admin\service\AdminCodeGeneratorService;
use warm\admin\service\AdminMenuService;
use warm\admin\support\code_generator\FilterGenerator;
use warm\admin\support\code_generator\GenCodeClear;
use warm\admin\support\code_generator\Generator;
use warm\admin\trait\IconifyPickerTrait;

/**
 * 代码生成器控制器
 *
 * 负责处理代码生成相关的所有功能，包括：
 * 1. 代码生成记录的增删改查
 * 2. 代码预览和生成
 * 3. 组件属性配置管理
 * 4. 字段配置管理
 * 5. 代码清理功能
 *
 * @property AdminCodeGeneratorService $service 代码生成服务类实例
 */
class CodeGeneratorController extends AdminController
{
    use IconifyPickerTrait;

    protected string $serviceName = AdminCodeGeneratorService::class;

    /**
     * 代码生成器首页
     *
     * 根据请求类型返回不同的响应：
     * - 如果是获取数据的请求，返回代码生成记录列表
     * - 如果是页面请求，返回完整的页面结构
     *
     * @return Response 响应对象
     */
    public function index(): Response
    {
        // 判断是否为获取数据的请求
        if ($this->actionOfGetData()) {
            return $this->response()->success($this->service->list());
        }

        // 返回完整的页面结构，包括CSS样式和列表内容
        return $this->response()->success(
            $this->basePage()->css($this->css())->body($this->list())
        );
    }

    /**
     * 构建代码生成记录列表页面
     *
     * 创建一个包含过滤器、工具栏和数据列的CRUD表格，
     * 用于展示和管理代码生成记录。
     *
     * @return CRUD CRUD表格对象
     */
    public function list(): CRUD
    {
        // 定义表单回调函数，用于创建和编辑操作
        $form = function ($isEdit = false) {
            // 获取基础表单结构
            $body = $this->form($isEdit);

            // 根据是否为编辑模式设置不同的API路径
            if ($isEdit) {
                $body = $body->initApi($this->getEditGetDataPath())->api($this->getUpdatePath());
            } else {
                $body = $body->api($this->getStorePath());
            }

            // 返回抽屉式表单
            return amis()
                ->Drawer()
                ->size('xl')
                ->title($isEdit ? translator('admin.edit') : translator('admin.create'))
                ->actions([
                    amis()->Button()->actionType('cancel')->label(translator('admin.cancel')),
                    amis()
                        ->Button()
                        ->actionType('submit')
                        ->label(translator('admin.save'))
                        ->level('primary'),
                ])
                ->body($body);
        };

        // 构建并返回CRUD表格
        return $this
            ->baseCRUD()
            ->filter(
                $this->baseFilter()->body([
                    amis()->InputText('keyword', translator('admin.keyword'))->size('md'),
                ])
            )
            ->headerToolbar([
                amis()
                    ->Action()
                    ->actionType('drawer')
                    ->label(translator('admin.create'))
                    ->icon('fa fa-add')
                    ->level('primary')
                    ->drawer($form()),
                amis()
                    ->Action()
                    ->actionType('dialog')
                    ->label(translator('admin.code_generators.import_record'))
                    ->icon('fa fa-upload')
                    ->level('success')
                    ->dialog(
                        amis()->Dialog()->title(false)->body(
                            amis()->Form()->mode('normal')->body([
                                amis()
                                    ->Textarea('data')
                                    ->required()
                                    ->minRows(10)
                                    ->description(translator('admin.code_generators.import_record_desc'))
                                    ->placeholder(translator('admin.code_generators.import_record_placeholder')),
                            ])->api([
                                'url' => '/dev_tools/code_generator',
                                'method' => 'post',
                                'data' => '${DECODEJSON(data)}',
                            ])
                        )
                    ),
                ...$this->baseHeaderToolBar(),
            ])
            ->columns([
                amis()->TableColumn('id', 'ID')->sortable(),
                amis()->TableColumn('title', translator('admin.code_generators.app_title')),
                amis()->TableColumn('table_name', translator('admin.code_generators.table_name')),
                amis()->TableColumn('menu_info.route', translator('admin.code_generators.route')),
                amis()->TableColumn('updated_at', translator('admin.updated_at'))->sortable(),
                $this->rowActions([
                    $this->generateCodeAction(),
                    $this->previewCodeAction(),
                    amis()
                        ->Action()->actionType('drawer')
                        ->label(translator('admin.edit'))
                        ->level('link')
                        ->drawer($form(true)),
                    $this->rowDeleteButton(),
                    amis()->DropdownButton()->label(translator('admin.more'))->level('link')->buttons([
                        $this->cloneAction(),
                        $this->copyRecordAction(),
                        $this->clearCodeAction(),
                    ]),
                ]),
            ]);
    }

    /**
     * 构建代码生成配置表单
     *
     * 创建一个多标签页的表单，包含基本信息、字段信息、
     * 路由配置和页面配置等四个主要部分。
     *
     * @param bool $isEdit 是否为编辑模式
     * @return Form 表单对象
     */
    public function form(bool $isEdit = false): Form
    {
        // 下划线的表名处理成驼峰文件名
        $nameHandler = 'JOIN(ARRAYMAP(SPLIT(IF(ENDSWITH(table_name, "s"), LEFT(table_name, LEN(table_name) - 1), table_name), "_"), item=>CAPITALIZE(item)))';
        // 填充路径
        $fillPathAction = [
            'actionType' => 'setValue',
            'componentId' => 'code_generator_form',
            'args' => [
                'value' => [
                    'model_name' => '${model_path}${' . $nameHandler . '}',
                    'controller_name' => '${controller_path}${' . $nameHandler . '}Controller',
                    'service_name' => '${service_path}${' . $nameHandler . '}Service',
                ],
            ],
        ];

        return amis()
            ->Form()
            ->promptPageLeave()
            ->id('code_generator_form')
            ->wrapWithPanel(false)
            ->labelWidth(150)
            ->title(' ')
            ->mode('horizontal')
            ->resetAfterSubmit()
            ->initApi('post:/dev_tools/code_generator/form_data')
            ->body([
                amis()->Tabs()->tabs([
                    // 基本信息标签页
                    amis()->Tab()->title(translator('admin.code_generators.base_info'))->tab([
                        amis()->Card()->body([
                            amis()->Group()->body([
                                amis()->Group()->direction('vertical')->body([
                                    amis()->Group()->body([
                                        amis()
                                            ->InputText('title', translator('admin.code_generators.app_title'))
                                            ->required()
                                            ->onEvent([
                                                'change' => [
                                                    'actions' => [
                                                        [
                                                            'actionType'  => 'setValue',
                                                            'componentId' => 'gen_menu_title',
                                                            'args'        => ['value' => '${value}'],
                                                        ],
                                                    ],
                                                ],
                                            ]),
                                    ]),
                                    amis()->Group()->body([
                                        amis()
                                            ->InputText('table_name', translator('admin.code_generators.table_name'))
                                            ->value()
                                            ->required()
                                            ->onEvent([
                                                'change' => [
                                                    'actions' => [
                                                        [
                                                            'actionType'  => 'setValue',
                                                            'componentId' => 'gen_menu_route',
                                                            'args'        => ['value' => '/${value}'],
                                                        ],
                                                        $fillPathAction,
                                                    ],
                                                ],
                                            ]),
                                        amis()
                                            ->Select('exists_table', translator('admin.code_generators.exists_table'))
                                            ->searchable()
                                            ->clearable()
                                            ->selectMode('group')
                                            ->source('${exists_tables}')
                                            ->onEvent([
                                                'change' => [
                                                    'actions' => [
                                                        // 更新 table_name 的值
                                                        [
                                                            'actionType'  => 'setValue',
                                                            'componentId' => 'code_generator_form',
                                                            'args'        => [
                                                                'value' => [
                                                                    'table_name'  => '${SPLIT(event.data.value, "+")[0]}',
                                                                    'primary_key' => '${table_primary_keys[SPLIT(event.data.value, "+")[1]][SPLIT(event.data.value, "+")[0]]}',
                                                                    'columns'     => '${table_info[SPLIT(event.data.value, "+")[1]][SPLIT(event.data.value, "+")[0]]}',
                                                                ],
                                                            ],
                                                        ],
                                                        [
                                                            'actionType'  => 'setValue',
                                                            'componentId' => 'gen_menu_route',
                                                            'args'        => ['value' => '/${SPLIT(event.data.value, "+")[0]}'],
                                                        ],
                                                        $fillPathAction,
                                                    ],
                                                ],
                                            ]),
                                    ]),
                                    amis()
                                        ->Checkboxes('needs', translator('admin.code_generators.options'))
                                        ->joinValues(false)
                                        ->extractValue()
                                        ->checkAll()
                                        ->defaultCheckAll()
                                        ->options(Generator::make()->needCreateOptions()),
                                    amis()
                                        ->InputText('primary_key', translator('admin.code_generators.primary_key'))
                                        ->value('id')
                                        ->description(translator('admin.code_generators.primary_key_description'))
                                        ->required(),
                                    amis()
                                        ->Select('save_path', translator('admin.code_generators.save_path_select'))->required()
                                        ->clearable()
                                        ->searchable()
                                        ->description(translator('admin.code_generators.save_path_select_tips'))
                                        ->selectMode('group')
                                        ->source('${save_path_options}')
                                        ->onEvent([
                                            'change' => [
                                                'actions' => [
                                                    // 更新 table_name 的值
                                                    [
                                                        'actionType'  => 'setValue',
                                                        'componentId' => 'code_generator_form',
                                                        'args'        => [
                                                            'value' => [
                                                                'controller_path' => '${event.data.value.controller_path}',
                                                                'service_path'    => '${event.data.value.service_path}',
                                                                'model_path'      => '${event.data.value.model_path}',
                                                            ],
                                                        ],
                                                    ],
                                                    $fillPathAction,
                                                ],
                                            ],
                                        ]),
                                    amis()->InputText('model_name', translator('admin.code_generators.model_name')),
                                    amis()->InputText('controller_name', translator('admin.code_generators.controller_name')),
                                    amis()->InputText('service_name', translator('admin.code_generators.service_name')),
                                    amis()->Switch('need_timestamps', 'CreatedAt & UpdatedAt')->value(1),
                                    amis()
                                        ->Switch('list_display_created_at', translator('admin.code_generators.list_display', ['content' => 'CreatedAt']))
                                        ->visibleOn('${need_timestamps}')
                                        ->value($isEdit ? '${page_info.list_display_created_at}' : '${need_timestamps}'),
                                    amis()
                                        ->Switch('list_display_updated_at', translator('admin.code_generators.list_display', ['content' => 'UpdatedAt']))
                                        ->visibleOn('${need_timestamps}')
                                        ->value($isEdit ? '${page_info.list_display_updated_at}' : '${need_timestamps}'),
                                    amis()->Switch('soft_delete', translator('admin.soft_delete'))->value(1),
                                ]),
                            ]),
                        ])
                    ]),
                    // 字段信息标签页
                    amis()->Tab()->title(translator('admin.code_generators.column_info'))->tab([
                        $this->cachedColumns(),
                        $this->columnForm(),
                    ]),
                    // 路由配置标签页
                    amis()->Tab()->title(translator('admin.code_generators.route_config'))->tab(
                        amis()->Combo('menu_info', false)->multiLine()->subFormMode('horizontal')->items([
                            amis()->Switch('enabled', translator('admin.code_generators.gen_route_menu'))->value(1),
                            amis()
                                ->InputText('route', translator('admin.code_generators.route'))
                                ->id('gen_menu_route')
                                ->required(),
                            amis()
                                ->InputText('title', translator('admin.code_generators.menu_name'))
                                ->id('gen_menu_title')
                                ->required(),
                            amis()
                                ->TreeSelect('parent_id', translator('admin.code_generators.parent_menu'))
                                ->labelField('title')
                                ->valueField('id')
                                ->value(0)
                                ->source('${menu_tree}'),
                            $this
                                ->iconifyPicker('icon', translator('admin.code_generators.menu_icon'))
                                ->value('ph:circle'),
                        ])
                    ),
                    // 页面配置标签页
                    amis()->Tab()->title(translator('admin.code_generators.page_config'))->tab(
                        amis()->Combo('page_info', false)->multiLine()->subFormMode('horizontal')->items([
                            amis()
                                ->Radios('dialog_form', translator('admin.code_generators.dialog_form'))
                                ->options([
                                    ['label' => translator('admin.code_generators.dialog'), 'value' => 'dialog'],
                                    ['label' => translator('admin.code_generators.drawer'), 'value' => 'drawer'],
                                    ['label' => translator('admin.code_generators.page'), 'value' => 'page'],
                                ])
                                ->selectFirst(),
                            amis()
                                ->Select('dialog_size', translator('admin.code_generators.dialog_size'))
                                ->options(['xs', 'sm', 'md', 'lg', 'xl', 'full'])
                                ->value('md')
                                ->visibleOn('${dialog_form == "dialog"}'),
                            amis()
                                ->Select('dialog_size', translator('admin.code_generators.drawer_size'))
                                ->options(['xs', 'sm', 'md', 'lg', 'full'])
                                ->value('md')
                                ->visibleOn('${dialog_form == "drawer"}'),
                            amis()->Checkboxes('row_actions', translator('admin.actions'))->options([
                                'create'       => translator('admin.create'),
                                'show'         => translator('admin.show'),
                                'edit'         => translator('admin.edit'),
                                'delete'       => translator('admin.delete'),
                                'batch_delete' => translator('admin.batch_delete'),
                            ])->checkAll()->defaultCheckAll()->joinValues(false)->extractValue(),
                        ])
                    ),
                ])
            ]);
    }

    /**
     * 生成代码
     *
     * 调用Generator类的generate方法生成代码文件，
     * 并返回生成结果。
     *
     * @param Request $request HTTP请求对象
     *
     * @return Response 响应对象，包含生成结果
     * @throws Throwable
     */
    public function generate(Request $request): Response
    {
        // 调用Generator生成代码，传入记录ID和需要生成的组件选项
        $result = Generator::make()->generate($request->input('id'), safe_explode(',', $request->input('needs')));

        // 返回成功响应，包含生成结果
        return $this->response()->doNotDisplayToast()->success(compact('result'));
    }

    /**
     * 预览代码
     *
     * 调用Generator类的preview方法预览将要生成的代码，
     * 并返回预览内容。
     *
     * @param Request $request HTTP请求对象
     *
     * @return Response 响应对象，包含预览内容
     * @throws Exception
     */
    public function preview(Request $request): Response
    {
        // 调用Generator预览代码，传入记录ID
        $data = Generator::make()->preview($request->input('id'));

        // 返回成功响应，包含预览数据
        return $this->response()->doNotDisplayToast()->success($data);
    }

    /**
     * 获取组件属性
     *
     * 通过反射机制获取指定组件类的所有公共方法，
     * 用于在前端展示组件可用的属性配置选项。
     *
     * @param Request $request HTTP请求对象
     *
     * @return Response 响应对象，包含组件属性选项
     * @throws ReflectionException 反射异常
     */
    public function getPropertyOptions(Request $request): Response
    {
        // 如果没有传入组件名称，则返回空数组
        if (blank($request->input('c'))) {
            return $this->response()->success([]);
        }

        // 构建完整的类名
        $className = '\\warm\\admin\\renderer\\' . $request->input('c');

        // 通过反射获取类信息
        $renderer = new ReflectionClass($className);

        // 需要排除的方法列表
        $exclude = ['__construct', '__call', 'set', 'jsonSerialize', 'toJson', 'toArray', 'name', 'label',];

        // 获取所有公共方法并过滤处理
        $options = collect($renderer->getMethods(ReflectionMethod::IS_PUBLIC))
            ->map(function ($item) {
                // 获取方法的注释文档
                $_doc = $item->getDocComment();
                $_doc = $_doc ? trim(str_replace(['/**', '*/', '*'], '', $_doc)) : false;

                return ['name' => $item->name, 'comment' => $_doc];
            })
            ->filter(fn($item) => !in_array($item['name'], $exclude))
            ->map(fn($item) => [
                'label' => $item['name'],
                'value' => $item['name'],
            ])
            ->values()
            ->toArray();

        // 返回成功响应，包含组件属性选项
        return $this->response()->success(['component_property_options' => $options]);
    }

    /**
     * 保存组件配置
     *
     * 将用户配置的组件属性保存到系统配置中，
     * 以便后续可以复用这些配置。
     *
     * @param Request $request HTTP请求对象
     *
     * @return Response 响应对象
     */
    public function saveComponentProperty(Request $request): Response
    {
        // 检查传入的值是否包含key字段
        admin_abort_if(!data_get($request->input('value'), 'key'), translator('admin.required', ['attribute' => translator('admin.admin_menu.component')]));

        $list = [];

        // 如果配置中已存在相关数据，则先读取出来
        if ($original = systemConfig()->get($request->input('key'))) {
            foreach ($original as $item) {
                $list[$item['key'] . '|' . $item['label']] = $item;
            }
        }

        // 将新的配置项添加到列表中
        $list[$request->input('value')['key'] . '|' . $request->input('value')['label']] = $request->input('value');

        // 保存配置到系统
        $res = systemConfig()->set($request->input('key'), array_values($list));

        // 返回自动响应结果
        return $this->autoResponse($res, translator('admin.save'));
    }

    /**
     * 获取组件配置
     *
     * 从系统配置中读取已保存的组件属性配置，
     * 用于在前端展示和选择。
     *
     * @param Request $request HTTP请求对象
     *
     * @return Response 响应对象，包含组件配置列表
     */
    public function getComponentProperty(Request $request): Response
    {
        // 从系统配置中获取组件属性列表
        $component_property_list = collect(systemConfig()->get($request->input('key')))->values();

        // 返回成功响应，包含组件属性列表
        return $this->response()->success(compact('component_property_list'));
    }

    /**
     * 删除组件配置
     *
     * 从系统配置中删除指定的组件属性配置。
     *
     * @param Request $request HTTP请求对象
     *
     * @return Response 响应对象
     */
    public function delComponentProperty(Request $request): Response
    {
        // 获取当前配置列表
        $list = systemConfig()->get($request->input('name'));

        // 如果配置列表为空，则直接返回
        if (blank($list)) {
            return $this->autoResponse(false);
        }

        // 遍历配置列表，找到匹配的项并删除
        foreach ($list as $key => $item) {
            if ($item['label'] == $request->input('label') && $item['key'] == $request->input('key')) {
                unset($list[$key]);
            }
        }

        // 保存更新后的配置列表
        systemConfig()->set($request->input('name'), array_values($list));

        // 返回成功响应
        return $this->autoResponse(true);
    }

    /**
     * 保存字段配置
     *
     * 将用户配置的字段属性保存到系统配置中，
     * 以便后续可以复用这些配置。
     *
     * @param Request $request HTTP请求对象
     *
     * @return Response 响应对象
     */
    public function saveColumnProperty(Request $request): Response
    {
        // 从传入的值中找到匹配的字段配置
        $value = collect($request->input('value'))->firstWhere('name', $request->input('column'));
        $list = systemConfig()->get('admin_common_field', []);

        // 将配置保存到列表中，排除组件属性选项
        $list[$request->input('name')] = Arr::except((array)$value, ['component_property_options']);

        // 保存配置到系统
        $res = systemConfig()->set('admin_common_field', $list);

        // 返回自动响应结果
        return $this->autoResponse($res, translator('admin.save'));
    }

    /**
     * 获取字段配置
     *
     * 从系统配置中读取已保存的字段配置，
     * 用于在前端展示和选择。
     *
     * @return Response 响应对象，包含字段配置列表
     */
    public function getColumnProperty(): Response
    {
        // 从系统配置中获取通用字段列表，并重新格式化
        $common_field_list = collect(systemConfig()->get('admin_common_field'))->map(fn($v, $k) => [
            'name' => $k,
            'column_name' => $v['name'],
            'value' => $v,
        ])->values();

        // 返回成功响应，包含通用字段列表
        return $this->response()->success(compact('common_field_list'));
    }

    /**
     * 删除字段配置
     *
     * 从系统配置中删除指定的字段配置。
     *
     * @param Request $request HTTP请求对象
     *
     * @return Response 响应对象
     */
    public function delColumnProperty(Request $request): Response
    {
        // 获取当前字段配置列表
        $list = systemConfig()->get('admin_common_field');

        // 如果配置列表为空，则直接返回
        if (blank($list)) {
            return $this->autoResponse(false);
        }

        // 遍历配置列表，找到匹配的项并删除
        foreach ($list as $key => $item) {
            if ($key == $request->input('name')) {
                unset($list[$key]);
            }
        }

        // 保存更新后的配置列表
        systemConfig()->set('admin_common_field', $list);

        // 返回成功响应
        return $this->autoResponse(true);
    }

    /**
     * 获取记录
     *
     * 获取指定ID的代码生成记录详情，
     * 并隐藏ID、创建时间和更新时间字段。
     *
     * @return Response 响应对象，包含记录详情
     */
    public function getRecord(): Response
    {
        // 获取指定ID的记录详情，并隐藏部分字段
        $record = $this->service->getDetail(request()->input('id'))->makeHidden(['id', 'created_at', 'updated_at'])->toArray();

        // 返回成功响应，包含记录详情
        return $this->response()->success(compact('record'));
    }

    /**
     * 获取表单数据
     *
     * 为代码生成表单提供初始化数据，包括：
     * 1. 数据库表信息
     * 2. 默认保存路径
     * 3. 插件信息
     * 4. 菜单树结构
     * 5. 组件选项
     *
     * @param bool $directReturn 是否直接返回数据而不是响应对象
     * @return Response|array 响应对象或数据数组
     */
    public function formData(bool $directReturn = false): Response|array
    {
        // 获取数据库列信息
        $databaseColumns = Generator::make()->getDatabaseColumns();

        // 获取默认保存路径
        $defaultPath = $this->service->getDefaultPath();

        // 初始化保存路径选项数组，包含默认路径
        $savePaths = [$defaultPath];

        // 遍历所有插件，将插件路径添加到保存路径选项中
        foreach (PluginService::make()->getPlugins() as $plugin) {
            $savePaths[] = [
                'label' => $plugin->name,
                'value' => [
                    'directory' => $plugin->name,
                    'controller_path' => '/plugin/' . $plugin->name . '/app/' . 'controller/',
                    'service_path' => '/plugin/' . $plugin->name . '/app/' . 'service/',
                    'model_path' => '/plugin/' . $plugin->name . '/app/' . 'model/',
                ],
            ];
        }

        // 构建已存在表的选项列表
        $existsTables = $databaseColumns->map(function ($item, $index) {
            return [
                'label' => $index,
                'children' => $item->keys()->map(function ($item) use ($index) {
                    return ['value' => $item . '+' . $index, 'label' => $item];
                }),
            ];
        })->values();

        // 构建返回数据数组
        $data = [
            'table_info' => $databaseColumns,
            'table_primary_keys' => Generator::make()->getDatabasePrimaryKeys(),
            'default_path' => $defaultPath,
            'model_path' => $defaultPath['value']['model_path'],
            'service_path' => $defaultPath['value']['service_path'],
            'controller_path' => $defaultPath['value']['controller_path'],
            'exists_tables' => $existsTables,
            'menu_tree' => AdminMenuService::make()->getTree(),
            'save_path_options' => $savePaths,
            'component_options' => $this->service->getComponentOptions(),
        ];

        // 根据参数决定直接返回数据还是包装成响应对象
        if ($directReturn === true) {
            return $data;
        }

        return $this->response()->success($data);
    }

    /**
     * 构建缓存字段部分
     *
     * 创建用于管理通用字段配置的界面元素，
     * 包括添加字段和加载配置的功能。
     *
     * @return Flex 弹性布局对象
     */
    public function cachedColumns(): Flex
    {
        return amis()->Flex()->justify('end')->className('pb-3')->items([
            amis()
                ->Action()->actionType('drawer')
                ->className('mr-3')
                ->label(translator('admin.code_generators.common_field_add'))
                ->level('primary')
                ->drawer(
                    amis()
                        ->Drawer()
                        ->title(translator('admin.code_generators.load_config'))
                        ->bodyClassName('p-0')
                        ->actions([])
                        ->id('load_config_dialog')
                        ->closeOnOutside()
                        ->body([
                            amis()
                                ->Service()
                                ->name('common_field_service')
                                ->api('post:/dev_tools/code_generator/common_field/list')
                                ->body(
                                    amis()
                                        ->CRUD()
                                        ->className('border-none')
                                        ->loadDataOnce()
                                        ->source('${common_field_list}')
                                        ->headerToolbar([
                                            amis()
                                                ->Action()->actionType('dialog')
                                                ->label(translator('admin.code_generators.common_field_add_column'))
                                                ->level('primary')
                                                ->dialog(
                                                    amis()
                                                        ->Dialog()
                                                        ->title(translator('admin.code_generators.common_field_add_column'))
                                                        ->body(
                                                            amis()
                                                                ->Form()
                                                                ->reload('common_field_service')
                                                                ->api('post:/dev_tools/code_generator/common_field/save')
                                                                ->body([
                                                                    amis()
                                                                        ->InputText('name', translator('admin.code_generators.config_name'))
                                                                        ->description(translator('admin.code_generators.same_name_tips'))
                                                                        ->required(),
                                                                    amis()
                                                                        ->Select('column', translator('admin.code_generators.field_config'))
                                                                        ->valueField('name')
                                                                        ->labelField('name')
                                                                        ->source('${columns}')
                                                                        ->menuTpl('<div>${name} (${comment})</div>')
                                                                        ->required(),
                                                                    amis()->Hidden('value')->value('${columns}'),
                                                                ])
                                                        )
                                                ),
                                        ])
                                        ->columns([
                                            amis()
                                                ->TableColumn('name', translator('admin.code_generators.config_name'))
                                                ->searchable(),
                                            amis()
                                                ->TableColumn('column_name', translator('admin.code_generators.field_name'))
                                                ->searchable(),
                                            amis()->Operation()->label(translator('admin.actions'))->buttons([
                                                // 填充按钮
                                                amis()
                                                    ->Button()
                                                    ->label(translator('admin.code_generators.fill'))
                                                    ->level('primary')
                                                    ->onEvent([
                                                        'click' => [
                                                            'actions' => [
                                                                [
                                                                    'actionType' => 'setValue',
                                                                    'componentId' => 'code_generator_form',
                                                                    'args' => [
                                                                        'value' => [
                                                                            'columns' => '${CONCAT(columns, [value])}',
                                                                        ],
                                                                    ],
                                                                ],
                                                                [
                                                                    'actionType' => 'closeDialog',
                                                                    'componentId' => 'load_config_dialog',
                                                                ],
                                                            ],
                                                        ],
                                                    ]),

                                                // 删除按钮
                                                amis()
                                                    ->Action()->actionType('ajax')
                                                    ->label(translator('admin.delete'))
                                                    ->level('danger')
                                                    ->confirmText(translator('admin.confirm_delete'))
                                                    ->reload('common_field_service')
                                                    ->api('post:/dev_tools/code_generator/common_field/del?name=${name}'),
                                            ])->set('width', 150),
                                        ])
                                ),
                        ])
                ),
        ]);
    }

    /**
     * 构建组件选择器
     *
     * 创建一个用于选择和配置组件的复合控件，
     * 包括组件类型选择、属性配置和配置管理功能。
     *
     * @param string $key 组件键名
     * @param string $label 组件标签
     * @return Combo 组合控件对象
     */
    public function componentSelect(string $key, string $label = ''): Combo
    {
        // 构建组件属性相关的名称和ID
        $comboName = $key . '_property';
        $comboId = $comboName . '_id';

        return amis()->Combo($key, $label)->items([
            amis()
                ->Service()
                ->initFetchOn('${!!' . $key . '_type}')
                ->api('post:/dev_tools/code_generator/get_property_options?c=${' . $key . '_type}&t=' . $key)
                ->body([
                    amis('group')->body([
                        amis()
                            ->Select($key . '_type', translator('admin.admin_menu.type'))
                            ->searchable()
                            ->id($key)
                            ->clearable()
                            ->size('lg')
                            ->source('${component_options}')
                            ->set('columnRatio', 8)
                            ->onEvent([
                                'change' => [
                                    'actions' => [
                                        [
                                            'actionType' => 'clear',
                                            'componentId' => $comboId,
                                            'expression' => '${!!' . $comboName . '}',
                                        ],
                                    ],
                                ],
                            ])->description(translator('admin.code_generators.name_label_desc')),

                        amis()->Group()->body([
                            amis()
                                ->Action()->actionType('drawer')
                                ->label(translator('admin.code_generators.load_config'))
                                ->level('primary')
                                ->set('columnRatio', 4)
                                ->drawer(
                                    amis()
                                        ->Drawer()
                                        ->title(translator('admin.code_generators.load_config'))
                                        ->bodyClassName('p-0')
                                        ->actions([])
                                        ->id('load_config_dialog')
                                        ->closeOnOutside()
                                        ->body(
                                            amis()
                                                ->Service()
                                                ->name('component_property_list_service')
                                                ->api('post:/dev_tools/code_generator/component_property/list?key=' . $comboName . '&c=${' . $key . '_type}')
                                                ->body(
                                                    amis()
                                                        ->CRUD()
                                                        ->className('border-none')
                                                        ->loadDataOnce()
                                                        ->source('${component_property_list}')
                                                        ->columns([
                                                            amis()
                                                                ->TableColumn('label', translator('admin.admin_role.name'))
                                                                ->searchable(),

                                                            amis()
                                                                ->Operation()
                                                                ->label(translator('admin.actions'))
                                                                ->buttons([
                                                                    // 填充按钮
                                                                    amis()
                                                                        ->Button()
                                                                        ->label(translator('admin.code_generators.fill'))
                                                                        ->level('primary')
                                                                        ->onEvent([
                                                                            'click' => [
                                                                                'actions' => [
                                                                                    [
                                                                                        'actionType' => 'setValue',
                                                                                        'componentId' => $comboId,
                                                                                        'args' => ['value' => '${value}'],
                                                                                    ],
                                                                                    [
                                                                                        'actionType' => 'setValue',
                                                                                        'componentId' => $key,
                                                                                        'args' => ['value' => '${key}'],
                                                                                    ],
                                                                                    [
                                                                                        'actionType' => 'closeDialog',
                                                                                        'componentId' => 'load_config_dialog',
                                                                                    ],
                                                                                ],
                                                                            ],
                                                                        ]),

                                                                    // 删除按钮
                                                                    amis()
                                                                        ->Action()->actionType('ajax')
                                                                        ->label(translator('admin.delete'))
                                                                        ->level('danger')
                                                                        ->confirmText(translator('admin.confirm_delete'))
                                                                        ->reload('component_property_list_service')
                                                                        ->api('post:/dev_tools/code_generator/component_property/del?name=' . $comboName),
                                                                ])
                                                                ->set('width', 150),
                                                        ])
                                                )
                                        )
                                ),
                            amis()
                                ->Action()->actionType('dialog')
                                ->label(translator('admin.code_generators.save_current_config'))
                                ->level('success')
                                ->set('columnRatio', 8)
                                ->dialog(
                                    amis()
                                        ->Dialog()
                                        ->title(translator('admin.code_generators.save_current_config'))
                                        ->body(
                                            amis()
                                                ->Form()
                                                ->mode('normal')
                                                ->api('post:/dev_tools/code_generator/component_property/save')
                                                ->body([
                                                    amis()->Hidden('key')->value($comboName),
                                                    amis()->Combo('value')->items([
                                                        amis()
                                                            ->InputText('label')
                                                            ->inline(false)
                                                            ->required()
                                                            ->placeholder(translator('admin.code_generators.input_config_name'))
                                                            ->description(translator('admin.code_generators.same_name_tips')),
                                                        amis()->Hidden('key')->value('${' . $key . '_type}'),
                                                        amis()->Hidden('value')->value('${' . $comboName . '}'),
                                                    ]),
                                                ])
                                        )
                                ),
                        ])->set('columnRatio', 4),
                    ]),

                    amis()
                        ->Combo($comboName, translator('admin.code_generators.property'))
                        ->id($comboId)
                        ->multiple()
                        ->strictMode(false)
                        ->items([
                            amis()
                                ->InputText('name', translator('admin.code_generators.property_name'))
                                ->required()
                                ->set('unique', true)
                                ->size('md')
                                ->clearable()
                                ->source('${component_property_options}'),
                            amis()->InputText('value', translator('admin.code_generators.value'))->size('md'),
                        ]),
                ]),
        ]);
    }

    /**
     * 字段表单
     *
     * 构建用于配置数据表字段的复杂表单，
     * 包括基本信息、列表组件、列表筛选、表单组件、
     * 详情组件和模型配置等多个标签页。
     *
     * @return Card 卡片对象
     */
    public function columnForm(): Card
    {
        // 设置组件的 Tab
        $componentSchema = function ($title, $tips, $key) {
            return amis()->Tabs()->title($title)->body([
                amis()->Alert()->level('info')->showIcon()->body($tips),
                amis()->Divider(),
                $this->componentSelect($key)->mode('normal'),
            ]);
        };

        return amis()->Card()->body([
            amis()
                ->Alert()
                ->body(translator('admin.code_generators.column_warning') . " <a href='https://github.com/sym134/warm/issues' target='_blank'>" . translator('admin.show') . "</a> ")
                ->level('warning')
                ->showCloseButton()
                ->showIcon(),
            amis()
                ->InputSubForm('columns', false)
                ->multiple()
                ->btnLabel('${"<div class=\'column-name\'>"+ name + "</div><div class=\'text-success\'>" + type +"</div><div class=\'item-comment\'>"+ comment +"</div>"}')
                ->minLength(1)
                ->draggable()
                ->addable()
                ->removable()
                ->itemClassName('custom-subform-item')
                ->addButtonText(translator('admin.code_generators.add_column'))
                ->form(
                    amis()
                        ->Form()
                        ->set('title', translator('admin.code_generators.add_column'))
                        ->size('lg')
                        ->id('column_form')
                        ->body([
                            // 基本信息标签页
                            amis()->Tabs()->title(translator('admin.code_generators.base_info'))->body([
                                amis()->Group()->body([
                                    amis()
                                        ->InputText('name', translator('admin.code_generators.column_name'))
                                        ->required(),
                                    amis()
                                        ->Select('type', translator('admin.code_generators.type'))
                                        ->options(Generator::make()->availableFieldTypes())
                                        ->searchable()
                                        ->value('string')
                                        ->required(),
                                ]),

                                amis()->Group()->body([
                                    amis()
                                        ->InputText('comment', translator('admin.code_generators.comment'))
                                        ->value(),
                                    amis()->InputText('default', translator('admin.code_generators.default_value')),
                                ]),

                                amis()->Group()->body([
                                    amis()
                                        ->InputText('additional', translator('admin.code_generators.extra_params'))
                                        ->labelRemark(
                                            translator('admin.code_generators.remark1') .
                                            "<a href='https://learnku.com/docs/laravel/9.x/migrations/12248#b419dd' target='_blank'>" .
                                            translator('admin.code_generators.remark2') .
                                            "</a>, " . translator('admin.code_generators.remark3')
                                        ),
                                    amis()
                                        ->Select('column_index', translator('admin.code_generators.index'))
                                        ->options(
                                            collect(['index', 'unique'])->map(fn($value) => [
                                                'label' => $value,
                                                'value' => $value,
                                            ]))
                                        ->clearable(),
                                ]),

                                amis()->Switch('nullable', translator('admin.code_generators.nullable')),
                                amis()
                                    ->Checkboxes('action_scope', translator('admin.code_generators.scope'))
                                    ->options([
                                        ['label' => translator('admin.list'), 'value' => 'list'],
                                        ['label' => translator('admin.detail'), 'value' => 'detail'],
                                        ['label' => translator('admin.create'), 'value' => 'create'],
                                        ['label' => translator('admin.edit'), 'value' => 'edit'],
                                    ])
                                    ->joinValues(false)
                                    ->extractValue()
                                    ->checkAll()
                                    ->defaultCheckAll(),
                            ]),
                            // 列表组件标签页
                            $componentSchema(
                                translator('admin.code_generators.list_component'),
                                translator('admin.code_generators.list_component_desc'),
                                'list_component'
                            ),
                            // 列表筛选标签页
                            amis()->Tabs()->title(translator('admin.code_generators.list_filter'))->tabs([
                                amis()->Combo('list_filter')->items([
                                    amis()
                                        ->Select('type', translator('admin.code_generators.filter_type'))
                                        ->options(map2options(FilterGenerator::$filterMap))
                                        ->required(),
                                    amis()
                                        ->Radios('mode', translator('admin.code_generators.filter_mode'))
                                        ->selectFirst()
                                        ->options([
                                            'fixed' => translator('admin.code_generators.filter_mode_fixed'),
                                            'input' => translator('admin.code_generators.filter_mode_input'),
                                        ]),
                                    amis()
                                        ->InputText('value', translator('admin.code_generators.filter_mode_fixed_value'))
                                        ->visibleOn('${mode == "fixed"}'),
                                    amis()
                                        ->InputText('input_name', translator('admin.code_generators.filter_input_name'))
                                        ->visibleOn('${mode == "input"}')
                                        ->required(),
                                    amis()
                                        ->InputText('input_label', translator('admin.code_generators.filter_input_label'))
                                        ->visibleOn('${mode == "input"}'),
                                    $this
                                        ->componentSelect('filter', translator('admin.code_generators.filter_component'))
                                        ->visibleOn('${mode == "input"}')
                                        ->value([
                                            'filter_type' => 'InputText',
                                            'filter_property' => [
                                                ['name' => 'size', 'value' => 'md'],
                                                ['name' => 'clearable', 'value' => 1],
                                            ],
                                        ]),
                                ])->multiple()->multiLine()->mode('normal'),
                            ]),
                            // 表单组件标签页
                            $componentSchema(
                                translator('admin.code_generators.form_component'),
                                translator('admin.code_generators.form_component_desc'),
                                'form_component'
                            ),
                            // 详情组件标签页
                            $componentSchema(
                                translator('admin.code_generators.detail_component'),
                                translator('admin.code_generators.detail_component_desc'),
                                'detail_component'
                            ),
                            // 模型配置标签页
                            amis()->Tabs()->title(translator('admin.code_generators.model_config'))->tabs([
                                amis()
                                    ->Switch('file_column', translator('admin.code_generators.file_column'))
                                    ->value(0)
                                    ->description(translator('admin.code_generators.file_column_desc')),
                                amis()
                                    ->Switch('file_column_multi', translator('admin.code_generators.file_column_multi'))
                                    ->value(0)
                                    ->visibleOn('${file_column}'),
                            ]),
                        ])
                ),
        ]);
    }

    /**
     * 预览代码 按钮
     *
     * 创建一个用于预览生成代码的对话框按钮，
     * 展示控制器、服务、模型和迁移文件的代码。
     *
     * @return Action 对话框按钮对象
     */
    public function previewCodeAction(): Action
    {
        // 定义编辑器标签页回调函数
        $editorTab = function ($column) {
            return amis()->Tabs()->title(Str::title($column))->tabs([
                amis()->Editor($column)->language('php')->disabled()->size('xxl')
            ]);
        };

        return amis()
            ->Action()->actionType('dialog')
            ->label(translator('admin.code_generators.preview'))
            ->level('link')
            ->dialog(
                amis()->Dialog()->size('lg')->title(translator('admin.code_generators.preview_code'))->body(
                    amis()->Service()->api('post:/dev_tools/code_generator/preview?id=${id}')->body(
                        amis()->Tabs()->tabs([
                            $editorTab('controller'),
                            $editorTab('service'),
                            $editorTab('model'),
                            $editorTab('migration'),
                        ])
                    )
                )
            );
    }

    /**
     * 克隆记录 按钮
     *
     * 创建一个用于克隆代码生成记录的对话框按钮，
     * 可以指定新的表名和应用标题。
     *
     * @return Action 对话框按钮对象
     */
    public function cloneAction(): Action
    {
        return amis()
            ->Action()->actionType('dialog')
            ->label(translator('admin.code_generators.clone_record'))
            ->level('link')
            ->dialog(
                amis()->Dialog()->title(translator('admin.code_generators.clone_record'))->body([
                    amis()->Form()->wrapWithPanel(false)->api('post:/dev_tools/code_generator/clone')->body([
                        amis()->Hidden('id'),
                        amis()
                            ->InputText('table_name', translator('admin.code_generators.new_table_name'))
                            ->required(),
                        amis()->InputText('title', translator('admin.code_generators.new_app_title'))->required(),
                    ]),
                ])
            );
    }

    /**
     * 克隆记录
     *
     */
    public function clone(): Response
    {
        $this->service->clone(request()->all());

        return $this->response()->successMessage(translator('admin.action_success'));
    }

    /**
     * 复制记录 按钮
     *
     * 创建一个用于复制代码生成记录JSON数据的对话框按钮，
     * 方便在其他地方导入使用。
     *
     * @return Action 对话框按钮对象
     */
    public function copyRecordAction(): Action
    {
        return amis()
            ->Action()->actionType('dialog')
            ->label(translator('admin.code_generators.copy_record'))
            ->level('link')
            ->dialog(
                amis()->Dialog()->title(false)->body(
                    amis()
                        ->Form()
                        ->initApi('post:/dev_tools/code_generator/get_record?id=${id}')
                        ->mode('normal')
                        ->body([
                            amis()->Textarea('record')
                                ->disabled()
                                ->description(translator('admin.code_generators.copy_record_description'))
                        ]),
                )->actions([
                    amis()->Button()->actionType('cancel')->label(translator('admin.cancel')),
                    amis()
                        ->Action()->actionType('copy')
                        ->label(translator('admin.copy'))
                        ->level('success')
                        ->content('${ENCODEJSON(record)}'),
                ])
            );
    }

    /**
     * 生成代码 按钮
     *
     * 创建一个用于生成代码的对话框按钮，
     * 可以选择需要生成的组件。
     *
     * @return Action 对话框按钮对象
     */
    public function generateCodeAction(): Action
    {
        return amis()
            ->Action()->actionType('dialog')
            ->level('link')
            ->label(translator('admin.code_generators.generate_code'))
            ->iconClassName('pr-4')
            ->dialog(
                amis()->Dialog()->title(translator('admin.code_generators.select_generate_record'))->body([
                    amis()->Form()->api('post:/dev_tools/code_generator/generate?id=${id}')->mode('normal')->body([
                        amis()
                            ->Checkboxes('needs')
                            ->checkAll()
                            ->inline(false)
                            ->required()
                            ->options(Generator::make()->needCreateOptions()),
                    ])->feedback(
                        amis()
                            ->Dialog()->title(' ')->bodyClassName('overflow-auto')
                            ->size('lg')
                            ->body(amis()->Tpl()->tpl('${result | raw}'))
                            ->onEvent([
                                'confirm' => [
                                    'actions' => [
                                        ['actionType' => 'custom', 'script' => 'window.$owl.refreshRoutes()'],
                                    ],
                                ],
                                'cancel' => [
                                    'actions' => [
                                        ['actionType' => 'custom', 'script' => 'window.$owl.refreshRoutes()'],
                                    ],
                                ],
                            ])
                    ),
                ])
            );
    }

    /**
     * 清除代码 按钮
     *
     * 创建一个用于清除已生成代码的对话框按钮，
     * 可以选择需要清除的组件。
     *
     * @return Action 对话框按钮对象
     */
    public function clearCodeAction(): Action
    {
        return amis()
            ->Action()->actionType('dialog')
            ->level('link')
            ->label(translator('admin.code_generators.clear_code'))
            ->dialog(
                amis()->Dialog()->title(translator('admin.code_generators.select_clear_record'))->body([
                    amis()->Form()->api('post:/dev_tools/code_generator/clear?id=${id}')->mode('normal')->body([
                        amis()
                            ->Checkboxes('selected')
                            ->checkAll()
                            ->inline(false)
                            ->required()
                            ->menuTpl('<div><div class="font-bold">${label}</div><div class="break-words break-all text-sm text-gray-400">${content}</div></div>')
                            ->source('post:/dev_tools/code_generator/gen_record_options?id=${id}'),
                    ])->onEvent([
                        'submitSucc' => [
                            'actions' => [['actionType' => 'custom', 'script' => 'window.$owl.refreshRoutes()']],
                        ],
                    ]),
                ])
            );
    }

    /**
     * 清除代码
     *
     * 调用GenCodeClear类的handle方法清除已生成的代码文件。
     *
     * @return Response 响应对象
     */
    public function clear(): Response
    {
        // 调用代码清理处理方法
        GenCodeClear::make()->handle(request()->all());

        // 返回成功消息
        return $this->response()->successMessage(translator('admin.action_success'));
    }

    /**
     * 获取生成的内容
     *
     * 获取指定记录ID的生成内容列表，
     * 用于在清除代码功能中展示可清除的项目。
     *
     * @return Response 响应对象，包含生成内容选项
     */
    public function genRecordOptions(): Response
    {
        // 获取指定ID的记录信息
        $list = GenCodeClear::make()->getRecord(request()->input('id'));

        // 格式化选项数据
        $options = collect($list)->except(['menu_id'])->map(fn($item, $index) => [
            'label' => Str::headline($index),
            'value' => $index,
            'content' => is_array($item) ? implode("\n", $item) : $item,
            'hidden' => blank($item),
        ])->values();

        // 返回成功响应，包含选项数据
        return $this->response()->success($options);
    }

    /**
     * 编辑记录
     *
     * 处理代码生成记录的编辑操作，
     * 在获取数据时会合并表单数据。
     *
     * @param mixed $id 记录ID
     * @return Response 响应对象
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function edit(mixed $id): Response
    {
        // 判断是否为获取数据的请求
        if ($this->actionOfGetData()) {
            // 获取记录编辑数据
            $data = $this->service->getEditData($id)->toArray();

            // 合并表单数据
            $data = array_merge($data, $this->formData(true));

            // 返回成功响应，包含数据
            return $this->response()->success($data);
        }

        // 调用父类的编辑方法
        return parent::edit($id);
    }

    /**
     * 页面样式
     *
     * 定义代码生成器页面的自定义CSS样式，
     * 用于美化字段配置表单地显示效果。
     *
     * @return array[] CSS样式数组
     */
    private function css(): array
    {
        return [
            '.cxd-Table-content' => ['padding-bottom' => '15px'],
            '.item-comment' => [
                'width' => '220px',
                'height' => '18px',
                'overflow' => 'hidden',
                'text-overflow' => 'ellipsis',
                'white-space' => 'nowrap',
                'color' => '#a9aeb8',
                'font-size' => '12px',
            ],
            '.column-name' => [
                'max-width' => '160px',
                'overflow' => 'hidden',
                'text-overflow' => 'ellipsis',
                'white-space' => 'nowrap',
                'font-weight' => 'bold',
            ],
            '.custom-subform-item' => [
                'border' => '1px solid #eee',
                'border-radius' => 'var(--borderRadius)',
                'margin' => '5px',
                'width' => '16%',
                'padding' => '10px',
                'min-width' => '220px',
                'position' => 'relative',
            ],
            '.custom-subform-item .cxd-SubForm-valueDragBar' => [
                'position' => 'absolute',
                'top' => '5px',
                'left' => '10px',
            ],
            '.custom-subform-item .cxd-SubForm-valueLabel' => [
                'margin-left' => '20px',
            ],
            '.custom-subform-item .cxd-SubForm-valueEdit' => [
                'position' => 'absolute',
                'top' => '5px',
                'right' => '30px',
            ],
            '.custom-subform-item .cxd-SubForm-valueDel' => [
                'position' => 'absolute',
                'top' => '5px',
                'right' => '10px',
            ],
            '.border-none' => [
                'border' => 'none !important',
            ],
        ];
    }
}