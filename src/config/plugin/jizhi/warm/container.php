<?php

use DI\ContainerBuilder;
use Illuminate\Filesystem\Filesystem;
use warm\admin\service\system\SystemConfigService;
use warm\admin\support\apis\{DataDetailApi};
use warm\admin\support\apis\DataCreateApi;
use warm\admin\support\apis\DataDeleteApi;
use warm\admin\support\apis\DataListApi;
use warm\admin\support\apis\DataUpdateApi;
use warm\admin\support\cores\{Menu};
use warm\admin\support\cores\Asset;
use warm\admin\support\cores\Context;
use warm\admin\support\Pipeline;
use warm\framework\hashing\HashManager;
use warm\framework\support\facade\Validate;

/**
 * Warm Admin 依赖注入容器配置文件
 * 
 * 配置应用的依赖注入容器，定义各种服务的创建方式和依赖关系
 * 使用 PHP-DI 作为依赖注入容器实现
 */
//$builder = new ContainerBuilder();
//
//$builder->addDefinitions([
//    // API模板列表
//    'apis' => [
//        DataListApi::class,
//        DataCreateApi::class,
//        DataDetailApi::class,
//        DataDeleteApi::class,
//        DataUpdateApi::class,
//    ],
//    // 文件系统服务
//    'files' => fn() => new Filesystem,
//    // 菜单服务
//    'admin.menu' => fn() => new Menu,
//    // 资源服务
//    'admin.asset' => fn() => new Asset,
//    // 配置服务
//    'admin.config' => fn() => new SystemConfigService,
//    // 上下文服务
//    'admin.context' => fn() => new Context,
//    // 管道服务
//    'pipeline' => fn() => new Pipeline,
//    // 验证服务
//    'validate' => fn() => new Validate,
//    // 哈希服务
//    'hash' => fn() => new HashManager,
//]);
//
//// 启用自动装配
//$builder->useAutowiring(true);
//
//return $builder->build();