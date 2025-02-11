<?php

namespace warm\command;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use warm\admin\service\AdminCodeGeneratorService;

/**
 * 生成路由
 * GenRouteCommand
 * warm\command
 *
 * Author:sym
 * Date:2024/12/2 22:00
 * Company:极智科技
 */
class GenRouteCommand extends BaseCommand
{

    protected static string $defaultName = 'warm:gen-route';
    protected static string $defaultDescription = 'admin gen-route';

    protected function configure(): void
    {
        parent::configure();
        $this->addOption('excluded', '-excluded', InputOption::VALUE_REQUIRED, '--excluded选项的值');
    }

    public function putApp($content): bool
    {
        $route_path = config_path('plugin/jizhi/warm/route/');
        // 确保目录存在，如果不存在则创建
        if (!file_exists($route_path)) {
            mkdir($route_path, 0777, true); // 第三个参数为true表示递归创建目录
        }
        $route_path .= 'autoRoute.php';
        if (!file_exists($route_path)) { // 路由文件是否存在
            $fileWritten = file_put_contents($route_path, '');
            if ($fileWritten !== false) {
                $this->line('create routing file');
            } else {
                $this->error('unable to create routing file');
                return false;
            }
        }
        file_put_contents($route_path, $content);

        $root_route_path = config_path('plugin/jizhi/warm/route.php');
        // 添加到 config/route.php
        $root_route_content = file_get_contents($root_route_path);
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

    public function putPlugin($plugin_name, $content): bool
    {
        $route_path = plugin_path($plugin_name . '/route/');
        // 确保目录存在，如果不存在则创建
        if (!file_exists($route_path)) {
            mkdir($route_path, 0777, true); // 第三个参数为true表示递归创建目录
        }
        $route_path .= 'auto.php';
        if (!file_exists($route_path)) { // 路由文件是否存在
            $fileWritten = file_put_contents($route_path, '');
            if ($fileWritten !== false) {
                $this->line('create routing file');
            } else {
                $this->error('unable to create routing file');
                return false;
            }
        }
        file_put_contents($route_path, $content);
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

    public function handle(InputInterface $input, OutputInterface $output): int
    {
        $content = <<<EOF
<?php

// =====================================================================
// !!  路由文件自动生成，请不要手动修改。

// =====================================================================

use warm\admin\Admin;use Webman\Route;

Route::group('/' . Admin::config('app.route.prefix'), function () {
_content_
})->middleware(\warm\admin\Admin::middleware());
EOF;


        $excluded = $this->option('excluded');
        if ($excluded) {
            $excluded = explode(',', $excluded);
        }

        $app_routes = '';
        $plugin_routes = [];

        // 代码生成器
        $CodeGenerator = AdminCodeGeneratorService::make()->query()
            ->when($excluded, fn($query, $excluded) => $query->whereNotIn('id', $excluded))
            ->get()->toArray();
        foreach ($CodeGenerator as $item) {
            if (!$item['menu_info']['enabled']) continue;
            // 判断控制器是否存在
            $_controller = str_replace('/', '\\', $item['controller_name']);
            // 判断是否存在
            if (!class_exists($_controller)) {
                continue;
            }
            $_route = ltrim($item['menu_info']['route'], '/');

            $routes = <<<EOF
    // {$item['title']}
    Route::resource('{$_route}', {$_controller}::class);

EOF;
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

        $result = $this->putApp(str_replace('_content_', $app_routes, $content));
        if (!$result) {
            $this->io->error('App route file generation failed.');
            return self::FAILURE;
        }
        foreach ($plugin_routes as $key => $value) {
            $result = $this->putPlugin($key, str_replace('_content_', $value, $content));
            if ((!$result)) {
                $this->io->error('Plugin route file generation failed.');
                return self::FAILURE;
            }
        }

        $this->io->success('Route file generated successfully.');
        return self::SUCCESS;
    }
}
