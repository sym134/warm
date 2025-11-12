<?php

namespace warm\common\model;

/**
 * 系统配置模型类
 * 
 * 用于管理系统配置信息的数据模型，继承自基础模型类
 * 主要处理系统级配置的存储和读取
 */
class SystemConfig extends BaseModel
{
    /**
     * 数据表名
     * 
     * 指定当前模型对应的数据表名称
     * 
     * @var string
     */
    protected $table = 'system_configs';

    /**
     * 主键字段名
     * 
     * 指定当前模型对应数据表的主键字段名
     * 
     * @var string
     */
    protected $primaryKey = 'key';

    /**
     * 不可批量赋值的字段
     * 
     * 指定不允许通过批量赋值进行填充的字段
     * 空数组表示所有字段都可以批量赋值
     * 
     * @var array
     */
    protected $guarded = [];

    /**
     * 字段类型转换
     * 
     * 定义字段的类型转换规则
     * 'values字段将被转换为JSON格式
     * 
     * @var array
     */
    protected $casts = [
        'values' => 'json',
    ];

    /**
     * 将值转换为JSON格式
     * 
     * 自定义JSON编码方法，使用特定的JSON编码选项
     * 
     * @param mixed $value 需要转换为JSON的值
     * @return bool|string 返回编码后的JSON字符串，失败时返回false
     */
    protected function asJson($value): bool|string
    {
        return json_encode($value, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    }
}