<?php

use DI\ContainerBuilder;
use Illuminate\Filesystem\Filesystem;
use warm\framework\facade\Validate;
use warm\framework\hashing\HashManager;
use warm\service\AdminSettingService;
use warm\support\apis\{DataCreateApi, DataDeleteApi, DataDetailApi, DataListApi, DataUpdateApi};
use warm\support\cores\{Asset, Context, Menu};
use warm\support\Pipeline;

$builder = new ContainerBuilder();

$builder->addDefinitions([
    'apis' => [
        DataListApi::class,
        DataCreateApi::class,
        DataDetailApi::class,
        DataDeleteApi::class,
        DataUpdateApi::class,
    ],
    'files' => DI\create(Filesystem::class),
    'admin.menu' => DI\create(Menu::class),
    'admin.asset' => DI\create(Asset::class),
    'admin.setting' => DI\factory([AdminSettingService::class, 'create']),
    'admin.context' => DI\create(Context::class),
    'Pipeline' => DI\create(Pipeline::class),
    'validate' => DI\create(Validate::class),
    'hash' => DI\create(HashManager::class),
]);

$builder->useAutowiring(true);

return $builder->build();