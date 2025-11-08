<?php

/**
 * Warm Admin 命令行配置文件
 * 
 * 注册自定义的命令行指令，这些指令可以通过命令行执行
 * 用于应用安装、路由生成、插件创建等操作
 */
return [
    // 安装命令
    \warm\command\InstallCommand::class,
    // 路由生成命令
    \warm\command\GenRouteCommand::class,
    // 应用插件创建命令
    \warm\command\AppPluginCreateCommand::class,
    // 添加微信菜单命令
    \warm\command\AddWechatMenuCommand::class,
];