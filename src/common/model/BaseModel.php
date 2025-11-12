<?php

namespace warm\common\model;

use support\Db;
use support\Model;
use warm\admin\Admin;
use warm\common\trait\DatetimeFormatterTrait;

/**
 * 基础模型类
 * 
 * 所有数据模型的基类，提供统一的数据库连接管理和常用方法
 * 支持多租户(SAAS)模式和普通模式的数据库切换
 */
class BaseModel extends Model
{
    use DatetimeFormatterTrait;

    /**
     * 构造函数
     * 
     * 初始化模型实例，根据环境变量和配置设置数据库连接
     * 支持SAAS多租户模式和普通管理模式
     * 
     * @param array $attributes 模型属性数组
     */
    public function __construct(array $attributes = [])
    {
        if (env('ENABLED_SAAS')) {
            // 切换当前站点信息
            $this->setConnection((isset(request()->tenant) && request()->tenant['database']) ? request()->tenant['database'] : 'plugin.saas.saas');
        } else {
            // 切换当前站点信息
            $this->setConnection(Admin::warmConfig('app.database.connection'));
        }

        parent::__construct($attributes);
    }

    /**
     * 获取表名
     * 
     * 获取当前模型对应的数据库表名
     * 
     * @return string 数据库表名
     */
    public static function getTableName(): string
    {
        return (new static)->getTable();
    }

    /**
     * 获取基础查询构造器
     * 
     * 创建并返回当前模型对应表的基础查询构造器
     * 
     * @return \Illuminate\Database\Query\Builder 查询构造器实例
     */
    public static function baseQuery(): \Illuminate\Database\Query\Builder
    {
        return Db::table(static::getTableName());
    }
}