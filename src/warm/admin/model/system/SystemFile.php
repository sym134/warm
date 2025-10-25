<?php

namespace warm\admin\model\system;

use warm\common\model\BaseModel;

/**
 * 系统文件模型类
 * 
 * 该模型用于存储和管理系统中上传的文件信息，包括：
 * 1. 文件存储模式（本地、云存储等）
 * 2. 文件类型（图片、文档、音频等）
 * 3. 文件路径和元数据
 * 
 * 不包含 updated_at 字段。
 */
class SystemFile extends BaseModel
{
    /**
     * 禁用 updated_at 字段
     * 
     * @var null
     */
    public const UPDATED_AT = null;
    
    /**
     * 存储模式常量定义
     * 
     * @var array
     */
    const STORAGE_MODE = ['local' => '本地', 'qiniu' => '七牛', 'aliyun' => '阿里云', 'qcloud' => '腾讯云'];
    
    /**
     * 文件类型常量定义
     * 
     * @var array
     */
    const FILE_TYPE = ['image' => '图片', 'text' => '文档', 'audio' => '音频', 'file' => '文件'];

    /**
     * 与模型关联的表名
     * 
     * @var string
     */
    protected $table = 'system_files';
}