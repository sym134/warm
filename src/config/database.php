<?php

/**
 * 数据库配置文件
 * 
 * 该配置文件定义了应用程序连接各种数据库所需的配置信息。
 * 支持多种数据库类型，每种数据库可以有独立的连接配置。
 * 使用env()函数从环境变量中获取敏感配置信息，增强安全性。
 */
return [
    /**
     * 默认数据库连接名称
     * 指定应用启动时默认使用的数据库连接配置
     * 从环境变量DB_CONNECTION获取，如果未设置则默认为'mysql'
     */
    'default' => env('DB_CONNECTION', 'mysql'),
    
    /**
     * 数据库连接配置集合
     * 可以为不同的数据库定义不同的连接参数
     * 每个连接由一个唯一标识符作为键名
     */
    'connections' => [
        /**
         * MySQL数据库连接配置
         * 包含了连接MySQL数据库所需的所有参数
         */
        'mysql' => [
            /**
             * 数据库驱动类型
             * 指定要使用的数据库驱动程序
             */
            'driver' => 'mysql',
            
            /**
             * 数据库服务器主机地址
             * 从环境变量DB_HOST获取，如果未设置则默认为'127.0.0.1'
             */
            'host' => env('DB_HOST', '127.0.0.1'),
            
            /**
             * 数据库服务器端口号
             * 从环境变量DB_PORT获取，如果未设置则默认为'3306'
             */
            'port' => env('DB_PORT', '3306'),
            
            /**
             * 数据库名称
             * 从环境变量DB_DATABASE获取，如果未设置则默认为'forge'
             */
            'database' => env('DB_DATABASE', 'forge'),
            
            /**
             * 数据库用户名
             * 从环境变量DB_USERNAME获取，如果未设置则默认为'forge'
             */
            'username' => env('DB_USERNAME', 'forge'),
            
            /**
             * 数据库密码
             * 从环境变量DB_PASSWORD获取，如果未设置则默认为空字符串
             */
            'password' => env('DB_PASSWORD', ''),
            
            /**
             * Unix套接字路径（可选）
             * 当使用Unix套接字连接而非TCP/IP时指定
             * 从环境变量DB_SOCKET获取，如果未设置则默认为空字符串
             */
            'unix_socket' => env('DB_SOCKET', ''),
            
            /**
             * 字符集设置
             * 设置数据库连接使用的字符集，这里使用utf8mb4以支持完整的UTF-8字符
             */
            'charset' => 'utf8mb4',
            
            /**
             * 排序规则
             * 设置数据库连接使用的排序规则
             */
            'collation' => 'utf8mb4_unicode_ci',
            
            /**
             * 表前缀
             * 用于给所有数据表添加统一前缀，便于在同一个数据库中区分不同应用的数据表
             */
            'prefix' => '',
            
            /**
             * 是否为索引也加上前缀
             * 如果启用，索引名称也会加上表前缀
             */
            'prefix_indexes' => true,
            
            /**
             * 是否启用严格模式
             * 在严格模式下，一些SQL操作会更加严格地遵循SQL标准
             */
            'strict' => false,
            
            /**
             * 存储引擎
             * 指定MySQL存储引擎，null表示使用默认存储引擎
             */
            'engine' => null,
            
            /**
             * PDO连接选项
             * 根据是否加载了pdo_mysql扩展来决定是否设置SSL选项
             * PDO::MYSQL_ATTR_SSL_CA: 指定用于SSL连接的CA证书路径
             */
            'options' => extension_loaded('pdo_mysql') ? array_filter([
                PDO::MYSQL_ATTR_SSL_CA => env('MYSQL_ATTR_SSL_CA'),
            ]) : [],
        ],
    ],
];
