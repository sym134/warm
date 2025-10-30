<?php

namespace warm\admin\service;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use warm\admin\model\AdminApi;
use warm\admin\model\AdminCodeGenerator;
use warm\admin\support\code_generator\RouteGenerator;

/**
 * 管理后台API服务类
 * 
 * 提供API管理相关功能，包括API路径验证、路由生成等
 * 
 * @method AdminApi getModel() 获取模型实例
 * @method AdminApi|Builder query() 获取查询构造器
 */
class AdminApiService extends AdminService
{
    /**
     * 模型类名
     * 
     * @var string
     */
    protected string $modelName = AdminApi::class;

    /**
     * 保存前处理
     * 
     * 验证API路径是否已存在，防止重复添加
     * 
     * @param array $data 保存的数据
     * @param string $primaryKey 主键值
     * @return void
     */
    public function saving(array &$data, string $primaryKey = ''): void
    {
        $exists = $this->query()
            ->where('path', $data['path'])
            ->when($primaryKey, fn($q) => $q->where('id', '<>', $primaryKey))
            ->exists();

        $routes = AdminCodeGenerator::query()->get()->map(function ($item) {
            return $item->menu_info['enabled'] ? ltrim($item->menu_info['route'], '/') : '';
        })->filter()->toArray();

        admin_abort_if($exists || in_array(ltrim($data['path'], '/'), $routes), translator('admin.apis.path_exists'));
    }

    /**
     * 保存后处理
     * 
     * 刷新路由配置
     * 
     * @param mixed $model 保存的模型实例
     * @param bool $isEdit 是否为编辑操作
     * @return void
     */
    public function saved(mixed $model, bool $isEdit = false): void
    {
        RouteGenerator::refresh();
    }

    /**
     * 删除后处理
     * 
     * 删除API后刷新路由配置
     * 
     * @param string $ids 删除的ID列表
     * @return void
     */
    public function deleted(string $ids): void
    {
        RouteGenerator::refresh();
    }

    /**
     * 根据路径获取API信息
     * 
     * @param string $path API路径
     * @return Model|static|null API模型实例或null
     */
    public function getApiByPath(string $path): Model|static|null
    {
        $api = $this->query()->where('path', $path)->first();

        if (!$api && str_starts_with($path, '/')) {
            $api = $this->query()->where('path', ltrim($path, '/'))->first();
        }

        return $api;
    }

    /**
     * 根据模板获取API信息
     * 
     * @param string $template 模板名称
     * @return Model|Builder|AdminApi|null API模型实例或null
     */
    public function getApiByTemplate(string $template): Model|Builder|AdminApi|null
    {
        return $this->query()->where('template', $template)->first();
    }
}