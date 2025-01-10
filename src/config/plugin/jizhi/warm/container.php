<?php

use DI\ContainerBuilder;
use Illuminate\Filesystem\Filesystem;
use warm\admin\support\apis\DataCreateApi;
use warm\admin\support\apis\DataDeleteApi;
use warm\admin\support\apis\DataListApi;
use warm\admin\support\apis\DataUpdateApi;
use warm\admin\support\cores\Asset;
use warm\admin\support\cores\Context;
use warm\common\service\ConfigService;
use warm\framework\facade\Validate;
use warm\framework\hashing\HashManager;
use warm\admin\support\apis\{DataDetailApi};
use warm\admin\support\cores\{Menu};
use warm\admin\support\Pipeline;

$builder = new ContainerBuilder();

$builder->addDefinitions([
    'apis' => [
        DataListApi::class,
        DataCreateApi::class,
        DataDetailApi::class,
        DataDeleteApi::class,
        DataUpdateApi::class,
    ],
    'files' => fn() => new Filesystem,
    'admin.menu' => fn() => new Menu,
    'admin.asset' => fn() => new Asset,
    'admin.config' => fn() => new ConfigService,
    'admin.context' => fn() => new Context,
    'Pipeline' => fn() => new Pipeline,
    'validate' => fn() => new Validate,
    'hash' => fn() => new HashManager,
]);

$builder->useAutowiring(true);

return $builder->build();