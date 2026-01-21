<?php

namespace warm\admin\model\system;

use warm\common\model\BaseModel;

/**
 * 文件分组模型类
 * 
 * 用于管理系统文件的分类和分组
 */
class SystemFileGroup extends BaseModel
{
    /**
     * 与模型关联的表名
     * 
     * @var string
     */
    protected $table = 'system_file_groups';

    /**
     * 可批量赋值的属性
     * 
     * @var array
     */
    protected $fillable = [
        'name',
        'file_type',
        'sort',
        'created_by',
    ];
}
