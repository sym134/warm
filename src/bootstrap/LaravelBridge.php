<?php

namespace warm\bootstrap;

use Illuminate\Config\Repository as Config;
use Illuminate\Container\Container;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Filesystem\FilesystemServiceProvider;
use Illuminate\Hashing\HashServiceProvider;
use Illuminate\Support\Facades\Facade;
use Illuminate\Translation\FileLoader;
use Illuminate\Translation\Translator;
use Illuminate\Validation\ValidationServiceProvider;
use warm\admin\service\system\SystemConfigService;
use warm\admin\support\cores\Api;
use warm\admin\support\cores\Menu;
use warm\admin\support\Pipeline;
use warm\admin\support\SqlRecord;

/**
 * LaravelBridge (完全版)
 * 支持协程 + 全部 Cache 方法 + Laravel Facade
 * @author Rain
 */
class LaravelBridge
{
    protected static ?Container $app = null;

    /**
     * 初始化 Laravel 环境
     */
    public static function start(): void
    {
        if (self::$app) return;

        $app = new Container;

        // 加载基础配置
        $config = new Config([
            'app' => [
                'debug' => config('app.debug'),
                'timezone' => config('app.default_timezone'),
                'locale' => config('translation.locale', 'zh_CN'), // 兼容webman语言配置
                'fallback_locale' => config('translation.path', base_path() . '/resource/translations'),
            ],
            'filesystems' => config('filesystems'),
        ]);
        $app->instance('config', $config);

        // 设置 Facade 容器
        Facade::setFacadeApplication($app);

        // 注册核心服务提供者
//        (new EventServiceProvider($app))->register();
        (new FilesystemServiceProvider($app))->register();
        (new HashServiceProvider($app))->register();
        (new ValidationServiceProvider($app))->register();

        // 注册语言路径与 Loader 兼容webman语言配置
        $app->singleton('path.lang', fn() => config('translation.path', base_path() . '/resource/translations'));
        $app->singleton('translation.loader', fn($app) => new FileLoader(new Filesystem(), $app->make('path.lang')));

        // 注册翻译器（Translator）
        $app->singleton('translator', function ($app) {
            $loader = $app->make('translation.loader');
            $translator = new Translator($loader, $app['config']['app.locale']);
            $translator->setFallback($app['config']['app.fallback_locale']);
            return $translator;
        });

        // 设置全局容器实例
        Container::setInstance($app);

        // warm
        $app->singleton('admin.context', function () {
            return new class {
                protected array $data = [];

                public function set(string $key, $value): void
                {
                    $this->data[$key] = $value;
                }

                public function get(string $key, $default = null)
                {
                    return $this->data[$key] ?? $default;
                }

                public function clear(): void
                {
                    $this->data = [];
                }
            };
        });
        $app->singleton('admin.config', SystemConfigService::class);
        $app->singleton('pipeline', Pipeline::class);
        $app->singleton('admin.menu', Menu::class);


        self::$app = $app;

        // 启动 Api
        Api::boot();
        // 重置 SQL 记录
        SqlRecord::$sql = [];
    }

    /**
     * 获取容器实例
     */
    public static function app(): Container
    {
        if (!self::$app) self::start();
        return self::$app;
    }

    /**
     * 检测是否在协程环境（Swoole/Swow/Fiber）
     */
    protected static function isCoroutineEnabled(): bool
    {
        return (
            (class_exists('Swoole\Coroutine') && \Swoole\Coroutine::getCid() > 0)
            || (class_exists('Fiber') && \Fiber::getCurrent())
        );
    }
}