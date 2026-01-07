<?php

/**
 * Warm Admin 启动配置文件
 * 
 * 定义应用启动时需要加载的服务提供者或启动项
 * 这些启动项会在应用启动时自动执行
 */
return [
    // SQL监控启动类
    \warm\bootstrap\SqlMonitor::class,
    // Laravel 服务提供者（文件系统服务）
    \warm\bootstrap\LaravelServiceProvider::class,
];