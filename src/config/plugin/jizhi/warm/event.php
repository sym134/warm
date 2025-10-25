<?php

use warm\event\SystemUser;

/**
 * Warm Admin 事件配置文件
 * 
 * 定义系统事件及其对应的监听器
 * 当特定事件触发时，会自动调用相应的监听器方法
 */
return [
    // 用户登录事件
    'user.login' => [
        [SystemUser::class, 'login'],
    ],
    // 用户操作日志事件
    'user.operateLog' => [
        [SystemUser::class, 'operateLog'],
    ]
];