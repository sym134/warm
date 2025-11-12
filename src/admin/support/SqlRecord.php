<?php

namespace warm\admin\support;

use support\Db;
use Illuminate\Database\Events\QueryExecuted;

/**
 * SQL记录类
 * 
 * 用于监听和记录数据库查询语句，主要用于调试和性能分析。
 * 可以捕获所有执行的SQL语句及其执行时间。
 * 
 * Author:sym
 * Date:2024/10/30 09:19
 * Company:极智科技
 */
class SqlRecord
{
    /**
     * 存储记录的SQL语句数组
     * 
     * 每条记录包含执行时间和SQL语句
     * 
     * @var array
     */
    public static array $sql = [];

    /**
     * 开始监听数据库查询
     * 
     * 注册数据库查询事件监听器，捕获所有执行的SQL语句：
     * 1. 获取SQL语句和绑定参数
     * 2. 将绑定参数替换到SQL语句中
     * 3. 记录执行时间和完整SQL语句
     * 
     * @return void
     */
    public static function listen(): void
    {
        // 注册数据库查询事件监听器
        Db::listen(function (QueryExecuted $query) {
            // 获取绑定参数
            $bindings = $query->bindings;
            // 获取原始SQL语句
            $sql = $query->sql;
            
            // 将绑定参数替换到SQL语句中
            foreach ($bindings as $replace) {
                $value = is_numeric($replace) ? $replace : "'" . $replace . "'";
                $sql = preg_replace('/\?/', $value, $sql, 1);
            }

            // 格式化SQL记录，包含执行时间
            $sql = sprintf('[%s ms] %s', $query->time, $sql);
            // 将SQL记录添加到数组中
            self::$sql[] = $sql;
        });
    }
}