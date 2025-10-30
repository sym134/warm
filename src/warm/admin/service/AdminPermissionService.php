<?php

namespace warm\admin\service;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use warm\admin\Admin;
use warm\admin\model\AdminPermission;

/**
 * 管理权限服务类
 * 
 * 提供权限管理相关功能，包括权限树形结构处理、权限验证等
 * 
 * @method AdminPermission getModel() 获取模型实例
 * @method AdminPermission|Builder query() 获取查询构造器
 */
class AdminPermissionService extends AdminService
{
    /**
     * 构造函数
     * 
     * 初始化权限服务，设置模型名称
     */
    public function __construct()
    {
        parent::__construct();

        $this->modelName = Admin::adminPermissionModel();
    }

    /**
     * 获取权限树形结构
     * 
     * @return array 权限树形结构数组
     */
    public function getTree(): array
    {
        $list = $this->query()->orderBy('order')->get()->toArray();

        return array2tree($list);
    }

    /**
     * 检查父级权限是否为子权限
     * 
     * 防止出现循环嵌套的情况
     * 
     * @param int $id 权限ID
     * @param int $parent_id 父级权限ID
     * @return bool 是否为子权限
     */
    public function parentIsChild($id, $parent_id): bool
    {
        $parent = $this->query()->find($parent_id);

        do {
            if ($parent->parent_id == $id) {
                return true;
            }
            // 如果没有parent 则为顶级 退出循环
            $parent = $parent->parent;
        } while ($parent);

        return false;
    }

    /**
     * 获取编辑数据
     * 
     * @param mixed $id 数据ID
     * @return Model|Collection|Builder|array|null 权限数据
     */
    public function getEditData(mixed $id): Model|Collection|Builder|array|null
    {
        $permission = parent::getEditData($id);

        $permission->load(['menus']);

        return $permission;
    }

    /**
     * 存储权限
     * 
     * @param array $data 存储的数据
     * @return bool 是否存储成功
     */
    public function store(array $data): bool
    {
        $this->checkRepeated($data);

        $columns = $this->getTableColumns();

        $model = $this->getModel();

        return $this->saveData($data, $columns, $model);
    }

    /**
     * 更新权限
     * 
     * @param mixed $primaryKey 主键值
     * @param array $data 更新的数据
     * @return bool 是否更新成功
     */
    public function update(mixed $primaryKey, array $data): bool
    {
        $this->checkRepeated($data, $primaryKey);

        $columns = $this->getTableColumns();

        $parent_id = Arr::get($data, 'parent_id');
        if ($parent_id != 0) {
            amis_abort_if($this->parentIsChild($primaryKey, $parent_id), translator('admin.admin_permission.parent_id_not_allow'));
        }

        $model = $this->query()->whereKey($primaryKey)->first();

        return $this->saveData($data, $columns, $model);
    }

    /**
     * 检查权限是否重复
     * 
     * @param array $data 权限数据
     * @param int $id 权限ID
     * @return void
     */
    public function checkRepeated($data, $id = 0): void
    {
        $query = $this->query()->when($id, fn($query) => $query->where('id', '<>', $id));

        amis_abort_if($query->clone()->where('name', $data['name'])
            ->exists(), translator('admin.admin_permission.name_already_exists'));

        amis_abort_if($query->clone()->where('slug', $data['slug'])
            ->exists(), translator('admin.admin_permission.slug_already_exists'));
    }

    /**
     * 获取权限列表
     * 
     * @return array 权限列表数组
     */
    public function list(): array
    {
        return ['items' => $this->getTree()];
    }

    /**
     * 保存权限数据
     * 
     * @param array $data 权限数据
     * @param array $columns 数据表字段列表
     * @param AdminPermission $model 权限模型实例
     * @return bool 是否保存成功
     */
    protected function saveData($data, array $columns, AdminPermission $model): bool
    {
        $menus = Arr::pull($data, 'menus');

        foreach ($data as $k => $v) {
            if (!in_array($k, $columns)) {
                continue;
            }

            $model->setAttribute($k, $v);
        }

        if ($model->save()) {
            $model->menus()->sync(Arr::has($menus, '0.id') ? Arr::pluck($menus, 'id') : $menus);

            return true;
        }

        return false;
    }
}