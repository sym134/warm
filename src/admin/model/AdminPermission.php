<?php

namespace warm\admin\model;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Str;
use support\Request;
use warm\admin\Admin;
use warm\common\model\BaseModel;

/**
 * 管理权限模型类
 * 
 * 该模型用于管理系统权限，包括：
 * 1. 权限的基本信息（标识、名称等）
 * 2. 权限关联的HTTP方法和路径
 * 3. 权限关联的菜单项
 * 
 * 支持权限验证和请求匹配功能。
 */
class AdminPermission extends BaseModel
{
    /**
     * 支持的HTTP方法列表
     * 
     * @var array
     */
    public static array $httpMethods = [
        'GET',
        'POST',
        'PUT',
        'DELETE',
        'PATCH',
        'OPTIONS',
        'HEAD',
    ];

    /**
     * 需要进行类型转换的字段
     * 
     * 将数据库中的JSON字符串自动转换为PHP数组
     * 
     * @var array
     */
    protected $casts = [
        'http_method' => 'array',  // HTTP方法列表
        'http_path'   => 'array',  // HTTP路径列表
    ];

    /**
     * 可以批量赋值的属性
     * 
     * @var array
     */
    protected $fillable = ['sign'];

    /**
     * 权限关联的菜单项
     * 
     * 定义权限与菜单项的多对多关联关系
     * 一个权限可以关联多个菜单项，一个菜单项也可以关联多个权限
     * 
     * @return BelongsToMany 菜单关联关系
     */
    public function menus(): BelongsToMany
    {
        return $this->belongsToMany(AdminMenu::class, 'admin_permission_menu', 'permission_id', 'menu_id')
            ->withTimestamps();
    }

    /**
     * 判断请求是否应该通过权限检查
     * 
     * 根据权限配置的HTTP方法和路径，判断当前请求是否应该通过权限检查：
     * 1. 如果权限没有配置方法和路径，则允许通过
     * 2. 否则检查请求是否匹配配置的路径和方法
     * 
     * @param Request $request HTTP请求对象
     * @return bool 是否应该通过权限检查
     */
    public function shouldPassThrough(Request $request): bool
    {
        // 如果没有配置HTTP方法和路径，则允许通过
        if (empty($this->http_method) && empty($this->http_path)) {
            return true;
        }
        
        // 获取权限配置的方法和路径
        $method  = $this->http_method;
        $matches = array_map(function ($path) use ($method) {
            // 添加路由前缀到路径
            $path = trim(Admin::warmConfig('app.route.prefix'), '/') . $path;
            if (Str::contains($path, ':')) {
                // 如果路径包含方法，则分离方法和路径
                [$method, $path] = explode(':', $path);
                $method = explode(',', $method);
            }
            return compact('method', 'path');
        }, $this->http_path);
        
        // 检查请求是否匹配任何一个配置的路径和方法
        foreach ($matches as $match) {
            if ($this->matchRequest($match, $request)) {
                return true;
            }
        }
        return false;
    }

    /**
     * 匹配请求与配置规则
     * 
     * 检查请求是否匹配指定的路径和方法规则
     * 
     * @param array $match 匹配规则（包含method和path）
     * @param Request $request HTTP请求对象
     * @return bool 是否匹配
     */
    protected function matchRequest(array $match, Request $request): bool
    {
        // 处理根路径的特殊情况
        if ($match['path'] == '/') {
            $path = '/';
        } else {
            $path = trim($match['path'], '/');
        }
        
        // 检查路径是否匹配
        if (!collect($path)->contains(fn ($pattern) => Str::is($pattern, trim($request->path(),'/')))) { //
            return false;
        }
        
        // 处理方法匹配
        $method = collect($match['method'])->filter()->map(function ($method) {
            return strtoupper($method);
        });
        
        // 如果没有指定方法或方法匹配，则返回true
        return $method->isEmpty() || $method->contains($request->method()); //
    }

    /**
     * 模型启动时的初始化操作
     * 
     * 注册删除事件监听器，在删除权限时同时删除关联的菜单关系
     * 
     * @return void
     */
    protected static function boot(): void
    {
        parent::boot();
        static::deleting(function ($model) {
            // 删除权限时，同时删除与菜单的关联关系
            $model->menus()->detach();
        });
    }
}