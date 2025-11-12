<?php

namespace warm\command;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use warm\admin\service\AdminApiService;
use warm\admin\service\AdminCodeGeneratorService;

/**
 * 路由生成命令类
 *
 * 该命令类用于根据代码生成器的配置自动生成管理后台路由
 * 可通过命令行执行: php webman warm:gen-route
 * 支持 --excluded 选项排除特定的代码生成器配置
 */
class GenRouteCommand extends BaseCommand
{

    /**
     * @var string 命令名称
     */
    protected static string $defaultName = 'warm:gen-route';

    /**
     * @var string 命令详细描述
     */
    protected static string $defaultDescription = '根据代码生成器配置自动生成管理后台路由';

    /**
     * 配置命令参数和选项
     *
     * @return void
     */
    protected function configure(): void
    {
        parent::configure();
        // 添加 excluded 选项，用于排除特定的代码生成器配置
        $this->addOption('excluded', '-excluded', InputOption::VALUE_REQUIRED, '--excluded选项的值');
    }

    /**
     * 生成应用路由文件
     *
     * @param string $content 路由文件内容
     * @return bool 是否成功生成路由文件
     */
    public function putApp(string $content): bool
    {
        // 定义路由文件路径
        $route_path = config_path('plugin/jizhi/warm/route/');

        // 确保目录存在，如果不存在则创建
        if (!file_exists($route_path)) {
            mkdir($route_path, 0777, true); // 第三个参数为true表示递归创建目录
        }

        $route_path .= 'autoRoute.php';

        // 如果路由文件不存在，则创建空文件
        if (!file_exists($route_path)) { // 路由文件是否存在
            $fileWritten = file_put_contents($route_path, '');
            if ($fileWritten !== false) {
                $this->line('create routing file');
            } else {
                $this->error('unable to create routing file');
                return false;
            }
        }

        // 写入路由内容到文件
        file_put_contents($route_path, $content);

        // 获取根路由文件路径
        $root_route_path = config_path('plugin/jizhi/warm/route.php');

        // 添加到 config/route.php
        $root_route_content = file_get_contents($root_route_path);

        // 检查是否已包含自动路由文件的引入语句
        if (!str_contains($root_route_content, 'require_once config_path(\'plugin/jizhi/warm/route/autoRoute.php\');')) {
            // 如果不包含，则在内容后追加该行 admin 替换为应用名称
            $root_route_content .= "\n// 加载应用下的路由配置\nrequire_once config_path('plugin/jizhi/warm/route/autoRoute.php');";

            // 将修改后的内容写回文件
            if (file_put_contents($root_route_path, $root_route_content) === false) {
                $this->error('Failed to append content to route.php file');
                return false;
            }
        }

        return true;
    }

    /**
     * 生成插件路由文件
     *
     * @param string $plugin_name 插件名称
     * @param string $content 路由文件内容
     * @return bool 是否成功生成路由文件
     */
    public function putPlugin(string $plugin_name, string $content): bool
    {
        // 定义插件路由文件路径
        $route_path = plugin_path($plugin_name . '/route/');

        // 确保目录存在，如果不存在则创建
        if (!file_exists($route_path)) {
            mkdir($route_path, 0777, true); // 第三个参数为true表示递归创建目录
        }

        $route_path .= 'auto.php';

        // 如果路由文件不存在，则创建空文件
        if (!file_exists($route_path)) { // 路由文件是否存在
            $fileWritten = file_put_contents($route_path, '');
            if ($fileWritten !== false) {
                $this->line('create routing file');
            } else {
                $this->error('unable to create routing file');
                return false;
            }
        }

        // 写入路由内容到文件
        file_put_contents($route_path, $content);

        // 获取插件配置路由文件路径
        $config_route_path = plugin_path($plugin_name . '/config/route.php');
        $root_route_content = file_get_contents($config_route_path);

        // 引入自动路由
        if (!str_contains($root_route_content, "require_once app_path($plugin_name/route/autoRoute.php');")) {
            $root_route_content .= "\n// 加载应用下的路由配置\nrequire_once plugin_path('$plugin_name/route/autoRoute.php');";
            if (file_put_contents($config_route_path, $root_route_content) === false) {
                $this->error('Failed to append content to route.php file');
                return false;
            }
        }

        return true;
    }

    /**
     * 执行路由生成命令的主方法
     *
     * @param InputInterface $input 输入接口对象
     * @param OutputInterface $output 输出接口对象
     * @return int 返回执行状态码 (self::SUCCESS 或 self::FAILURE)
     */
    public function handle(InputInterface $input, OutputInterface $output): int
    {
        // 定义路由文件模板内容
        $content = <<<EOF
<?php

// =====================================================================
// !!  路由文件自动生成，请不要手动修改。

// =====================================================================

use warm\admin\Admin;use Webman\Route;

Route::group(Admin::warmConfig('app.route.prefix'), function () {
_content_
})->middleware(\warm\admin\Admin::middleware());
EOF;


        // 获取 excluded 选项参数
        $excluded = $this->option('excluded');
        if ($excluded) {
            $excluded = explode(',', $excluded);
        }

        // 初始化应用路由和插件路由内容
        $app_routes = '';
        $plugin_routes = [];

        // 从代码生成器获取路由配置
        $CodeGenerator = AdminCodeGeneratorService::make()->query()
            ->when($excluded, fn($query, $excluded) => $query->whereNotIn('id', $excluded))
            ->get()->toArray();

        // 遍历代码生成器配置，生成对应的路由
        foreach ($CodeGenerator as $item) {
            // 如果菜单未启用，则跳过
            if (!$item['menu_info']['enabled']) continue;

            // 判断控制器是否存在
            $_controller = str_replace('/', '\\', $item['controller_name']);

            // 判断控制器类是否存在
            if (!class_exists($_controller)) {
                continue;
            }

            // 获取路由路径
            $_route = ltrim($item['menu_info']['route'], '');

            // 生成路由定义代码
            $routes = <<<EOF
    // {$item['title']}
    Route::resource('$_route', $_controller::class);

EOF;
            // 根据保存路径区分应用路由和插件路由
            if (empty($item['save_path']) || $item['save_path']['directory'] === 'app') {
                $app_routes .= $routes;
            } else {
                if (isset($plugin_routes[$item['save_path']['directory']])) {
                    $plugin_routes[$item['save_path']['directory']] .= $routes;
                } else {
                    $plugin_routes[$item['save_path']['directory']] = $routes;
                }
            }
        }

        // api
        AdminApiService::make()->query()->where('enabled', 1)->get()->map(function ($item) use (&$app_routes) {
            $_route = ltrim($item->path, '/');

            $app_routes .= <<<EOF
    // {$item->title}
    Route::$item->method('/$_route', [\warm\admin\controller\AdminApiController::class, 'index']);

EOF;
        });

        // 生成应用路由文件
        $result = $this->putApp(str_replace('_content_', $app_routes, $content));
        if (!$result) {
            $this->io->error('App route file generation failed.');
            return self::FAILURE;
        }

        // 生成插件路由文件
        foreach ($plugin_routes as $key => $value) {
            $result = $this->putPlugin($key, str_replace('_content_', $value, $content));
            if ((!$result)) {
                $this->io->error('AdminPlugin route file generation failed.');
                return self::FAILURE;
            }
        }

        // 输出成功信息
        $this->io->success('Route file generated successfully.');
        return self::SUCCESS;
    }
}