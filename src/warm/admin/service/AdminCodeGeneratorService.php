<?php

namespace warm\admin\service;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Arr;
use ReflectionClass;
use warm\admin\Admin;
use warm\admin\model\AdminCodeGenerator;

/**
 * 代码生成器服务类
 * 
 * 提供代码生成相关功能，包括数据表验证、数据过滤、路径处理等
 * 
 * @method AdminCodeGenerator getModel() 获取模型实例
 * @method AdminCodeGenerator|Builder query() 获取查询构造器
 */
class AdminCodeGeneratorService extends AdminService
{
    /**
     * 模型类名
     * 
     * @var string
     */
    protected string $modelName = AdminCodeGenerator::class;

    /**
     * 列表查询处理
     * 
     * 添加关键词搜索功能
     * 
     * @return Builder 查询构造器
     */
    public function listQuery(): Builder
    {
        $keyword = request()->input('keyword');// webman

        return parent::listQuery()->when($keyword, function ($query) use ($keyword) {
            $query->where(function ($q) use ($keyword) {
                $q->where('table_name', 'like', "%{$keyword}%")->orWhere('title', 'like', "%{$keyword}%");
            });
        });
    }

    /**
     * 存储数据
     * 
     * 验证表名是否已存在并过滤数据后存储
     * 
     * @param array $data 存储的数据
     * @return bool 是否存储成功
     */
    public function store(array $data): bool
    {
        amis_abort_if($this->query()->where('table_name', $data['table_name'])->exists(), translator('admin.code_generators.exists_table'));

        return parent::store($this->filterData($data));
    }

    /**
     * 更新数据
     * 
     * 验证表名是否已存在并过滤数据后更新
     * 
     * @param mixed $primaryKey 主键值
     * @param array $data 更新的数据
     * @return bool 是否更新成功
     */
    public function update(mixed $primaryKey, array $data): bool
    {
        $exists = $this->query()
            ->where('table_name', $data['table_name'])
            ->where($this->primaryKey(), '<>', $primaryKey)
            ->exists();

        amis_abort_if($exists, translator('admin.code_generators.exists_table'));

        return parent::update($primaryKey, $this->filterData($data));
    }

    /**
     * 过滤数据
     * 
     * 对传入的数据进行处理和验证，确保数据格式正确
     * 
     * @param array $data 原始数据
     * @return array 过滤后的数据
     */
    public function filterData(array $data): array
    {
        admin_abort_if(
            !data_get($data, 'columns'),
            translator('admin.required', ['attribute' => translator('admin.code_generators.column_info')])
        );

        admin_abort_if(
            collect($data['columns'])->pluck('name')->unique()->count() != count($data['columns']),
            translator('admin.code_generators.duplicate_column')
        );

        $data['columns'] = collect($data['columns'])
            ->map(fn($item) => Arr::except($item, ['component_options']))
            ->toArray();

        if (in_array('need_create_table', $data['needs'])) {
            $data['needs'][] = 'need_database_migration';
            $data['needs'] = array_unique($data['needs']);
        }

        $data['page_info']['list_display_created_at'] = $data['list_display_created_at'] ?? 1;
        $data['page_info']['list_display_updated_at'] = $data['list_display_updated_at'] ?? 1;

        foreach ($data['columns'] as &$columnItem) {
            if (data_get($columnItem, 'list_component.component_property_options')) {
                unset($columnItem['list_component']['component_property_options']);
            }
            if (data_get($columnItem, 'form_component.component_property_options')) {
                unset($columnItem['form_component']['component_property_options']);
            }
            if (data_get($columnItem, 'detail_component.component_property_options')) {
                unset($columnItem['detail_component']['component_property_options']);
            }
        }

        return Arr::except($data, [
            'table_info',
            'table_primary_keys',
            'exists_tables',
            'menu_tree',
            'component_options',
            'save_path_options',
            'default_path',
            // 'save_path',
        ]);
    }

    /**
     * 获取命名空间
     *
     * @param string $name 命名空间名称
     * @param mixed|null $app 应用标识
     * @return string 命名空间路径
     */
    public function getNamespace(string $name, mixed $app = null): string
    {
        $namespace = collect(explode('\\', Admin::warmConfig('app.route.namespace')));

        $namespace->pop();

        // if ($app && !Admin::currentModule()) {
        //     $namespace->pop();
        // }

        return $namespace->push($name)->implode('/') . '/';
    }

    /**
     * 获取默认路径配置
     * 
     * @return array 默认路径配置数组
     */
    public function getDefaultPath(): array
    {
        return [
            'label' => translator('admin.code_generators.save_path_dir'),
            'value' => [
                'directory'       => 'app',
                'controller_path' => $this->getNamespace('controller'),
                'service_path'    => $this->getNamespace('service', 1),
                'model_path'      => $this->getNamespace('model', 1),
            ],
        ];
    }

    /**
     * 获取组件选项
     * 
     * @return array 组件选项数组
     */
    public function getComponentOptions(): array
    {
        return collect(get_class_methods(amis()))
            ->filter(fn($item) => $item != 'make')
            ->map(function ($item) {
                $renderer = new ReflectionClass('\\warm\\admin\\renderer\\' . $item);
                $_doc = $renderer->getDocComment();
                $_doc = preg_replace("/[^\x{4e00}-\x{9fa5}]/u", "", $_doc);
                $_doc = $_doc ? trim(str_replace('文档', '', $_doc)) : '';
                $label = $_doc ? $item . ' - ' . $_doc : $item;

                return [
                    'label' => $label,
                    'value' => $item,
                ];
            })
            ->values()
            ->toArray();
    }

    public function clone($data): void
    {
        $tableNameExists = $this->query()->where('table_name', $data['table_name'])->exists();

        admin_abort_if($tableNameExists, translator('admin.code_generators.exists_table'));

        $original = $this->query()->find($data['id']);

        $new = $original->replicate();

        $new->table_name = $data['table_name'];
        $new->title      = $data['title'];

        $new->save();
    }
}