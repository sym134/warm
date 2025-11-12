<?php

namespace warm\admin\model;

use warm\common\model\BaseModel;

/**
 * 插件模型类
 * 
 * 该模型用于存储和管理插件信息，包括：
 * 1. 插件的基本信息（名称、启用状态等）
 * 2. 插件的配置选项
 * 
 * 对应数据库表为 admin_extensions。
 */
class AdminPlugin extends BaseModel
{
    /**
     * 可以批量赋值的属性
     * 
     * @var array
     */
    protected $fillable = ['key', 'is_enabled', 'options'];

    /**
     * 需要进行类型转换的字段
     * 
     * 将数据库中的JSON字符串自动转换为PHP数组
     * 
     * @var array
     */
    protected $casts = [
        'options' => 'json',  // 插件配置选项
    ];

    /**
     * 与模型关联的表名
     * 
     * @var string
     */
    protected $table = 'admin_plugins';
}