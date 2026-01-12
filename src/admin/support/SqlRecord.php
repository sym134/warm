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

    /**
     * 清空SQL记录
     * 
     * 1. 在每个请求开始时清空，确保每个请求只记录自己的SQL语句
     * 2. 在每个请求结束时清空，避免SQL记录累积导致内存泄漏
     * 
     * 清空机制：
     * - ClearSqlRecord 中间件在请求开始时和结束时清空（使用 finally 确保总是执行）
     * - JsonResponse 在响应中读取SQL记录后清空（用于调试信息）
     * 
     * @return void
     */
    public static function clear(): void
    {
        self::$sql = [];
    }
}