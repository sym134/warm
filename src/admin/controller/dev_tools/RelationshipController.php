<?php

namespace warm\admin\controller\dev_tools;

use Exception;
use support\Db;
use support\Response;
use Throwable;
use warm\admin\controller\AdminController;
use warm\admin\model\AdminRelationship;
use warm\admin\renderer\DrawerAction;
use warm\admin\renderer\Form;
use warm\admin\renderer\Page;
use warm\admin\service\AdminRelationshipService;
use warm\admin\support\cores\Database;

/**
 * 关系管理控制器
 *
 * 负责处理模型关系的配置和管理功能，包括：
 * 1. 关系记录的增删改查
 * 2. 模型代码生成
 * 3. 关系预览功能
 *
 * @property AdminRelationshipService $service 关系管理服务类实例
 */
class RelationshipController extends AdminController
{
    protected string $serviceName = AdminRelationshipService::class;

    /**
     * 关系记录列表页面
     *
     * 构建并返回关系记录的列表页面，包含：
     * 1. 数据表格展示关系记录
     * 2. 工具栏按钮（创建、模型生成等）
     * 3. 行操作按钮（预览、编辑、删除）
     *
     * @return Page 页面对象
     */
    public function list(): Page
    {
        // 构建CRUD表格
        $crud = $this->baseCRUD()
            ->filterTogglable(false)
            ->headerToolbar([
                $this->createButton(true, 'lg'),
                ...$this->baseHeaderToolBar(),
                $this->modelGenerator(),
            ])
            ->columns([
                amis()->TableColumn('id', 'ID')->sortable(),
                amis()->TableColumn('model', translator('admin.relationships.model'))->searchable(),
                amis()->TableColumn('title', translator('admin.relationships.title'))->searchable(),
                amis()->TableColumn('remark', translator('admin.relationships.remark'))->searchable(),
                $this->rowActions([
                    $this->previewButton(),
                    $this->rowEditButton(true, 'lg'),
                    $this->rowDeleteButton(),
                ]),
            ]);

        // 返回基础列表页面
        return $this->baseList($crud);
    }

    /**
     * 模型生成器
     *
     * 创建一个抽屉式表单，用于选择数据库表并生成对应的模型文件。
     *
     * @return DrawerAction 抽屉按钮对象
     */
    public function modelGenerator(): DrawerAction
    {
        return amis()->DrawerAction()->label(translator('admin.relationships.generate_model'))->level('success')->drawer(
            amis()->Drawer()
                ->title(translator('admin.relationships.generate_model'))
                ->size('lg')
                ->resizable()
                ->closeOnOutside()
                ->closeOnEsc()
                ->actions([
                    amis()->Button()
                        ->label(translator('admin.relationships.generate'))
                        ->actionType('submit')
                        ->level('primary'),
                ])
                ->body([
                    amis()->Form()
                        ->api('/dev_tools/relation/generate_model')
                        ->initApi('/dev_tools/relation/all_models')
                        ->mode('normal')
                        ->body([
                            amis()->InputTree()
                                ->name('check_tables')
                                ->label()
                                ->multiple()
                                ->heightAuto()
                                ->required()
                                ->source('${all_models}')
                                ->searchable()
                                ->joinValues(false)
                                ->extractValue()
                                ->size('full')
                                ->className('h-full b-none')
                                ->inputClassName('h-full tree-full')
                                ->set('menuTpl', '${label} <span class="text-gray-300 pl-2">${model}</span>'),
                        ]),
                ])
        );
    }

    /**
     * 预览按钮
     *
     * 创建一个用于预览关系代码的抽屉按钮。
     *
     * @return DrawerAction 抽屉按钮对象
     */
    public function previewButton(): DrawerAction
    {
        return amis()->DrawerAction()->label(translator('admin.preview'))->level('link')->icon('fa fa-eye')->drawer(
            amis()->Drawer()
                ->position('top')
                ->resizable()
                ->title(translator('admin.preview'))
                ->actions([])
                ->showCloseButton(false)
                ->closeOnEsc()
                ->closeOnOutside()
                ->body(
                    amis()->Code()->value('${preview_code | raw}')->language('php')
                )
        );
    }

    /**
     * 关系配置表单
     *
     * 构建用于创建和编辑关系配置的表单，支持多种关系类型：
     * 1. 一对一 (HasOne)
     * 2. 一对多 (HasMany)
     * 3. 反向一对一/属于 (BelongsTo)
     * 4. 多对多 (BelongsToMany)
     * 5. 远程一对一 (HasOneThrough)
     * 6. 远程一对多 (HasManyThrough)
     * 7. 一对一多态 (MorphOne)
     * 8. 一对多多态 (MorphMany)
     * 9. 多对多多态 (MorphToMany)
     *
     * @return Form 表单对象
     */
    public function form(): Form
    {
        // 模型选择器组件
        $modelSelect = function ($name, $label) {
            return amis()
                ->Select($name, $label)
                ->required()
                ->menuTpl('${label} <span class="text-gray-300 pl-2">${table}</span>')
                ->source('/dev_tools/relation/model_options')
                ->searchable();
        };

        // 字段选择器组件
        $columnSelect = function ($name, $label, $modelField = "_blank_model", $tableField = '_blank_table') {
            return amis()
                ->InputText($name, $label)
                ->source('/dev_tools/relation/column_options?model=${' . $modelField . '}&table=${' . $tableField . '}');
        };

        // 关系参数配置组件
        $args = function ($type, $items) {
            return amis()
                ->Combo('args', translator('admin.relationships.args'))
                ->multiLine()
                ->strictMode(false)
                ->items($items)
                ->visibleOn('${type == "' . $type . '"}');
        };

        // 构建并返回表单
        return $this->baseForm()->data([
            'tables' => Database::getTables(),
        ])->body([
            amis()->Group()->body([
                amis()->Group()->direction('vertical')->body([
                    $modelSelect('model', translator('admin.relationships.model')),
                    amis()->InputText('title', translator('admin.relationships.title'))->required()->placeholder('comments'),
                    amis()->InputText('remark', translator('admin.relationships.remark')),
                    amis()->Select('type', translator('admin.relationships.type'))
                        ->required()
                        ->value(AdminRelationship::TYPE_BELONGS_TO)
                        ->menuTpl('${label} <span class="text-gray-300 pl-2">${method}</span>')
                        ->options(AdminRelationship::typeOptions()),
                ]),
                // 一对一关系配置
                $args(AdminRelationship::TYPE_HAS_ONE, [
                    // $related, $foreignKey = null, $localKey = null
                    $modelSelect('related', translator('admin.relationships.related_model')),
                    $columnSelect('foreignKey', 'foreignKey', 'related'),
                    $columnSelect('localKey', 'localKey', 'model'),
                ]),
                // 一对多关系配置
                $args(AdminRelationship::TYPE_HAS_MANY, [
                    // $related, $foreignKey = null, $localKey = null
                    $modelSelect('related', translator('admin.relationships.related_model')),
                    $columnSelect('foreignKey', 'foreignKey', 'related'),
                    $columnSelect('localKey', 'localKey', 'model'),
                ]),
                // 一对多(反向)/属于关系配置
                $args(AdminRelationship::TYPE_BELONGS_TO, [
                    // $related, $foreignKey = null, $ownerKey = null, $relation = null
                    $modelSelect('related', translator('admin.relationships.related_model')),
                    $columnSelect('foreignKey', 'foreignKey', 'model'),
                    $columnSelect('ownerKey', 'ownerKey', 'related'),
                    amis()->InputText('relation', 'relation'),
                ]),
                // 多对多关系配置
                $args(AdminRelationship::TYPE_BELONGS_TO_MANY, [
                    // $related, $table = null, $foreignPivotKey = null, $relatedPivotKey = null, $parentKey = null, $relatedKey = null, $relation = null
                    $modelSelect('related', translator('admin.relationships.related_model')),
                    amis()->Select('table', 'table')->source('${tables}')->searchable(),
                    $columnSelect('foreignPivotKey', 'foreignPivotKey', '_blank_model', 'table'),
                    $columnSelect('relatedPivotKey', 'relatedPivotKey', '_blank_model', 'table'),
                    $columnSelect('parentKey', 'parentKey', 'model'),
                    $columnSelect('relatedKey', 'relatedKey', 'related'),
                    amis()->InputText('relation', 'relation'),
                ]),
                // 远程一对一关系配置
                $args(AdminRelationship::TYPE_HAS_ONE_THROUGH, [
                    // $related, $through, $firstKey = null, $secondKey = null, $localKey = null, $secondLocalKey = null
                    $modelSelect('related', translator('admin.relationships.related_model')),
                    $modelSelect('through', translator('admin.relationships.through_model')),
                    $columnSelect('firstKey', 'firstKey', 'through'),
                    $columnSelect('secondKey', 'secondKey', 'related'),
                    $columnSelect('localKey', 'localKey', 'model'),
                    $columnSelect('secondLocalKey', 'secondLocalKey', 'through'),
                ]),
                // 远程一对多关系配置
                $args(AdminRelationship::TYPE_HAS_MANY_THROUGH, [
                    // $related, $through, $firstKey = null, $secondKey = null, $localKey = null, $secondLocalKey = null
                    $modelSelect('related', translator('admin.relationships.related_model')),
                    $modelSelect('through', translator('admin.relationships.through_model')),
                    $columnSelect('firstKey', 'firstKey', 'through'),
                    $columnSelect('secondKey', 'secondKey', 'related'),
                    $columnSelect('localKey', 'localKey', 'model'),
                    $columnSelect('secondLocalKey', 'secondLocalKey', 'through'),
                ]),
                // 一对一(多态)关系配置
                $args(AdminRelationship::TYPE_MORPH_ONE, [
                    // $related, $name, $type = null, $id = null, $localKey = null
                    $modelSelect('related', translator('admin.relationships.related_model')),
                    amis()->InputText('name', 'name')->required(),
                    amis()->InputText('type', 'type'),
                    amis()->InputText('id', 'id'),
                    $columnSelect('localKey', 'localKey', 'model'),
                ]),
                // 一对多(多态)关系配置
                $args(AdminRelationship::TYPE_MORPH_MANY, [
                    // $related, $name, $type = null, $id = null, $localKey = null
                    $modelSelect('related', translator('admin.relationships.related_model')),
                    amis()->InputText('name', 'name')->required(),
                    amis()->InputText('type', 'type'),
                    amis()->InputText('id', 'id'),
                    $columnSelect('localKey', 'localKey', 'model'),
                ]),
                // 多对多(多态)关系配置
                $args(AdminRelationship::TYPE_MORPH_TO_MANY, [
                    // $related, $name, $table = null, $foreignPivotKey = null, $relatedPivotKey = null, $parentKey = null, $relatedKey = null, $inverse = false
                    $modelSelect('related', translator('admin.relationships.related_model')),
                    amis()->InputText('name', 'name')->required(),
                    amis()->Select('table', 'table')->source('${tables}')->searchable(),
                    $columnSelect('foreignPivotKey', 'foreignPivotKey', '_blank_model', 'table'),
                    $columnSelect('relatedPivotKey', 'relatedPivotKey', '_blank_model', 'table'),
                    $columnSelect('parentKey', 'parentKey', 'model'),
                    $columnSelect('relatedKey', 'relatedKey', 'related'),
                    amis()->InputText('inverse', 'inverse'),
                ]),
            ]),
        ]);
    }

    /**
     * 关系详情页面
     *
     * 构建并返回关系记录的详情页面。
     *
     * @return Form 表单对象
     */
    public function detail(): Form
    {
        return $this->baseDetail()->body([]);
    }

    /**
     * 获取所有模型选项
     *
     * 获取系统中所有模型的选项列表，用于前端下拉选择。
     *
     * @return Response 响应对象，包含模型选项列表
     */
    public function modelOptions(): Response
    {
        try {
            // 获取所有模型
            $models = $this->service->allModels()['models'];

            // 返回成功响应
            return $this->response()->success($models);
        } catch (Exception $e) {
            // 返回失败响应
            return $this->response()->fail($e->getMessage());
        }
    }

    /**
     * 字段选项
     *
     * 根据模型或表名获取对应的字段列表，用于前端下拉选择。
     *
     * @return Response 响应对象，包含字段选项列表
     */
    public function columnOptions(): Response
    {
        // 获取请求参数
        $model = request()->input('model');
        $table = request()->input('table');

        // 如果模型和表都为空，则返回空数组
        if (blank($model) && blank($table)) {
            return $this->response()->success([]);
        }

        // 如果没有指定表名，则从模型获取表名
        $table = $table ?: $model::make()->getTable();

        // 获取表的字段列表
        $columns = Db::schema()->getColumnListing($table);

        // 返回成功响应，包含字段列表
        return $this->response()->success($columns);
    }

    /**
     * 获取所有模型信息
     *
     * 获取系统中所有模型的详细信息，包括已存在的模型和表信息。
     *
     * @return Response 响应对象，包含所有模型信息
     * @throws Exception
     */
    public function allModels(): Response
    {
        // 获取所有模型和表信息
        $all = $this->service->allModels();
        $tables = $all['tables'];
        $models = collect($all['models'])->keyBy('table');

        // 构建模型列表，标记已存在的模型
        $all_models = collect($tables)->map(function ($item) use ($models) {
            $model = data_get($models, $item . '.value');

            return [
                'value' => $item,
                'label' => $item,
                'model' => $model,
                'disabled' => (bool)$model,
            ];
        })->sortBy('disabled')->values();

        // 返回成功响应，包含所有模型信息
        return $this->response()->success(compact('all_models'));
    }

    /**
     * 生成模型
     *
     * 根据选择的表生成对应的模型文件。
     *
     * @return Response 响应对象
     * @throws Exception
     */
    public function generateModel(): Response
    {
        // 获取选中的表列表
        $tables = request()->input('check_tables');
        // 获取已存在的模型列表
        $existsList = collect($this->service->allModels()['models'])->pluck('table')->toArray();
        // 找出已存在的表
        $exists = array_intersect($tables, $existsList);

        // 如果有已存在的表，则返回错误信息
        admin_abort_if(filled($exists), translator('admin.relationships.model_exists') . implode(',', $exists));

        try {
            // 遍历表列表，逐个生成模型
            foreach ($tables as $table) {
                $this->service->generateModel($table);
            }
        } catch (Throwable $e) {
            // 如果出现异常，则返回失败信息
            return $this->response()->fail($e->getMessage());
        }

        // 返回成功信息
        return $this->response()->successMessage(translator('admin.action_success'));
    }
}