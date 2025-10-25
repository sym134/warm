<?php

namespace warm\admin\service;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use warm\admin\Admin;
use warm\admin\model\AdminUser;
use warm\framework\support\facade\Hash;

/**
 * 管理用户服务类
 * 
 * 提供用户管理相关功能，包括用户验证、密码处理等
 * 
 * @method AdminUser getModel() 获取模型实例
 * @method AdminUser|Builder query() 获取查询构造器
 */
class AdminUserService extends AdminService
{
    /**
     * 构造函数
     * 
     * 初始化用户服务，设置模型名称
     */
    public function __construct()
    {
        parent::__construct();

        $this->modelName = Admin::adminUserModel();
    }

    /**
     * 获取编辑数据
     * 
     * @param mixed $id 数据ID
     * @return Model|Collection|Builder|array|null 用户数据
     */
    public function getEditData($id): Model|Collection|Builder|array|null
    {
        $adminUser = parent::getEditData($id)->makeHidden('password');

        $adminUser->load('roles');

        return $adminUser;
    }

    /**
     * 存储用户
     * 
     * @param array $data 存储的数据
     * @return bool 是否存储成功
     */
    public function store($data): bool
    {
        $this->checkUsernameUnique($data['username']);

        admin_abort_if(!data_get($data, 'password'), translator('admin.required', ['attribute' => translator('admin.password')]));

        $this->passwordHandler($data);

        $columns = $this->getTableColumns();

        $model = $this->getModel();

        return $this->saveData($data, $columns, $model);
    }

    /**
     * 更新用户
     * 
     * @param mixed $primaryKey 主键值
     * @param array $data 更新的数据
     * @return bool 是否更新成功
     */
    public function update($primaryKey, $data): bool
    {
        $this->checkUsernameUnique($data['username'], $primaryKey);
        $this->passwordHandler($data);

        $columns = $this->getTableColumns();

        $model = $this->query()->whereKey($primaryKey)->first();

        return $this->saveData($data, $columns, $model);
    }

    /**
     * 检查用户名是否唯一
     * 
     * @param string $username 用户名
     * @param int $id 用户ID
     * @return void
     */
    public function checkUsernameUnique($username, $id = 0): void
    {
        $exists = $this->query()
            ->where('username', $username)
            ->when($id, fn($query) => $query->where('id', '<>', $id))
            ->exists();

        admin_abort_if($exists, translator('admin.admin_user.username_already_exists'));
    }

    /**
     * 更新用户设置
     * 
     * @param mixed $primaryKey 主键值
     * @param array $data 更新的数据
     * @return bool 是否更新成功
     */
    public function updateUserSetting($primaryKey, $data): bool
    {
        $this->passwordHandler($data, $primaryKey);

        return parent::update($primaryKey, $data);
    }

    /**
     * 密码处理
     * 
     * @param array $data 用户数据
     * @param int|null $id 用户ID
     * @return void
     */
    public function passwordHandler(&$data, $id = null): void
    {
        $password = Arr::get($data, 'password');

        if ($password) {
            admin_abort_if($password !== Arr::get($data, 'confirm_password'), translator('admin.admin_user.password_confirmation'));

            if ($id) {
                admin_abort_if(!Arr::get($data, 'old_password'), translator('admin.admin_user.old_password_required'));

                $oldPassword = $this->query()->where('id', $id)->value('password');

                admin_abort_if(!Hash::check($data['old_password'], $oldPassword), translator('admin.admin_user.old_password_error'));
            }

            $data['password'] = password_hash($password,PASSWORD_DEFAULT);;

            unset($data['confirm_password']);
            unset($data['old_password']);
        }
    }

    /**
     * 获取用户列表
     * 
     * @return array 用户列表
     */
    public function list(): array
    {
        $keyword = request()->input('keyword');

        $query = $this->query()
            ->with('roles')
            ->select(['id', 'name', 'username', 'avatar', 'enabled', 'created_at'])
            ->when($keyword, function ($query) use ($keyword) {
                $query->where('username', 'like', "%{$keyword}%")->orWhere('name', 'like', "%{$keyword}%");
            });

        $this->sortable($query);

        $list = $query->paginate(request()->input('perPage', 20));
        $items = $list->items();
        $total = $list->total();

        return compact('items', 'total');
    }

    /**
     * 保存用户数据
     * 
     * @param array $data 用户数据
     * @param array $columns 数据表字段列表
     * @param AdminUser $model 用户模型实例
     * @return bool 是否保存成功
     */
    protected function saveData($data, array $columns, AdminUser $model): bool
    {
        $roles = Arr::pull($data, 'roles');

        foreach ($data as $k => $v) {
            if (!in_array($k, $columns)) {
                continue;
            }

            $model->setAttribute($k, $v);
        }

        if ($model->save()) {
            $model->roles()->sync(Arr::has($roles, '0.id') ? Arr::pluck($roles, 'id') : $roles);

            return true;
        }

        return false;
    }

    /**
     * 删除用户
     * 
     * @param string $ids 删除的ID列表
     * @return bool 是否删除成功
     */
    public function delete(string $ids): bool
    {
        $exists = $this->query()
            ->whereIn($this->primaryKey(), explode(',', $ids))
            ->whereHas('roles', fn($q) => $q->where('slug', 'administrator'))
            ->exists();

        admin_abort_if($exists, translator('admin.admin_user.cannot_delete'));

        return parent::delete($ids);
    }
}