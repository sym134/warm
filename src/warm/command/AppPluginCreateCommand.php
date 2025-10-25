<?php

namespace warm\command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * 创建插件命令类
 * 
 * 该类继承自Symfony Console Command，用于通过命令行创建新的插件
 * 包括插件目录结构、配置文件、控制器、视图等
 * 
 * Author:sym
 * Date:2024/6/18 上午10:32
 * Company:极智科技
 */
class AppPluginCreateCommand extends Command
{
    /**
     * 命令名称
     * 
     * @var string
     */
    protected static string $defaultName = 'cms-plugin:create';
    
    /**
     * 命令描述
     * 
     * @var string
     */
    protected static string $defaultDescription = 'App Plugin Create';

    /**
     * 配置命令参数
     * 
     * 添加一个必需的参数'name'，用于指定要创建的插件名称
     * 
     * @return void
     */
    protected function configure(): void
    {
        $this->addArgument('name', InputArgument::REQUIRED, 'App plugin name');
    }

    /**
     * 执行命令逻辑
     * 
     * 根据提供的插件名称创建完整的插件目录结构和文件
     * 
     * @param InputInterface $input 输入接口对象
     * @param OutputInterface $output 输出接口对象
     * @return int 命令执行结果状态码
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $name = $input->getArgument('name');
        $output->writeln("Create App Plugin $name");

        if (str_contains($name, '/')) {
            $output->writeln('<error>Bad name, name must not contain character \'/\'</error>');
            return self::FAILURE;
        }

        // Create dir config/plugin/$name
        if (is_dir($plugin_config_path = base_path()."/plugin/$name")) {
            $output->writeln("<error>Dir $plugin_config_path already exists</error>");
            return self::FAILURE;
        }

        $this->createAll($name);

        return self::SUCCESS;
    }

    /**
     * 创建插件的所有文件和目录
     * 
     * 创建插件所需的完整目录结构和初始文件
     * 包括控制器、模型、中间件、视图、配置等目录及文件
     * 
     * @param string $name 插件名称
     * @return void
     */
    protected function createAll($name): void
    {
        $base_path = base_path();
        $this->mkdir("$base_path/plugin/$name/app/controller", 0777, true);
        $this->mkdir("$base_path/plugin/$name/app/model", 0777, true);
        $this->mkdir("$base_path/plugin/$name/app/middleware", 0777, true);
        $this->mkdir("$base_path/plugin/$name/app/view/index", 0777, true);
        $this->mkdir("$base_path/plugin/$name/config", 0777, true);
        $this->mkdir("$base_path/plugin/$name/public", 0777, true);
        $this->mkdir("$base_path/plugin/$name/api", 0777, true);
        $this->createFunctionsFile("$base_path/plugin/$name/app/functions.php");
        $this->createControllerFile("$base_path/plugin/$name/app/controller/IndexController.php", $name);
        $this->createViewFile("$base_path/plugin/$name/app/view/index/index.html");
        $this->createConfigFiles("$base_path/plugin/$name/config", $name);
        $this->createApiFiles("$base_path/plugin/$name/api", $name);
        $this->createInstallSqlFile("$base_path/plugin/$name/install.sql");
    }

    /**
     * 创建目录
     * 
     * 安全地创建目录，如果目录已存在则不执行任何操作
     * 
     * @param string $path 要创建的目录路径
     * @return void
     */
    protected function mkdir($path): void
    {
        if (is_dir($path)) {
            return;
        }
        echo "Create $path\r\n";
        mkdir($path, 0777, true);
    }

    /**
     * 创建控制器文件
     * 
     * 创建插件的默认IndexController控制器文件
     * 
     * @param string $path 控制器文件路径
     * @param string $name 插件名称
     * @return void
     */
    protected function createControllerFile($path, $name): void
    {
        $content = <<<EOF
<?php

namespace plugin\\$name\\app\\controller;

use support\use;use warm\admin\controller\AdminController;use warm\admin\service\AdminApiService; support\Response;

class IndexController  extends AdminController
{

    public string \$serviceName = AdminApiService::class;
    
    public function index(): Response
    {
        return view('index/index', ['name' => '$name']);
    }

}

EOF;
        file_put_contents($path, $content);

    }

    /**
     * 创建视图文件
     * 
     * 创建插件的默认首页视图文件
     * 
     * @param string $path 视图文件路径
     * @return void
     */
    protected function createViewFile($path): void
    {
        $content = <<<EOF
<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="shortcut icon" href="/favicon.ico"/>
    <title>webman app plugin</title>

</head>
<body>
hello <?=htmlspecialchars(\$name)?>
</body>
</html>


EOF;
        file_put_contents($path, $content);

    }


    /**
     * 创建函数文件
     * 
     * 创建插件的自定义函数文件
     * 
     * @param string $file 函数文件路径
     * @return void
     */
    protected function createFunctionsFile($file): void
    {
        $content = <<<EOF
<?php
/**
 * Here is your custom functions.
 */



EOF;
        file_put_contents($file, $content);
    }

    /**
     * 创建API安装文件
     * 
     * 创建插件的API安装类文件，包含安装、卸载、更新等方法
     * 
     * @param string $base API目录路径
     * @param string $name 插件名称
     * @return void
     */
    protected function createApiFiles($base, $name): void
    {
        $content = <<<EOF
<?php

namespace plugin\\$name\api;

use plugin\admin\api\Menu;
use support\Db;
use Throwable;

class Install
{

    /**
     * 数据库连接
     */
    protected static \$connection = 'plugin.admin.mysql';
    
    /**
     * 安装
     *
     * @param \$version
     * @return void
     */
    public static function install(\$version)
    {
        // 安装数据库
        static::installSql();
        // 导入菜单
        if(\$menus = static::getMenus()) {
            Menu::import(\$menus);
        }
    }

    /**
     * 卸载
     *
     * @param \$version
     * @return void
     */
    public static function uninstall(\$version)
    {
        // 删除菜单
        foreach (static::getMenus() as \$menu) {
            Menu::delete(\$menu['key']);
        }
        // 卸载数据库
        static::uninstallSql();
    }

    /**
     * 更新
     *
     * @param \$from_version
     * @param \$to_version
     * @param \$context
     * @return void
     */
    public static function update(\$from_version, \$to_version, \$context = null)
    {
        // 删除不用的菜单
        if (isset(\$context['previous_menus'])) {
            static::removeUnnecessaryMenus(\$context['previous_menus']);
        }
        // 安装数据库
        static::installSql();
        // 导入新菜单
        if (\$menus = static::getMenus()) {
            Menu::import(\$menus);
        }
        // 执行更新操作
        \$update_file = __DIR__ . '/../update.php';
        if (is_file(\$update_file)) {
            include \$update_file;
        }
    }

    /**
     * 更新前数据收集等
     *
     * @param \$from_version
     * @param \$to_version
     * @return array|array[]
     */
    public static function beforeUpdate(\$from_version, \$to_version)
    {
        // 在更新之前获得老菜单，通过context传递给 update
        return ['previous_menus' => static::getMenus()];
    }

    /**
     * 获取菜单
     *
     * @return array|mixed
     */
    public static function getMenus()
    {
        clearstatcache();
        if (is_file(\$menu_file = __DIR__ . '/../config/menu.php')) {
            \$menus = include \$menu_file;
            return \$menus ?: [];
        }
        return [];
    }

    /**
     * 删除不需要的菜单
     *
     * @param \$previous_menus
     * @return void
     */
    public static function removeUnnecessaryMenus(\$previous_menus)
    {
        \$menus_to_remove = array_diff(Menu::column(\$previous_menus, 'name'), Menu::column(static::getMenus(), 'name'));
        foreach (\$menus_to_remove as \$name) {
            Menu::delete(\$name);
        }
    }
    
    /**
     * 安装SQL
     *
     * @return void
     */
    protected static function installSql()
    {
        static::importSql(__DIR__ . '/../install.sql');
    }
    
    /**
     * 卸载SQL
     *
     * @return void
     */
    protected static function uninstallSql() {
        // 如果卸载数据库文件存在责直接使用
        \$uninstallSqlFile = __DIR__ . '/../uninstall.sql';
        if (is_file(\$uninstallSqlFile)) {
            static::importSql(\$uninstallSqlFile);
            return;
        }
        // 否则根据install.sql生成卸载数据库文件uninstall.sql
        \$installSqlFile = __DIR__ . '/../install.sql';
        if (!is_file(\$installSqlFile)) {
            return;
        }
        \$installSql = file_get_contents(\$installSqlFile);
        preg_match_all('/CREATE TABLE `(.+?)`/si', \$installSql, \$matches);
        \$dropSql = '';
        foreach (\$matches[1] as \$table) {
            \$dropSql .= "DROP TABLE IF EXISTS `\$table`;\\n";
        }
        file_put_contents(\$uninstallSqlFile, \$dropSql);
        static::importSql(\$uninstallSqlFile);
        unlink(\$uninstallSqlFile);
    }
    
    /**
     * 导入数据库
     *
     * @return void
     */
    public static function importSql(\$mysqlDumpFile)
    {
        if (!\$mysqlDumpFile || !is_file(\$mysqlDumpFile)) {
            return;
        }
        foreach (explode(';', file_get_contents(\$mysqlDumpFile)) as \$sql) {
            if (\$sql = trim(\$sql)) {
                try {
                    Db::connection(static::\$connection)->statement(\$sql);
                } catch (Throwable \$e) {}
            }
        }
    }

}
EOF;

        file_put_contents("$base/Install.php", $content);

    }

    /**
     * 创建安装SQL文件
     * 
     * 创建插件的空SQL安装文件
     * 
     * @param string $file SQL文件路径
     * @return void
     */
    protected function createInstallSqlFile($file): void
    {
        file_put_contents($file, '');
    }

    /**
     * 创建配置文件
     * 
     * 创建插件所需的各种配置文件，包括：
     * app.php, menu.php, autoload.php, container.php, database.php,
     * exception.php, log.php, middleware.php, process.php, redis.php,
     * route.php, static.php, translation.php, view.php, thinkorm.php
     * 
     * @param string $base 配置目录路径
     * @param string $name 插件名称
     * @return void
     */
    protected function createConfigFiles($base, $name): void
    {
        // app.php
        $content = <<<EOF
<?php

use support\\Request;

return [
    'debug' => true,
    'controller_suffix' => 'Controller',
    'controller_reuse' => false,
    'version' => '1.0.0',
    'authors'           => [
        'name'  => '',
        'email' => '',
    ],
];

EOF;
        file_put_contents("$base/app.php", $content);

        // menu.php
        $content = <<<EOF
<?php

return [];

EOF;
        file_put_contents("$base/menu.php", $content);

        // autoload.php
        $content = <<<EOF
<?php
return [
    'files' => [
        base_path() . '/plugin/$name/app/functions.php',
    ]
];
EOF;
        file_put_contents("$base/autoload.php", $content);

        // container.php
        $content = <<<EOF
<?php
return new Webman\\Container;

EOF;
        file_put_contents("$base/container.php", $content);


        // database.php
        $content = <<<EOF
<?php
return  [];

EOF;
        file_put_contents("$base/database.php", $content);

        // exception.php
        $content = <<<EOF
<?php

return [
    '' => support\\exception\\Handler::class,
];

EOF;
        file_put_contents("$base/exception.php", $content);

        // log.php
        $content = <<<EOF
<?php

return [
    'default' => [
        'handlers' => [
            [
                'class' => Monolog\\Handler\\RotatingFileHandler::class,
                'constructor' => [
                    runtime_path() . '/logs/$name.log',
                    7,
                    Monolog\\Logger::DEBUG,
                ],
                'formatter' => [
                    'class' => Monolog\\Formatter\\LineFormatter::class,
                    'constructor' => [null, 'Y-m-d H:i:s', true],
                ],
            ]
        ],
    ],
];

EOF;
        file_put_contents("$base/log.php", $content);

        // middleware.php
        $content = <<<EOF
<?php

return [
        '' => \warm\admin\Admin::middleware(),
];

EOF;
        file_put_contents("$base/middleware.php", $content);

        // process.php
        $content = <<<EOF
<?php
return [];

EOF;
        file_put_contents("$base/process.php", $content);

        // redis.php
        $content = <<<EOF
<?php
return [
    'default' => [
        'host' => '127.0.0.1',
        'password' => null,
        'port' => 6379,
        'database' => 0,
    ],
];

EOF;
        file_put_contents("$base/redis.php", $content);

        // route.php
        $content = <<<EOF
<?php

use Webman\\Route;


EOF;
        file_put_contents("$base/route.php", $content);

        // static.php
        $content = <<<EOF
<?php

return [
    'enable' => true,
    'middleware' => [],    // Static file Middleware
];

EOF;
        file_put_contents("$base/static.php", $content);

        // translation.php
        $content = <<<EOF
<?php

return [
    // Default language
    'locale' => 'zh_CN',
    // Fallback language
    'fallback_locale' => ['zh_CN', 'en'],
    // Folder where language files are stored
    'path' => base_path() . "/plugin/$name/resource/translations",
];

EOF;
        file_put_contents("$base/translation.php", $content);

        // view.php
        $content = <<<EOF
<?php

use support\\view\\Raw;
use support\\view\\Twig;
use support\\view\\Blade;
use support\\view\\ThinkPHP;

return [
    'handler' => Raw::class
];

EOF;
        file_put_contents("$base/view.php", $content);

        // thinkorm.php
        $content = <<<EOF
<?php

return [];

EOF;
        file_put_contents("$base/thinkorm.php", $content);

    }

}