<?php

namespace warm\bootstrap;

use Illuminate\Config\Repository as Config;
use Illuminate\Container\Container;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Filesystem\FilesystemManager;
use Illuminate\Redis\RedisManager;
use Illuminate\Support\Arr;
use Illuminate\Translation\FileLoader;
use Illuminate\Translation\Translator;
use Illuminate\Validation\Factory as ValidationFactory;
use Illuminate\Cache\CacheManager;
use Illuminate\Support\Facades\Facade;
use Illuminate\Cache\Repository as CacheRepository;
use Illuminate\Hashing\HashManager;
use warm\admin\service\system\SystemConfigService;
use warm\admin\support\cores\Api;
use warm\admin\support\cores\Menu;
use warm\admin\support\Pipeline;
use warm\admin\support\SqlRecord;
use warm\common\config\FilesystemConfig;
use Webman\Bootstrap;

class LaravelBootstrap implements Bootstrap
{
    public static ?Container $container = null;

    /**
     * Webman worker 启动时执行
     */
    public static function start($worker): void
    {
        $container = new Container();

        // 1️⃣ 加载配置
        $config = new Config([
            'app' => [
                'debug' => config('app.debug'),
                'timezone' => config('app.default_timezone'),
                'locale' => env('APP_LOCALE', 'en'),
                'fallback_locale' => config('app.fallback_locale', 'en'),
            ],
            'filesystems' => FilesystemConfig::CONFIG,
            'cache' => config('cache'),
            'database' => config('database'),
        ]);
        $container->instance('config', $config);

        // 2️⃣ 事件系统
//        $container->singleton('events', fn($app) => new Dispatcher($app));

        // 3️⃣ 文件系统

//        $container->singleton('files', fn() => new Filesystem());
        $container->singleton('filesystem', fn($app) => new FilesystemManager($app));

        // 4️⃣ Redis
        $container->singleton('redis', function ($app) {
            $config = $app->make('config')->get('database.redis', []);
            return new RedisManager($app, Arr::pull($config, 'client', 'phpredis'), $config);
        });

        // 5️⃣ 缓存
        $container->singleton('cache', fn($app) => new CacheManager($app));
        $container->singleton('cache.store', fn($app) => $app['cache']->store());
        $container->bind(CacheRepository::class, fn($app) => $app['cache.store']);

        // 6️⃣ 数据库 / ORM
//        $container->singleton('db', function ($app) use ($config) {
//            $capsule = new Capsule($app);
//            $connections = $config->get('database.connections', []);
//            foreach ($connections as $name => $connection) {
//                $capsule->addConnection($connection, $name);
//            }
//            $capsule->setEventDispatcher($app['events']);
//            $capsule->setAsGlobal();
//            $capsule->bootEloquent();
//            return $capsule;
//        });

        // 7️⃣ 哈希系统
        $container->singleton('hash', fn($app) => new HashManager($app));

        // 8️⃣ 翻译
        $container->singleton('translator', function ($app) {
            $loader = new FileLoader(new Filesystem(), base_path('lang'));
            return new Translator($loader, $app->make('config')->get('app.locale', 'en'));
        });

        // 9️⃣ 验证系统
        $container->singleton('validator', fn($app) => new ValidationFactory($app['translator'], $app));

        // 🔟 Context（上下文）
        $container->singleton('admin.context', function () {
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

        // 1️⃣1️⃣ Facade
        Facade::setFacadeApplication($container);
        self::registerFacades();

        // warm
        $container->singleton('admin.config', SystemConfigService::class);
        $container->singleton('pipeline', Pipeline::class);
        $container->singleton('admin.menu', Menu::class);

        static::$container = $container;

        Api::boot();
        // 重置 SQL 记录
        SqlRecord::$sql = [];

        self::reloadConfig();
    }
    public static function app(): ?Container
    {
        return static::$container;
    }

    protected static function registerFacades(): void
    {
        $aliases = [
            'App' => \Illuminate\Support\Facades\App::class,
            'Cache' => \Illuminate\Support\Facades\Cache::class,
            'Config' => \Illuminate\Support\Facades\Config::class,
            'DB' => \Illuminate\Support\Facades\DB::class,
//            'Event' => \Illuminate\Support\Facades\Event::class,
//            'File' => \Illuminate\Support\Facades\File::class,
            'Hash' => \Illuminate\Support\Facades\Hash::class,
            'Lang' => \Illuminate\Support\Facades\Lang::class,
            'Redis' => \Illuminate\Support\Facades\Redis::class,
            'Storage' => \Illuminate\Support\Facades\Storage::class,
            'Validator' => \Illuminate\Support\Facades\Validator::class,
        ];

        foreach ($aliases as $alias => $facade) {
            if (!class_exists($alias)) {
                class_alias($facade, $alias);
            }
        }
    }

    public static function reloadConfig(): void
    {
        $config = \warm\common\service\SystemConfigService::get('filesystems');

        if (!empty($config)) {
            $app=app();
            \Illuminate\Support\Facades\Config::set('filesystems', $config);
            // 移除已实例化的文件系统组件，让下次重新创建
            foreach (['filesystem', 'filesystem.disk', 'filesystem.cloud'] as $key) {
                if ($app->bound($key)) {
                    $app->forgetInstance($key);
                }
            }
            // 清空 Storage facade 内部缓存
//            \Illuminate\Support\Facades\Storage::clearResolvedInstances();
//            \Illuminate\Support\Facades\Storage::setFacadeApplication($app);
            \Illuminate\Support\Facades\Facade::clearResolvedInstances();

            echo "[LaravelBootstrap] ✅ Reload filesystem config success.\n";
        }
    }
}