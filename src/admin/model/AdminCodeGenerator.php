<?php

namespace warm\admin\model;

use Illuminate\Database\Eloquent\Concerns\HasTimestamps;
use warm\common\model\BaseModel;

/**
 * 代码生成器模型类
 * 
 * 该模型用于存储代码生成器的配置信息，包括：
 * 1. 表名和主键信息
 * 2. 字段配置信息
 * 3. 生成需求选项
 * 4. 菜单和页面配置信息
 * 5. 保存路径配置
 * 
 * 主要用于代码生成器功能，保存用户配置的代码生成参数。
 */
class AdminCodeGenerator extends BaseModel
{
    use HasTimestamps;
    
    /**
     * 与模型关联的表名
     * 
     * @var string
     */
    protected $table = 'admin_code_generators';

    /**
     * 需要进行类型转换的字段
     * 
     * 将数据库中的JSON字符串自动转换为PHP数组
     * 
     * @var array
     */
    protected $casts = [
        'columns'   => 'array',    // 字段配置信息
        'needs'     => 'array',    // 生成需求选项
        'menu_info' => 'array',    // 菜单配置信息
        'page_info' => 'array',    // 页面配置信息
        'save_path' => 'array',    // 保存路径配置
    ];
}