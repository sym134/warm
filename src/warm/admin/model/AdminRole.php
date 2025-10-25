<?php

namespace warm\admin\model;

use Illuminate\Database\Eloquent\Concerns\HasTimestamps;
use warm\common\model\BaseModel;

/**
 * 管理角色模型类
 * 
 * 该模型用于管理系统中的角色信息，包括：
 * 1. 角色的基本信息（名称、标识等）
 * 2. 角色关联的权限
 * 
 * 支持角色与权限的多对多关联关系。
 */
class AdminRole extends BaseModel
{
    use HasTimestamps;

    /**
     * 角色关联的权限
     * 
     * 定义角色与权限的多对多关联关系
     * 一个角色可以拥有多个权限，一个权限也可以分配给多个角色
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany 权限关联关系
     */
    public function permissions(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(AdminPermission::class, 'admin_role_permissions', 'role_id', 'permission_id')
            ->withTimestamps();
    }

    /**
     * 模型启动时的初始化操作
     * 
     * 注册删除事件监听器，在删除角色时同时删除关联的权限关系
     * 
     * @return void
     */
    protected static function boot(): void
    {
        parent::boot();
        static::deleting(function (AdminRole $model) {
            // 删除角色时，同时删除与权限的关联关系
            $model->permissions()->detach();
        });
    }
}