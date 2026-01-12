<?php

namespace warm\admin\controller\dev_tools;

use Illuminate\Support\Str;
use support\Response;
use warm\admin\controller\AdminController;
use warm\admin\renderer\DialogAction;
use warm\admin\renderer\Form;
use warm\admin\renderer\Page;
use warm\admin\service\AdminApiService;
use warm\admin\support\apis\AdminBaseApi;
use warm\admin\support\cores\Api;

/**
 * API管理控制器
 * 
 * 负责处理API接口的配置和管理功能，包括：
 * 1. API记录的增删改查
 * 2. API模板管理
 * 3. API预览功能
 * 
 * @property AdminApiService $service API管理服务类实例
 */
class ApiController extends AdminController
{
    protected string $serviceName = AdminApiService::class;

    /**
     * API记录列表页面
     * 
     * 构建并返回API记录的列表页面，包含：
     * 1. 数据表格展示API记录
     * 2. 工具栏按钮（创建、添加模板等）
     * 3. 行操作按钮（编辑、删除、预览）
     * 
     * @return Page 页面对象
     */
    public function list(): Page
    {
        // 构建CRUD表格
        $crud = $this
            ->baseCRUD()
            ->filterTogglable(false)
            ->headerToolbar([
                $this->createButton(true, 'lg'),
                ...$this->baseHeaderToolBar(),
                $this->appTemplateBtn(),
            ])
            ->columns([
                amis()->TableColumn('id', 'ID')->sortable(),
                amis()->TableColumn('title', translator('admin.apis.title'))->searchable(),
                amis()->TableColumn('path', translator('admin.apis.path'))->searchable(),
                amis()->TableColumn('template_title', translator('admin.apis.template')),
                amis()->TableColumn('enabled', translator('admin.apis.enabled'))->quickEdit(
                    amis()->Switch()->mode('inline')->saveImmediately(true)
                ),
                amis()->TableColumn('updated_at', translator('admin.updated_at'))->type('datetime')->sortable(true),
                $this->rowActions([
                    $this->rowEditButton(true, 'lg'),
                    $this->rowDeleteButton(),
                    $this->previewAction(),
                ]),
            ]);

        // 返回基础列表页面
        return $this->baseList($crud);
    }

    /**
     * API预览操作按钮
     * 
     * 创建一个用于预览API返回结果的对话框按钮，
     * 仅对启用的GET请求API可见。
     * 
     * @return DialogAction 对话框按钮对象
     */
    public function previewAction(): DialogAction
    {
        return amis()
            ->DialogAction()
            ->label(translator('admin.preview'))
            ->size('md')
            ->level('link')
            ->visibleOn('${method == "get" && enabled}')
            ->dialog(
                amis()
                    ->Dialog()
                    ->title(translator('admin.preview'))
                    ->actions([])
                    ->closeOnOutside()
                    ->closeOnEsc()
                    ->body([
                        amis()->Service()->api('/${path}')->body([
                            amis()->Json()->source('${&}')->levelExpand(3),
                        ]),
                    ])
            );
    }

    /**
     * 添加模板按钮
     * 
     * 创建一个用于添加API模板的对话框按钮。
     * 
     * @return DialogAction 对话框按钮对象
     */
    public function appTemplateBtn(): DialogAction
    {
        return amis()
            ->DialogAction()
            ->label(translator('admin.apis.add_template'))
            ->level('success')
            ->icon('fa fa-upload')
            ->dialog(
                amis()->Dialog()->title(translator('admin.apis.add_template'))->body([
                    amis()->Form()->mode('normal')->api('/dev_tools/api/add_template')->body([
                        amis()
                            ->Textarea('template')
                            ->required()
                            ->minRows(10)
                            ->description(translator('admin.apis.add_template_tips'))
                            ->placeholder(translator('admin.apis.paste_template')),
                        amis()->Switch('overlay', translator('admin.apis.overlay'))->value(1),
                    ]),
                ])
            );
    }

    /**
     * 添加模板
     *
     * 将用户提供的API模板代码保存为PHP文件。
     *
     * @return Response 响应对象
     */
    public function addTemplate(): Response
    {
        // 获取模板代码
        $template = request()->input('template');
        // 从模板代码中提取类名
        $className = Str::between($template, 'class ', ' extends AdminBaseApi');
        if (!$className) {
            $className = Str::between($template, 'class ', ' extends \warm\support\apis\AdminBaseApi');
        }

        // 如果没有提取到类名，则返回错误
        admin_abort_if(!$className, translator('admin.apis.template_format_error'));

        // 获取模板文件路径
        $file = Api::path($className . '.php');

        // 如果文件已存在且不允许覆盖，则返回错误
        admin_abort_if(is_file($file) && !request()->input('overlay'), translator('admin.apis.template_exists'));

        try {
            // 获取文件目录
            $dir = dirname($file);

            // 如果目录不存在，则创建目录
            if (!is_dir($dir)) {
                (new \Illuminate\Filesystem\Filesystem)->makeDirectory($dir, 0755, true);
            }
            // 保存模板文件
            (new \Illuminate\Filesystem\Filesystem)->put($file, $template);
        } catch (\Throwable $e) {
            // 如果出现异常，则返回保存失败信息
            return $this->response()->fail(translator('admin.save_failed'));
        }

        // 返回保存成功信息
        return $this->response()->successMessage(translator('admin.save_success'));
    }

    /**
     * API配置表单
     * 
     * 构建用于创建和编辑API配置的表单，包含：
     * 1. API标题
     * 2. API路径
     * 3. 启用状态
     * 4. 模板选择
     * 5. 参数配置
     * 
     * @return Form 表单对象
     */
    public function form(): Form
    {
        return $this->baseForm()->body([
            // API标题输入框
            amis()->InputText('title', translator('admin.apis.title'))->required(),
            // API路径输入框
            amis()->InputText('path', translator('admin.apis.path'))->required(),
            // 启用状态开关
            amis()->Switch('enabled', translator('admin.apis.enabled'))->value(1),
            // 模板选择下拉框
            amis()->Select('template', translator('admin.apis.template'))
                ->required()
                ->searchable()
                ->source('/dev_tools/api/templates'),
            // 参数配置组合框
            amis()->Combo('args', translator('admin.apis.args'))
                ->visibleOn('${template}')
                ->multiLine()
                ->strictMode(false)
                ->items([
                    amis()->Service()->initFetch()->schemaApi('get:/dev_tools/api/args_schema?template=${template}'),
                ]),
        ]);
    }

    /**
     * API详情页面
     * 
     * 构建并返回API记录的详情页面。
     * 
     * @param mixed $id API记录ID
     * @return Form 表单对象
     */
    public function detail(mixed $id): Form
    {
        return $this->baseDetail()->body([]);
    }

    /**
     * 获取模板列表
     * 
     * 获取系统中所有API模板的列表，用于前端下拉选择。
     * 
     * @return Response 响应对象，包含模板列表
     */
    public function template(): Response
    {
        // 获取所有API模板
        $apis = collect(Api::getAllApis())
            ->filter(fn($item) => (new \ReflectionClass($item))->isSubclassOf(AdminBaseApi::class))
            ->map(fn($item) => [
                'label' => app($item)->getMethod() . ' - ' . app($item)->getTitle(),
                'value' => $item,
            ]);

        // 返回成功响应，包含模板列表
        return $this->response()->success($apis);
    }

    /**
     * 获取参数结构
     * 
     * 根据选择的模板获取对应的参数配置结构。
     * 
     * @return Response 响应对象，包含参数结构
     */
    public function argsSchema(): Response
    {
        // 获取模板对应的参数结构
        $schema = app(request()->input('template'))->argsSchema();

        // 如果结构为空，则设置为null
        if (blank($schema)) {
            $schema = null;
        }

        // 返回成功响应，包含参数结构
        return $this->response()->success($schema);
    }
}