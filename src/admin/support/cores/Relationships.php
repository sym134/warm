<?php

namespace warm\admin\support\cores;


use warm\admin\Admin;
use warm\admin\service\AdminRelationshipService;

/**
 * 关联关系管理类
 * 
 * 用于管理系统中的模型关联关系
 * 负责加载和注册系统可用的模型关联
 */
class Relationships
{
    /**
     * 启动关联关系管理器
     * 
     * 加载数据库中配置的模型关联关系，并注册到对应模型中
     * 
     * @return void
     */
    public static function boot(): void
    {
        if (!Admin::hasTable('admin_relationships')) {
            return;
        }

        $relationships = AdminRelationshipService::make()->getAll();

        if (blank($relationships)) {
            return;
        }

        foreach ($relationships as $relationship) {
            try {
                $relationship->model::resolveRelationUsing($relationship->title, function ($model) use ($relationship) {
                    $method = $relationship->method;

                    return $model->$method(...array_column($relationship->buildArgs(), 'value'));
                });
            } catch (\Throwable $e) {
            }
        }
    }
}