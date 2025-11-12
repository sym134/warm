<?php

namespace warm\admin\model;

use Illuminate\Database\Eloquent\Concerns\HasTimestamps;
use warm\common\model\BaseModel;

/**
 * 管理页面模型类
 * 
 * 该模型用于存储自定义管理页面的信息，包括：
 * 1. 页面标题和标识
 * 2. 页面结构配置（schema）
 * 3. 创建和更新时间戳
 * 
 * 主要用于保存通过页面设计器创建的自定义页面配置。
 */
class AdminPage extends BaseModel
{
    use HasTimestamps;

    /**
     * 需要进行类型转换的字段
     * 
     * 将数据库中的JSON字符串自动转换为PHP数组或对象
     * 
     * @var array
     */
    protected $casts = [
        'schema' => 'json',  // 页面结构配置
    ];
}