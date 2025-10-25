<?php

namespace warm\admin\service;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Arr;
use support\Db as DB;
use warm\admin\Admin;
use warm\admin\model\AdminMenu;

/**
 * 管理菜单服务类
 * 
 * 提供菜单管理相关功能，包括菜单树形结构处理、菜单验证、排序等
 * 
 * @method AdminMenu getModel() 获取模型实例
 * @method AdminMenu|Builder query() 获取查询构造器
 */
class AdminMenuService extends AdminService
{
    /**
     * 构造函数
     * 
     * 初始化菜单服务，设置模型名称
     */
    public function __construct()
    {
        parent::__construct();

        $this->modelName = Admin::adminMenuModel();
    }

    /**
     * 获取菜单树形结构
     * 
     * @return array 菜单树形结构数组
     */
    public function getTree(): array
    {
        $list = $this->query()->orderBy('order')->get()->toArray();

        return array2tree($list);
    }

    /**
     * 检查父级菜单是否为子菜单
     * 
     * 防止出现循环嵌套的情况
     * 
     * @param int $id 菜单ID
     * @param int $parent_id 父级菜单ID
     * @return bool 是否为子菜单
     */
    public function parentIsChild($id, $parent_id): bool
    {
        if($id == $parent_id){
            return true;
        }

        $parent = $this->query()->find($parent_id);

        do {
            if ($parent->parent_id == $id) {
                return true;
            }
            // 如果没有parent 则为顶级菜单 退出循环
            $parent = $parent->parent;
        } while ($parent);

        return false;
    }

    /**
     * 更新菜单
     * 
     * @param mixed $primaryKey 主键值
     * @param array $data 更新的数据
     * @return bool 是否更新成功
     */
    public function update($primaryKey, $data): bool
    {
        $columns = $this->getTableColumns();

        $parent_id = Arr::get($data, 'parent_id');
        if ($parent_id != 0) {
            amis_abort_if($this->parentIsChild($primaryKey, $parent_id), translator('admin.admin_menu.parent_id_not_allow'));
        }

        $model = $this->query()->whereKey($primaryKey)->first();

        $data['id'] = $primaryKey;

        return $this->saveData($data, $columns, $model);
    }

    /**
     * 存储菜单
     * 
     * @param array $data 存储的数据
     * @return bool 是否存储成功
     */
    public function store($data): bool
    {
        $columns = $this->getTableColumns();
        $model = $this->getModel();
        return $this->saveData($data, $columns, $model);
    }

    /**
     * 更改首页菜单
     * 
     * @param int $excludeId 需要排除的菜单ID
     * @return void
     */
    public function changeHomePage($excludeId = 0): void
    {
        $this->query()->when($excludeId, fn($query) => $query->where('id', '<>', $excludeId))->update(['is_home' => 0]);
    }

    /**
     * 获取菜单列表
     * 
     * @return array 菜单列表数组
     */
    public function list(): array
    {
        return ['items' => $this->getTree()];
    }

    /**
     * 保存菜单数据
     * 
     * @param array $data 菜单数据
     * @param array $columns 数据表字段列表
     * @param AdminMenu $model 菜单模型实例
     * @return bool 是否保存成功
     */
    protected function saveData($data, array $columns, AdminMenu $model): bool
    {
        $urlExists = $this->query()
            ->where('url', data_get($data, 'url'))
            ->when(data_get($data, 'id'), fn($q) => $q->where('id', '<>', data_get($data, 'id')))
            ->exists();

        admin_abort_if($urlExists, translator('admin.admin_menu.url_exists'));

        foreach ($data as $k => $v) {
            if (!in_array($k, $columns)) {
                continue;
            }

            $v = $k == 'parent_id' ? intval($v) : $v;

            $model->setAttribute($k, $v);

            if ($k == 'is_home' && $v == 1) {
                $this->changeHomePage($model->getKey());
            }
        }
        return $model->save();
    }

    /**
     * 重新排序菜单
     *
     * @param string $ids 排序ID列表
     * @return false|int 更新结果
     */
    public function reorder($ids): bool|int
    {
        if (blank($ids)) {
            return false;
        }

        $ids = json_decode('[' . str_replace('[', ',[', $ids) . ']');

        $list = collect($this->refreshOrder($ids))->transform(fn($i) => $i * 10)->all();

        $sql = 'update ' . $this->getModel()->getTable() . ' set `order` = case id ';

        foreach ($list as $k => $v) {
            $sql .= " when {$k} then {$v} ";
        }

        return DB::update($sql . ' else `order` end');
    }

    /**
     * 刷新菜单排序
     * 
     * @param array $list 排序列表
     * @return array 刷新后的排序结果
     */
    public function refreshOrder($list)
    {
        $result = collect($list)->filter(fn($i) => !is_array($i))->values()->flip()->toArray();

        collect($list)->filter(fn($i) => is_array($i))->each(function ($item) use (&$result) {
            $result = $this->refreshOrder($item) + $result;
        });

        return $result;
    }
}