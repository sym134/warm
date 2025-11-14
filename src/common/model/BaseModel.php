<?php

namespace warm\common\model;

use Illuminate\Database\Query\Builder;
use support\Container;
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
     * 隐藏属性
     *
     * @var array
     */
    protected $hidden = ['tenant_id'];

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
//        if (env('ENABLED_SAAS')) {
//            // 切换当前站点信息
//            $this->setConnection((isset(request()->tenant) && request()->tenant['database']) ? request()->tenant['database'] : 'plugin.saas.saas');
//        } else {
//            // 切换当前站点信息
//            $this->setConnection(Admin::warmConfig('app.database.connection'));
//        }
        /**
         * 针对租户库模式切换数据源
         */
//        if (config('plugin.saas.app.enable', false)) {
//            if (is_null(pluginContainer('saas'))&&pluginContainer('saas')->has(\plugin\saas\app\support\RequestTenantContextInterface::class) && \plugin\saas\app\support\tenant\TenantContext::isLibraryIsolation()) {
//                $this->connection = \plugin\saas\app\support\tenant\TenantContext::getDatabaseConnection();
//            }else{
//                $this->setConnection(Admin::warmConfig('app.database.connection'));
//            }
//        }else{
//            $this->setConnection(Admin::warmConfig('app.database.connection'));
//        }


        $default = Admin::warmConfig('app.database.connection');

        // SaaS 未启用 → 使用默认连接
        if (!config('plugin.saas.app.enable', false)) {
//            $this->setConnection($default);
            return parent::__construct($attributes);
        }

        // SaaS 启用 → 判断是否为隔离模式的租户
        $hasContext = is_null(pluginContainer('saas')) && pluginContainer('saas')::has(
                \plugin\saas\app\support\RequestTenantContextInterface::class
            );

        $isIsolated = \plugin\saas\app\support\tenant\TenantContext::isLibraryIsolation();

        // 租户隔离且有上下文 → 切租户库
        if ($hasContext && $isIsolated) {
            $this->connection = \plugin\saas\app\support\tenant\TenantContext::getDatabaseConnection();
        } // 否则 → 默认连接
        else {
//            $this->setConnection($default);
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
     */
    public static function baseQuery(): Builder
    {
        return Db::table(static::getTableName());
    }
}