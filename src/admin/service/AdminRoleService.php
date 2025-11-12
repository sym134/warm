<?php

namespace warm\admin\service;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use warm\admin\Admin;
use warm\admin\model\AdminRole;

/**
 * 管理角色服务类
 * 
 * 提供角色管理相关功能，包括角色验证、权限关联等
 * 
 * @method AdminRole getModel() 获取模型实例
 * @method AdminRole|Builder query() 获取查询构造器
 */
class AdminRoleService extends AdminService
{
    /**
     * 构造函数
     * 
     * 初始化角色服务，设置模型名称
     */
    public function __construct()
    {
        parent::__construct();

        $this->modelName = Admin::adminRoleModel();
    }

    /**
     * 获取编辑数据
     * 
     * @param mixed $id 数据ID
     * @return Model|Collection|Builder|array|null 角色数据
     */
    public function getEditData(mixed $id): Model|Collection|Builder|array|null
    {
        $permission = parent::getEditData($id);

        $permission->load(['permissions']);

        return $permission;
    }

    /**
     * 存储角色
     * 
     * @param array $data 存储的数据
     * @return bool 是否存储成功
     */
    public function store(array $data): bool
    {
        $this->checkRepeated($data);

        $columns = $this->getTableColumns();

        $model = $this->getModel();

        foreach ($data as $k => $v) {
            if (!in_array($k, $columns)) {
                continue;
            }

            $model->setAttribute($k, $v);
        }

        return $model->save();
    }

    /**
     * 更新角色
     * 
     * @param mixed $primaryKey 主键值
     * @param array $data 更新的数据
     * @return bool 是否更新成功
     */
    public function update(mixed $primaryKey, array $data): bool
    {
        $this->checkRepeated($data, $primaryKey);

        $columns = $this->getTableColumns();

        $model = $this->query()->whereKey($primaryKey)->first();

        foreach ($data as $k => $v) {
            if (!in_array($k, $columns)) {
                continue;
            }

            $model->setAttribute($k, $v);
        }

        return $model->save();
    }

    /**
     * 检查角色是否重复
     * 
     * @param array $data 角色数据
     * @param int $id 角色ID
     * @return void
     */
    public function checkRepeated($data, $id = 0): void
    {
        $query = $this->query()->when($id, fn($query) => $query->where('id', '<>', $id));

        amis_abort_if($query->clone()
            ->where('name', $data['name'])
            ->exists(), translator('admin.admin_role.name_already_exists'));

        amis_abort_if($query->clone()
            ->where('slug', $data['slug'])
            ->exists(), translator('admin.admin_role.slug_already_exists'));
    }

    /**
     * 保存角色权限
     * 
     * @param mixed $primaryKey 主键值
     * @param array $permissions 权限列表
     * @return mixed 保存结果
     */
    public function savePermissions($primaryKey, $permissions)
    {
        $model = $this->query()->whereKey($primaryKey)->first();

        return $model->permissions()->sync(
            Arr::has($permissions, '0.id') ? Arr::pluck($permissions, 'id') : $permissions
        );
    }

    /**
     * 删除角色
     *
     * @param string|int $ids 删除的ID列表
     * @return bool 是否删除成功
     */
    public function delete(string|int $ids): bool
    {
        $_ids   = explode(',', $ids);
        $exists = $this->query()
            ->whereIn($this->primaryKey(), $_ids)
            ->where('slug', 'administrator')
            ->exists();

        admin_abort_if($exists, translator('admin.admin_role.cannot_delete'));

        $used = $this->query()
            ->whereIn($this->primaryKey(), $_ids)
            ->has('users')
            ->exists();

        admin_abort_if($used, translator('admin.admin_role.used'));


        return parent::delete($ids);
    }
}