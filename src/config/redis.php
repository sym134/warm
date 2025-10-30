<?php
/**
 * This file is part of webman.
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the MIT-LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @author    walkor<walkor@workerman.net>
 * @copyright walkor<walkor@workerman.net>
 * @link      http://www.workerman.net/
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 */

/**
 * Redis数据库配置文件
 * 
 * 该配置文件定义了应用程序连接Redis服务器所需的配置信息。
 * 使用env()函数从环境变量中获取配置值，如果环境变量未设置，则使用默认值。
 * 
 * 配置项说明：
 * - host: Redis服务器主机地址
 * - password: Redis服务器密码（如果有的话）
 * - port: Redis服务器端口号
 * - database: 要连接的Redis数据库编号（Redis支持多个数据库，从0开始编号）
 */
return [
    /**
     * 默认Redis连接配置
     * 这里定义了应用使用的默认Redis连接参数
     */
    'default' => [
        /**
         * Redis服务器主机地址
         * 从环境变量REDIS_HOST获取，如果未设置则默认为'127.0.0.1'
         */
        'host' => env('REDIS_HOST', '127.0.0.1'),
        
        /**
         * Redis服务器密码
         * 从环境变量REDIS_PASSWORD获取，如果未设置则默认为空字符串
         */
        'password' => env('REDIS_PASSWORD', ''),
        
        /**
         * Redis服务器端口
         * 从环境变量REDIS_PORT获取，如果未设置则默认为6379
         */
        'port' => env('REDIS_PORT', 6379),
        
        /**
         * Redis数据库编号
         * 从环境变量REDIS_CACHE_DB获取，如果未设置则默认为0
         * Redis支持多个数据库，通过编号区分，默认是0-15号数据库
         */
        'database' => env('REDIS_CACHE_DB', 0),
    ],
];
