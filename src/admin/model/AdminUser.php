<?php

namespace warm\admin\model;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Collection;
use warm\admin\Admin;
use warm\common\model\BaseModel;
use warm\framework\filesystem\facade\Storage;

/**
 * 管理用户模型类
 * 
 * 该模型用于管理系统用户信息，包括：
 * 1. 用户的基本信息（用户名、密码、头像等）
 * 2. 用户关联的角色
 * 3. 用户权限检查功能
 * 
 * 支持用户与角色的多对多关联关系，以及权限验证功能。
 */
class AdminUser extends BaseModel
{
    /**
     * 需要追加到模型数组/JSON表示中的访问器
     * 
     * @var array
     */
    protected $appends = ['administrator'];
    
    /**
     * 不可批量赋值的属性
     * 
     * @var array
     */
    protected $guarded = [];

    /**
     * 构造函数
     * 
     * 设置数据库连接并调用父类构造函数
     * 
     * @param array $attributes 模型属性
     */
    public function __construct(array $attributes = [])
    {
        // 设置数据库连接
        $this->setConnection(Admin::warmConfig('app.database.connection'));

        parent::__construct($attributes);
    }

    /**
     * 用户关联的角色
     * 
     * 定义用户与角色的多对多关联关系
     * 一个用户可以拥有多个角色，一个角色也可以分配给多个用户
     * 
     * @return BelongsToMany 角色关联关系
     */
    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(AdminRole::class, 'admin_role_users', 'user_id', 'role_id')->withTimestamps();
    }

    /**
     * 用户头像访问器
     * 
     * 处理用户头像的获取和设置：
     * 1. 获取时，如果头像存在则返回完整URL，否则返回默认头像
     * 2. 设置时，移除存储服务的URL前缀，只保存相对路径
     * 
     * @return Attribute 头像属性访问器
     */
    public function avatar(): Attribute
    {
        return Attribute::make(
            get: fn($value) => $value ? Storage::url($value) : url(Admin::warmConfig('app.default_avatar')),
            set: fn($value) => str_replace(Storage::url(''), '', $value)
        );
    }

    /**
     * 模型启动时的初始化操作
     * 
     * 注册删除事件监听器，在删除用户时同时删除关联的角色关系
     * 
     * @return void
     */
    protected static function boot(): void
    {
        parent::boot();
        static::deleting(function (AdminUser $model) {
            // 删除用户时，同时删除与角色的关联关系
            $model->roles()->detach();
        });
    }

    /**
     * 获取用户所有权限
     * 
     * 通过用户关联的角色获取所有权限集合
     * 
     * @return Collection 权限集合
     */
    public function allPermissions(): Collection
    {
        return $this->roles()->with('permissions')->get()->pluck('permissions')->flatten();
    }

    /**
     * 检查用户是否具有指定权限
     * 
     * 权限检查逻辑：
     * 1. 如果权限为空，则返回true
     * 2. 如果用户是超级管理员，则返回true
     * 3. 否则检查用户角色的权限中是否包含指定权限
     * 
     * @param mixed $abilities 权限标识
     * @param array $arguments 其他参数
     * @return bool 是否具有权限
     */
    public function can(mixed $abilities, array $arguments = []): bool
    {
        // 如果权限为空，则允许访问
        if (empty($abilities)) {
            return true;
        }

        // 如果用户是超级管理员，则允许访问
        if ($this->isAdministrator()) {
            return true;
        }

        // 检查用户角色的权限中是否包含指定权限
        return $this->roles->pluck('permissions')->flatten()->pluck('slug')->contains($abilities);
    }

    /**
     * 检查用户是否为超级管理员
     * 
     * @return bool 是否为超级管理员
     */
    public function isAdministrator(): bool
    {
        return $this->isRole('administrator');
    }

    /**
     * 检查用户是否具有指定角色
     * 
     * @param string $role 角色标识
     * @return bool 是否具有指定角色
     */
    public function isRole(string $role): bool
    {
        return $this->roles->pluck('slug')->contains($role);
    }

    /**
     * 检查用户是否具有指定角色中的任意一个
     * 
     * @param array $roles 角色标识数组
     * @return bool 是否具有指定角色中的任意一个
     */
    public function inRoles(array $roles = []): bool
    {
        return $this->roles->pluck('slug')->intersect($roles)->isNotEmpty();
    }

    /**
     * 检查用户对指定角色是否可见
     * 
     * 可见性检查逻辑：
     * 1. 如果用户是超级管理员，则始终可见
     * 2. 如果角色列表为空，则不可见
     * 3. 否则检查用户是否具有指定角色中的任意一个
     * 
     * @param array $roles 角色数组
     * @return bool 是否可见
     */
    public function visible(array $roles = []): bool
    {
        // 如果用户是超级管理员，则始终可见
        if ($this->isAdministrator()) {
            return true;
        }
        
        // 如果角色列表为空，则不可见
        if (empty($roles)) {
            return false;
        }
        
        // 提取角色标识
        $roles = array_column($roles, 'slug');

        // 检查用户是否具有指定角色中的任意一个
        return $this->inRoles($roles);
    }

    /**
     * 管理员标识访问器
     * 
     * 获取用户是否为超级管理员的标识
     * 
     * @return Attribute 管理员标识属性访问器
     */
    public function administrator(): Attribute
    {
        return Attribute::get(fn() => $this->isAdministrator());
    }
}