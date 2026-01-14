<?php

namespace warm\admin\service;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use support\Container;
use support\Db;
use support\Request;
use Throwable;
use warm\admin\renderer\Page;
use warm\admin\renderer\TableColumn;
use warm\admin\trait\ErrorTrait;

/**
 * 管理服务基类
 * 
 * 所有管理服务类的基类，提供通用的增删改查功能和钩子方法
 */
abstract class AdminService
{
    use ErrorTrait;

    /**
     * 数据表字段列表
     * 
     * @var array
     */
    protected array $tableColumn = [];

    /**
     * 模型类名
     * 
     * @var string
     */
    protected string $modelName;

    /**
     * 请求对象
     * 
     * @var Request|null
     */
    protected Request|null $request;

    protected ?Model $currentModel = null;

    /**
     * 构造函数
     * 
     * 初始化请求对象
     */
    public function __construct()
    {
        $this->request = request();
    }

    /**
     * 创建服务实例
     * 
     * @return static 服务实例
     */
    public static function make(): static
    {
        return new static;
    }

    /**
     * 设置模型类名
     * 
     * @param string $modelName 模型类名
     * @return void
     */
    public function setModelName(string $modelName): void
    {
        $this->modelName = $modelName;
    }

    /**
     * 获取模型实例
     *
     * @return Model 模型实例
     */
    public function getModel(): Model
    {
        return new $this->modelName;
    }

    /**
     * 获取当前操作的数据实例（新增/修改后）
     *
     * @return Model|null
     */
    public function getCurrentModel(): ?Model
    {
        return $this->currentModel;
    }

    /**
     * 设置当前操作的数据实例
     *
     * @param Model|null $model
     * @return $this
     */
    public function setCurrentModel(?Model $model): static
    {
        $this->currentModel = $model;
        return $this;
    }

    public function primaryKey(): string
    {
        return $this->getModel()->getKeyName();
    }

    /**
     * 获取数据表字段列表
     * 
     * @return array 字段列表
     */
    public function getTableColumns(): array
    {
        if (!$this->tableColumn) {
            try {
                // laravel11: sqlite 暂时无法获取字段, 等待 laravel 适配
                $this->tableColumn = DB::schema($this->getModel()->getConnectionName())
                    ->getColumnListing($this->getModel()->getTable());
            } catch (Throwable) {
                $this->tableColumn = [];
            }
        }

        return $this->tableColumn;
    }

    /**
     * 检查字段是否存在
     *
     * @param string|null $column 字段名
     * @return bool 字段是否存在
     */
    public function hasColumn(string|null $column): bool
    {
        $columns = $this->getTableColumns();

        if (blank($columns)) return true;

        return in_array($column, $columns);
    }

    /**
     * 获取查询构造器
     * 
     * @return mixed 查询构造器
     */
    public function query(): mixed
    {
        return $this->modelName::query();
    }

    /**
     * 详情 获取数据
     *
     * @param mixed $id 数据ID
     * @return Builder|Builder[]|Collection|Model|null 详情数据
     */
    public function getDetail(mixed $id): Model|Collection|Builder|array|null
    {
        $query = $this->query();

        $this->addRelations($query, 'detail');

        return $query->find($id);
    }

    /**
     * 编辑 获取数据
     *
     * @param mixed $id 数据ID
     * @return Model|Collection|Builder|array|null 编辑数据
     */
    public function getEditData(mixed $id): Model|Collection|Builder|array|null
    {
        $model = $this->getModel();

        $hidden = collect([$model->getCreatedAtColumn(), $model->getUpdatedAtColumn()])
            ->filter(fn($item) => $item !== null)
            ->toArray();

        $query = $this->query();

        $this->addRelations($query, 'edit');

        return $query->find($id)->makeHidden($hidden);
    }

    /**
     * 列表 获取查询
     *
     * @return mixed 查询构造器
     */
    public function listQuery(): mixed
    {
        $query = $this->query();

        // 处理排序
        $this->sortable($query);

        // 自动加载 TableColumn 内的关联关系
        $this->loadRelations($query);

        // 处理查询
        $this->searchable($query);

        // 追加关联关系
        $this->addRelations($query);

        return $query;
    }

    /**
     * 添加关联关系
     *
     * 预留钩子, 方便处理只需要添加 [关联] 的情况
     *
     * @param mixed $query 查询构造器
     * @param string $scene 场景: list, detail, edit
     * @return void
     */
    public function addRelations(mixed $query, string $scene = 'list')
    {

    }

    /**
     * 根据 tableColumn 定义的列, 自动加载关联关系
     *
     * @param mixed $query 查询构造器
     * @return void
     */
    public function loadRelations(mixed $query): void
    {
        $controller = Container::make(request()->route->getCallback()[0], []);

        // 当前列表结构
        $schema = method_exists($controller, 'list') ? $controller->list() : '';

        if (!$schema instanceof Page) return;

        // 字段
        $columns = $schema->toArray()['body']->amisSchema['columns'] ?? [];

        $relations = [];
        foreach ($columns as $column) {
            // 排除非表格字段
            if (!$column instanceof TableColumn) continue;
            // 拆分字段名
            $field = $column->amisSchema['name'] ?? null;
            if (!$field) continue;
            // 是否是多层级
            if (str_contains($field, '.')) {
                // 去除字段名
                $list = array_slice(explode('.', $field), 0, -1);
                try {
                    $_class = $this->modelName;
                    foreach ($list as $item) {
                        $_class = app($_class)->{$item}()->getModel()::class;
                    }
                } catch (Throwable) {
                    continue;
                }
                $relations[] = implode('.', $list);
            }
        }

        // 加载关联关系
        $query->with(array_unique($relations));
    }

    /**
     * 排序
     *
     * @param mixed $query 查询构造器
     * @return void
     */
    public function sortable(mixed $query): void
    {
        if (request()->input('orderBy') && request()->input('orderDir')) {
            $query->orderBy(request()->input('orderBy'), request()->input('orderDir') ?? 'asc');
        } else {
            $query->orderByDesc($this->sortColumn());
        }
    }

    /**
     * 搜索
     *
     * @param mixed $query 查询构造器
     * @return void
     */
    public function searchable(mixed $query): void
    {
        collect(array_keys(request()->all()))
            ->intersect($this->getTableColumns())
            ->map(function ($field) use ($query) {
                $query->when(filled(request()->input($field)), function ($query) use ($field) {
                    $query->where($field, 'like', '%' . request()->input($field) . '%');
                });
            });
    }

    /**
     * 列表 排序字段
     *
     * @return mixed 排序字段名
     */
    public function sortColumn(): mixed
    {
        $updatedAtColumn = $this->getModel()->getUpdatedAtColumn();

        if ($this->hasColumn($updatedAtColumn)) {
            return $updatedAtColumn;
        }

        if ($this->hasColumn($this->getModel()->getKeyName())) {
            return $this->getModel()->getKeyName();
        }

        return Arr::first($this->getTableColumns());
    }

    /**
     * 格式化列表数据
     *
     * @param array $rows 一次分页的数据
     *
     * @return array
     */
    public function formatRows(array $rows): array
    {
        return $rows;
    }

    /**
     * 列表 获取数据
     *
     * @return array 列表数据
     */
    public function list(): array
    {
        $query = $this->listQuery();

        $list  = $query->paginate(request()->input('perPage', 20));
        $items = $this->formatRows($list->items());
        $total = $list->total();

        return compact('items', 'total');
    }

    /**
     * 修改
     *
     * @param mixed $primaryKey 主键值
     * @param array $data 更新的数据
     * @return bool 是否更新成功
     */
    public function update(mixed $primaryKey, array $data): bool
    {
        Db::beginTransaction();
        try {
            $this->saving($data, $primaryKey);

            $model = $this->query()->whereKey($primaryKey)->first();

            foreach ($data as $k => $v) {
                if (!$this->hasColumn($k)) {
                    continue;
                }

                $model->setAttribute($k, $v);
            }

            $result = $model->save();

            // 无论数据是否变更,都赋值当前模型实例
            $this->currentModel = $model;

            if ($result) {
                $this->saved($model, true);
            }

            Db::commit();
        } catch (Throwable $e) {
            Db::rollBack();

            admin_abort($e->getMessage());
        }

        return $result;
    }

    /**
     * 新增
     *
     * @param array $data 存储的数据
     * @return bool 是否存储成功
     */
    public function store(array $data): bool
    {
        Db::beginTransaction();
        try {
            $this->saving($data);

            $model = $this->getModel();

            foreach ($data as $k => $v) {
                if (!$this->hasColumn($k)) {
                    continue;
                }

                $model->setAttribute($k, $v);
            }

            $result = $model->save();

            // 无论是否保存成功,都赋值当前模型实例
            $this->currentModel = $model;

            if ($result) {
                $this->saved($model);
            }

            Db::commit();
        } catch (Throwable $e) {
            Db::rollBack();

            admin_abort($e->getMessage());
        }

        return $result;
    }

    /**
     * 删除
     *
     * @param string|int $ids 删除的ID列表
     * @return bool 是否删除成功
     */
    public function delete(string|int $ids): bool
    {
        Db::beginTransaction();
        try {
            $result = $this->query()->whereIn($this->primaryKey(), explode(',', $ids))->delete();

            if ($result) {
                $this->deleted($ids);
            }

            Db::commit();
        } catch (Throwable $e) {
            Db::rollBack();
            admin_abort($e->getMessage());
        }


        return $result;
    }

    /**
     * 快速编辑
     *
     * @param array $data 编辑的数据
     * @return bool 是否编辑成功
     */
    public function quickEdit(array $data): bool
    {
        $rowsDiff = data_get($data, 'rowsDiff', []);
        Db::beginTransaction();
        try {
            foreach ($rowsDiff as $item) {
                $this->update(Arr::pull($item, $this->primaryKey()), $item);
            }
            Db::commit();
        } catch (Throwable $e) {
            Db::rollBack();
            admin_abort($e->getMessage());
        }

        return true;
    }

    /**
     * 快速编辑单条
     *
     * @param array $data 编辑的数据
     * @return bool 是否编辑成功
     */
    public function quickEditItem(array $data): bool
    {
        return $this->update(Arr::pull($data, $this->primaryKey()), $data);
    }

    /**
     * saving 钩子 (执行于新增/修改前)
     *
     * 可以通过判断 $primaryKey 是否存在来判断是新增还是修改
     *
     * @param array $data 保存的数据
     * @param string $primaryKey 主键值
     * @return void
     */
    public function saving(array &$data, string $primaryKey = '')
    {

    }

    /**
     * saved 钩子 (执行于新增/修改后)
     *
     * 可以通过 $isEdit 来判断是新增还是修改
     *
     * @param mixed $model 保存的模型实例
     * @param bool $isEdit 是否为编辑操作
     * @return void
     */
    public function saved(mixed $model, bool $isEdit = false)
    {

    }

    /**
     * deleted 钩子 (执行于删除后)
     *
     * @param string $ids 删除的ID列表
     * @return void
     */
    public function deleted(string $ids)
    {

    }
}
