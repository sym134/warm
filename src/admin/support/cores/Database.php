<?php

namespace warm\admin\support\cores;


use Illuminate\Database\Schema\Blueprint;
use support\Db as DB;
use warm\admin\model\AdminRole;
use warm\common\config\ConfigDefaults;
use warm\framework\hash\facade\Hash;

/**
 * 数据库管理类
 * 
 * 用于管理 warm 框架的数据库表结构和初始数据填充
 * 包括创建数据表、删除数据表、初始化数据等操作
 */
class Database
{
    /**
     * @var string|null 模块名称，用于构建表名前缀
     */
    private string|null $moduleName;

    /**
     * 构造函数
     * 
     * @param string|null $moduleName 模块名称
     */
    public function __construct($moduleName = null)
    {
        $this->moduleName = $moduleName;
    }

    /**
     * 创建 Database 实例的静态方法
     * 
     * @param string|null $moduleName 模块名称
     * @return Database 返回 Database 实例
     */
    public static function make($moduleName = null): Database
    {
        return new self($moduleName);
    }

    /**
     * 获取完整的表名（包含模块前缀）
     * 
     * @param string $name 表名
     * @return string 完整的表名
     */
    public function tableName($name): string
    {
        return $this->moduleName . $name;
    }

    /**
     * 创建数据表
     * 
     * @param string $tableName 表名
     * @param callable $callback 创建表的回调函数
     * @return void
     */
    public function create($tableName, $callback): void
    {
        DB::schema()->create($this->tableName($tableName), $callback);
    }

    /**
     * 如果数据表存在则删除
     * 
     * @param string $tableName 表名
     * @return void
     */
    public function dropIfExists($tableName): void
    {
        DB::schema()->dropIfExists($this->tableName($tableName));
    }

    /**
     * 初始化数据库结构
     * 先删除现有表，再重新创建所有表
     * 
     * @return void
     */
    public function initSchema(): void
    {
        $this->down();
        $this->up();
    }

    /**
     * 创建所有数据表
     * 定义并创建系统所需的所有数据表结构
     * 
     * @return void
     */
    public function up(): void
    {
        $this->create('admin_users', function (Blueprint $table) {
            $table->id();
            $table->string('username', 120)->unique();
            $table->string('password', 80);
            $table->tinyInteger('enabled')->default(1);
            $table->string('name')->default('');
            $table->string('avatar')->nullable();
            $table->string('remember_token', 100)->nullable();
            $table->timestamps();
        });

        $this->create('admin_roles', function (Blueprint $table) {
            $table->id();
            $table->string('name', 50)->unique();
            $table->string('slug', 50)->unique();
            $table->timestamps();
        });

        $this->create('admin_permissions', function (Blueprint $table) {
            $table->id();
            $table->string('name', 50)->unique();
            $table->string('slug', 50)->unique();
            $table->text('http_method')->nullable();
            $table->text('http_path')->nullable();
            $table->integer('order')->default(0);
            $table->integer('parent_id')->default(0);
            $table->timestamps();
        });

        $this->create('admin_menus', function (Blueprint $table) {
            $table->id();
            $table->integer('parent_id')->default(0);
            $table->integer('order')->default(0);
            $table->string('title', 100)->comment('菜单名称');
            $table->string('icon', 255)->nullable()->comment('菜单图标');
            $table->string('url')->nullable()->comment('菜单路由');
            $table->tinyInteger('url_type')->default(1)->comment('路由类型(1:路由,2:外链,3:iframe)');
            $table->tinyInteger('visible')->default(1)->comment('是否可见');
            $table->tinyInteger('is_home')->default(0)->comment('是否为首页');
            $table->tinyInteger('keep_alive')->nullable()->comment('页面缓存');
            $table->string('iframe_url')->nullable()->comment('iframe_url');
            $table->string('component')->nullable()->comment('菜单组件');
            $table->tinyInteger('is_full')->default(0)->comment('是否是完整页面');
            $table->string('extension')->nullable()->comment('插件');

            $table->timestamps();
        });

        $this->create('admin_role_users', function (Blueprint $table) {
            $table->integer('role_id');
            $table->integer('user_id');
            $table->index(['role_id', 'user_id']);
            $table->timestamps();
        });

        $this->create('admin_role_permissions', function (Blueprint $table) {
            $table->integer('role_id');
            $table->integer('permission_id');
            $table->index(['role_id', 'permission_id']);
            $table->timestamps();
        });

        $this->create('admin_permission_menu', function (Blueprint $table) {
            $table->integer('permission_id');
            $table->integer('menu_id');
            $table->index(['permission_id', 'menu_id']);
            $table->timestamps();
        });

        // 如果是模块，跳过下面的表
        if ($this->moduleName) {
            return;
        }

        $this->create('admin_code_generators', function (Blueprint $table) {
            $table->id();
            $table->string('title')->default('')->comment('名称');
            $table->string('table_name')->default('')->comment('表名');
            $table->string('primary_key')->default('id')->comment('主键名');
            $table->string('model_name')->default('')->comment('模型名');
            $table->string('controller_name')->default('')->comment('控制器名');
            $table->string('service_name')->default('')->comment('服务名');
            $table->longText('columns')->comment('字段信息');
            $table->tinyInteger('need_timestamps')->default(0)->comment('是否需要时间戳');
            $table->tinyInteger('soft_delete')->default(0)->comment('是否需要软删除');
            $table->text('needs')->nullable()->comment('需要生成的代码');
            $table->text('menu_info')->nullable()->comment('菜单信息');
            $table->text('page_info')->nullable()->comment('页面信息');
            $table->text('save_path')->nullable()->comment('保存位置');
            $table->timestamps();
        });

        $this->create('admin_settings', function (Blueprint $table) {
            $table->string('key')->default('');
            $table->longText('values')->nullable();
            $table->timestamps();
        });

        $this->create('admin_plugins', function (Blueprint $table) {
            $table->id();
            $table->string('key', 100)->unique();
            $table->string('name', 100)->unique();
            $table->tinyInteger('is_enabled')->default(0);
            $table->timestamps();
        });

        $this->create('admin_pages', function (Blueprint $table) {
            $table->id();
            $table->string('title')->comment('页面名称');
            $table->string('sign')->comment('页面标识');
            $table->longText('schema')->comment('页面结构');
            $table->timestamps();
        });

        $this->create('admin_relationships', function (Blueprint $table) {
            $table->id();
            $table->string('model')->comment('模型');
            $table->string('title')->comment('关联名称');
            $table->string('type')->comment('关联类型');
            $table->string('remark')->comment('关联名称')->nullable();
            $table->text('args')->comment('关联参数')->nullable();
            $table->text('extra')->comment('额外参数')->nullable();
            $table->timestamps();
        });

        $this->create('admin_apis', function (Blueprint $table) {
            $table->id();
            $table->string('title')->comment('接口名称');
            $table->string('path')->comment('接口路径');
            $table->string('template')->comment('接口模板');
            $table->tinyInteger('enabled')->default(1)->comment('是否启用');
            $table->longText('args')->comment('接口参数')->nullable();
            $table->timestamps();
        });

        $this->create('system_file_groups', function (Blueprint $table) {
            $table->comment('文件分组');
            $table->increments('id');
            $table->string('name', 100)->comment('分组名称');
            $table->enum('file_type', ['image', 'video', 'audio', 'file'])->nullable()->comment('文件类型（可选，用于筛选）');
            $table->integer('sort')->default(0)->comment('排序');
            $table->tinyInteger('created_by')->comment('创建者');
            $table->timestamps();
        });

        $this->create('system_files', function (Blueprint $table) {
            $table->comment('附件管理');
            $table->increments('id');
            $table->enum('storage_mode', ['local', 'qiniu', 'aliyun', 'qcloud'])->comment('存储模式');
            $table->string('origin_name')->nullable()->comment('原文件名');
            $table->string('new_name')->nullable()->comment('新文件名');
            $table->string('hash')->nullable()->comment('文件hash');
            $table->enum('file_type', ['image', 'video', 'audio', 'file'])->comment('资源类型');
            $table->string('mime_type')->comment('资源类型');
            $table->string('storage_path')->nullable()->comment('存储目录');
            $table->bigInteger('size_byte')->comment('字节数');
            $table->string('file_size')->nullable()->comment('文件大小');
            $table->string('url')->nullable()->comment('url地址');
            $table->string('remark')->nullable()->comment('备注');
            $table->unsignedInteger('group_id')->nullable()->comment('分组ID');
            $table->tinyInteger('created_by')->comment('创建者');
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('group_id')->references('id')->on('system_file_groups')->onDelete('set null');
        });

        $this->create('admin_operation_log', function (Blueprint $table) {
            $table->comment('操作日志');
            $table->increments('id');
            $table->string('username', 20)->nullable()->comment('用户名');
            $table->string('app', 50)->nullable()->comment('应用名称');
            $table->string('method')->nullable()->comment('请求方式');
            $table->string('router')->nullable()->comment('请求路由');
            $table->string('service_name')->nullable()->comment('业务名称');
            $table->string('ip', 45)->nullable()->comment('请求IP地址');
            $table->string('ip_location')->nullable()->comment('IP所属地');
            $table->text('request_data')->nullable()->comment('请求数据');
            $table->string('remark')->nullable()->comment('备注');
            $table->bigInteger('created_by')->index()->comment('创建者');
            $table->dateTime('created_at')->nullable();
            $table->softDeletes();
        });

        $this->create('admin_login_log', function (Blueprint $table) {
            $table->comment('登录日志');
            $table->increments('id');
            $table->string('username')->nullable()->comment('用户名');
            $table->string('ip')->nullable()->comment('登录IP地址');
            $table->string('ip_location')->nullable()->comment('IP所属地');
            $table->string('os', 50)->nullable()->comment('操作系统');
            $table->string('browser', 50)->nullable()->comment('浏览器');
            $table->unsignedSmallInteger('status')->default(new \Illuminate\Database\Query\Expression('1'))->comment('登录状态');
            $table->string('message', 50)->nullable()->comment('提示消息');
            $table->dateTime('login_time')->nullable()->comment('登录时间');
            $table->string('remark')->nullable()->comment('备注');
            $table->dateTime('created_at')->nullable();
            $table->softDeletes();
        });

        $this->create('system_crontab', function (Blueprint $table) {
            $table->comment('定时任务');
            $table->increments('id');
            $table->string('name')->nullable()->comment('任务名称');
            $table->unsignedSmallInteger('task_type')->comment('任务类型');
            $table->enum('execution_cycle', ['day', 'hour', 'week', 'month', 'second-n', 'day-n', 'hour-n', 'minute-n'])->comment('执行周期');
            $table->string('target', 500)->nullable()->comment('调用目标');
            $table->string('parameter', 1000)->nullable()->comment('任务参数');
            $table->string('rule', 32)->nullable()->comment('表达式');
            $table->unsignedTinyInteger('week')->default(1)->comment('周');
            $table->unsignedTinyInteger('day')->default(1)->comment('天');
            $table->unsignedTinyInteger('hour')->default(0)->comment('小时');
            $table->unsignedTinyInteger('minute')->default(0)->comment('分钟');
            $table->unsignedTinyInteger('second')->default(0)->comment('秒');
            $table->unsignedTinyInteger('task_status')->default(0)->comment('状态');
            $table->string('remark')->nullable()->comment('备注');
            $table->unsignedInteger('created_by')->comment('创建者');
            $table->timestamps();
            $table->softDeletes();
            $table->unique(['name', 'deleted_at']);
        });

        $this->create('system_crontab_log', function (Blueprint $table) {
            $table->comment('定时任务日志');
            $table->increments('id');
            $table->unsignedInteger('crontab_id')->index()->comment('任务ID');
            $table->string('target', 500)->comment('调用目标');
            $table->string('parameter', 1000)->comment('调用参数');
            $table->string('exception_info', 2000)->nullable()->comment('异常信息');
            $table->unsignedTinyInteger('execution_status')->default(0)->comment('执行状态');
            $table->timestamps();
        });

        $this->create('wechat_menu', function (Blueprint $table) {
            $table->comment('微信菜单表');
            $table->increments('id')->comment('主键ID');
            $table->string('name', 50)->default('')->comment('菜单名称');
            $table->string('type', 20)->default('click')->comment('菜单类型：click-关键字,view-跳转链接,miniprogram-小程序');
            $table->string('key', 128)->default('')->comment('关键字（type为click时使用）');
            $table->string('url', 512)->default('')->comment('链接地址（type为view时使用）');
            $table->string('appid', 100)->default('')->comment('小程序AppID（type为miniprogram时使用）');
            $table->string('pagepath', 255)->default('')->comment('小程序路径（type为miniprogram时使用）');
            $table->string('miniprogram_url', 512)->default('')->comment('小程序备用网址（type为miniprogram时使用）');
            $table->unsignedInteger('parent_id')->default(0)->comment('父菜单ID，0表示一级菜单');
            $table->integer('sort')->default(0)->comment('排序，数字越小越靠前');
            $table->timestamps();

            // 添加索引
            $table->index('parent_id', 'idx_parent_id');
            $table->index('sort', 'idx_sort');
            $table->index('type', 'idx_type');
        });

        $this->create('system_configs', function (Blueprint $table) {
            $table->string('key', 255)->unique()->comment('配置键');
            $table->json('values')->comment('配置值');
            $table->timestamp('created_at')->nullable()->comment('创建时间');
            $table->timestamp('updated_at')->nullable()->comment('更新时间');

            $table->primary('key');
        });
    }

    /**
     * 删除所有数据表
     * 如果是模块则跳过删除操作
     * 
     * @return void
     */
    public function down(): void
    {
        // 如果是模块，跳过下面的表
        if ($this->moduleName) {
            return;
        }

        $this->dropIfExists('admin_users');
        $this->dropIfExists('admin_roles');
        $this->dropIfExists('admin_permissions');
        $this->dropIfExists('admin_menus');
        $this->dropIfExists('admin_role_users');
        $this->dropIfExists('admin_role_permissions');
        $this->dropIfExists('admin_permission_menu');
        $this->dropIfExists('admin_code_generators');
        $this->dropIfExists('admin_settings');
        $this->dropIfExists('admin_plugins');
        $this->dropIfExists('admin_pages');
        $this->dropIfExists('admin_relationships');
        $this->dropIfExists('admin_apis');
        $this->dropIfExists('system_files');
        $this->dropIfExists('admin_operation_log');
        $this->dropIfExists('admin_login_log');
        $this->dropIfExists('system_crontab');
        $this->dropIfExists('system_crontab_log');
        $this->dropIfExists('wechat_menu');
    }

    /**
     * 填充初始数据
     * 创建默认的管理员账户、角色、权限、菜单等初始数据
     *
     * @return void
     */
    public function fillInitialData(): void
    {
        // 数据处理闭包，用于格式化数据并添加时间戳
        $data = function ($data) {
            foreach ($data as $k => $v) {
                if (is_array($v)) {
                    $data[$k] = "['" . implode("','", $v) . "']";
                }
            }
            $now = date('Y-m-d H:i:s');

            return array_merge($data, ['created_at' => $now, 'updated_at' => $now]);
        };

        $adminUser = DB::table($this->tableName('admin_users'));
        $adminMenu = DB::table($this->tableName('admin_menus'));
        $adminPermission = DB::table($this->tableName('admin_permissions'));
        $adminRole = DB::table($this->tableName('admin_roles'));

        // 创建初始用户
        $adminUser->truncate();
        $adminUser->insert($data([
            'username' => 'admin',
            'password' => Hash::make('admin'),
            'name'     => 'Administrator',
        ]));

        // 创建初始角色
        $adminRole->truncate();
        $adminRole->insert($data([
            'name' => 'Administrator',
            'slug' => AdminRole::SuperAdministrator,
        ]));

        // 用户 - 角色绑定
        DB::table($this->tableName('admin_role_users'))->truncate();
        DB::table($this->tableName('admin_role_users'))->insert($data([
            'role_id' => 1,
            'user_id' => 1,
        ]));

        // 创建初始权限
        $adminPermission->truncate();
        $adminPermission->insert([
            $data(['name' => 'dashboard', 'slug' => 'home', 'http_path' => ['/home*'], "parent_id" => 0]),
            $data(['name' => 'system_settings', 'slug' => 'system', 'http_path' => '', "parent_id" => 0]),
            $data(['name' => 'admin_permission_management', 'slug' => 'admin_permission_management', 'http_path' => '', "parent_id" => 0]),
            $data(['name' => 'admin_monitor', 'slug' => 'admin_monitor', 'http_path' => '', "parent_id" => 0]),
            $data(['name' => 'application', 'slug' => 'app', 'http_path' => '', "parent_id" => 0]),

            $data(['name' => 'admin_log_monitoring', 'slug' => 'admin_log_monitoring', 'http_path' => '', "parent_id" => 4]),

            $data(['name' => 'api_setting', 'slug' => 'api', 'http_path' => '', "parent_id" => 2]),
            $data(['name' => 'sms_setting', 'slug' => '', 'http_path' => '/setting/other_config/sms/index', "parent_id" => 7]),


            $data(['name' => 'admin_users', 'slug' => 'admin_users', 'http_path' => ["/admin_users*"], "parent_id" => 3]),
            $data(['name' => 'admin_roles', 'slug' => 'roles', 'http_path' => ["/roles*"], "parent_id" => 3]),
            $data(['name' => 'admin_permission', 'slug' => 'permissions', 'http_path' => ["/permissions*"], "parent_id" => 3]),
            $data(['name' => 'admin_menu', 'slug' => 'menus', 'http_path' => ["/menus*"], "parent_id" => 3]),

            $data(['name' => 'admin_operation_log', 'slug' => 'admin_operation_log', 'http_path' => ["/log_monitoring/admin_operation_log*"], "parent_id" => 5]),
            $data(['name' => 'admin_login_log', 'slug' => 'admin_login_log', 'http_path' => ["/log_monitoring/admin_login_log*"], "parent_id" => 5]),

            $data(['name' => 'crontab', 'slug' => 'crontab', 'http_path' => ["/system/crontab*"], "parent_id" => 2]),

        ]);

        // 角色 - 权限绑定
        DB::table($this->tableName('admin_role_permissions'))->truncate();
        $permissionIds = DB::table($this->tableName('admin_permissions'))->orderBy('id')->pluck('id');
        foreach ($permissionIds as $id) {
            DB::table($this->tableName('admin_role_permissions'))->insert($data([
                'role_id'       => 1,
                'permission_id' => $id,
            ]));
        }

        // 创建初始菜单
        $adminMenu->truncate();

        // 构建菜单树状结构
        // 菜单结构说明：
        // - parent_id: 父级菜单ID，0表示顶级菜单
        // - order: 菜单排序，数字越小越靠前
        // - title: 菜单标题（多语言键名）
        // - icon: 菜单图标（使用iconify图标库）
        // - url: 菜单路由地址
        // - url_type: 路由类型（1:路由,2:外链,3:iframe）
        // - visible: 是否可见（1:可见,0:隐藏）
        // - is_home: 是否为首页（1:是,0:否）
        // - keep_alive: 页面缓存（0:不缓存,1:缓存,null:默认）
        // - component: 菜单组件（amis:Amis页面,null:默认页面）
        // - children: 子菜单数组
        $menuTree = [
            // === 一级菜单：仪表盘 ===
            // 路由：/dashboard
            // 图标：图表线条
            // 首页菜单，系统默认主页
            [
                'parent_id' => 0,
                'order' => 0,
                'title' => 'dashboard',
                'icon' => 'mdi:chart-line',
                'url' => '/dashboard',
                'url_type' => 1,
                'visible' => 1,
                'is_home' => 1,
                'keep_alive' => null,
                'iframe_url' => null,
                'component' => null,
                'is_full' => 0,
                'extension' => null,
                'children' => []
            ],
            // === 一级菜单：应用管理 ===
            // 路由：/app
            // 图标：应用商店
            // 包含微信公众号、小程序等应用配置
            [
                'parent_id' => 0,
                'order' => 5,
                'title' => 'application',
                'icon' => 'ant-design:appstore-outlined',
                'url' => '/app',
                'url_type' => 1,
                'visible' => 1,
                'is_home' => 0,
                'keep_alive' => 0,
                'iframe_url' => null,
                'component' => 'amis',
                'is_full' => 0,
                'extension' => null,
                'children' => [
                    // --- 二级菜单：微信公众号 ---
                    // 路由：/app/wechat_official
                    [
                        'parent_id' => 0,
                        'order' => 0,
                        'title' => 'official_account',
                        'icon' => null,
                        'url' => '/app/wechat_official',
                        'url_type' => 1,
                        'visible' => 1,
                        'is_home' => 0,
                        'keep_alive' => 0,
                        'iframe_url' => null,
                        'component' => 'amis',
                        'is_full' => 0,
                        'extension' => null,
                        'children' => [
                            // ----- 三级菜单：公众号配置 -----
                            // 路由：/app/wechat/official_account_config
                            // 功能：微信公众号基础配置
                            [
                                'parent_id' => 0,
                                'order' => 0,
                                'title' => 'official_account_config',
                                'icon' => null,
                                'url' => '/app/wechat/official_account_config',
                                'url_type' => 1,
                                'visible' => 1,
                                'is_home' => 0,
                                'keep_alive' => 0,
                                'iframe_url' => null,
                                'component' => 'amis',
                                'is_full' => 0,
                                'extension' => null,
                                'children' => []
                            ],
                            // ----- 三级菜单：自动回复 -----
                            // 路由：/app/wechat/auto_reply
                            // 功能：关键词回复、默认回复、关注回复配置
                            [
                                'parent_id' => 0,
                                'order' => 10,
                                'title' => 'auto_reply',
                                'icon' => null,
                                'url' => '/app/wechat/auto_reply',
                                'url_type' => 1,
                                'visible' => 1,
                                'is_home' => 0,
                                'keep_alive' => 0,
                                'iframe_url' => null,
                                'component' => 'amis',
                                'is_full' => 0,
                                'extension' => null,
                                'children' => [
                                    // ------- 四级菜单：关键词回复 -------
                                    // 路由：/app/wechat/keyword
                                    // 功能：设置关键词触发的自动回复
                                    [
                                        'parent_id' => 0,
                                        'order' => 0,
                                        'title' => 'keywords',
                                        'icon' => null,
                                        'url' => '/app/wechat/keyword',
                                        'url_type' => 1,
                                        'visible' => 1,
                                        'is_home' => 0,
                                        'keep_alive' => 0,
                                        'iframe_url' => null,
                                        'component' => 'amis',
                                        'is_full' => 0,
                                        'extension' => null,
                                        'children' => []
                                    ],
                                    // ------- 四级菜单：默认回复 -------
                                    // 路由：/app/wechat/reply/default
                                    // 功能：设置无匹配关键词时的默认回复
                                    [
                                        'parent_id' => 0,
                                        'order' => 10,
                                        'title' => 'default_reply',
                                        'icon' => null,
                                        'url' => '/app/wechat/reply/default',
                                        'url_type' => 1,
                                        'visible' => 1,
                                        'is_home' => 0,
                                        'keep_alive' => 0,
                                        'iframe_url' => null,
                                        'component' => 'amis',
                                        'is_full' => 0,
                                        'extension' => null,
                                        'children' => []
                                    ],
                                    // ------- 四级菜单：关注回复 -------
                                    // 路由：/app/wechat/reply/subscribe
                                    // 功能：设置用户关注公众号时的欢迎回复
                                    [
                                        'parent_id' => 0,
                                        'order' => 20,
                                        'title' => 'subscribe_reply',
                                        'icon' => null,
                                        'url' => '/app/wechat/reply/subscribe',
                                        'url_type' => 1,
                                        'visible' => 1,
                                        'is_home' => 0,
                                        'keep_alive' => 0,
                                        'iframe_url' => null,
                                        'component' => 'amis',
                                        'is_full' => 0,
                                        'extension' => null,
                                        'children' => []
                                    ]
                                ]
                            ],
                            // ----- 三级菜单：微信菜单 -----
                            // 路由：/system/wechat_menu
                            // 功能：微信公众号自定义菜单管理
                            [
                                'parent_id' => 0,
                                'order' => 20,
                                'title' => 'wechat_menu',
                                'icon' => null,
                                'url' => '/system/wechat_menu',
                                'url_type' => 1,
                                'visible' => 1,
                                'is_home' => 0,
                                'keep_alive' => 0,
                                'iframe_url' => null,
                                'component' => 'amis',
                                'is_full' => 0,
                                'extension' => null,
                                'children' => []
                            ]
                        ]
                    ],
                    // --- 二级菜单：小程序 ---
                    // 路由：/app/wechat_mini
                    [
                        'parent_id' => 0,
                        'order' => 10,
                        'title' => 'mini_program',
                        'icon' => null,
                        'url' => '/app/wechat_mini',
                        'url_type' => 1,
                        'visible' => 1,
                        'is_home' => 0,
                        'keep_alive' => 0,
                        'iframe_url' => null,
                        'component' => 'amis',
                        'is_full' => 0,
                        'extension' => null,
                        'children' => [
                            // ----- 三级菜单：小程序配置 -----
                            // 路由：/app/wechat/mini_program_config
                            // 功能：微信小程序基础配置
                            [
                                'parent_id' => 0,
                                'order' => 0,
                                'title' => 'mini_program_config',
                                'icon' => null,
                                'url' => '/app/wechat/mini_program_config',
                                'url_type' => 1,
                                'visible' => 1,
                                'is_home' => 0,
                                'keep_alive' => 0,
                                'iframe_url' => null,
                                'component' => 'amis',
                                'is_full' => 0,
                                'extension' => null,
                                'children' => []
                            ]
                        ]
                    ]
                ]
            ],
            // === 一级菜单：系统设置 ===
            // 路由：/system
            // 图标：设置轮廓
            // 包含存储设置、附件管理、短信配置等系统功能
            [
                'parent_id' => 0,
                'order' => 2,
                'title' => 'system_settings',
                'icon' => 'material-symbols:settings-outline',
                'url' => '/system',
                'url_type' => 1,
                'visible' => 1,
                'is_home' => 0,
                'keep_alive' => null,
                'iframe_url' => null,
                'component' => null,
                'is_full' => 0,
                'extension' => null,
                'children' => [
                    // --- 二级菜单：存储设置 ---
                    // 路由：/system/storage
                    // 功能：文件存储引擎配置（本地、OSS、七牛云等）
                    [
                        'parent_id' => 0, // 将在插入时动态设置
                        'order' => 1,
                        'title' => 'admin_storage',
                        'icon' => 'akar-icons:settings-horizontal',
                        'url' => '/system/storage',
                        'url_type' => 1,
                        'visible' => 1,
                        'is_home' => 0,
                        'keep_alive' => null,
                        'iframe_url' => null,
                        'component' => null,
                        'is_full' => 0,
                        'extension' => null,
                        'children' => []
                    ],
                    // --- 二级菜单：附件管理 ---
                    // 路由：/system/file
                    // 功能：文件上传、分组管理、文件浏览
                    [
                        'parent_id' => 0,
                        'order' => 2,
                        'title' => 'attachment',
                        'icon' => 'grommet-icons:attachment',
                        'url' => '/system/file',
                        'url_type' => 1,
                        'visible' => 1,
                        'is_home' => 0,
                        'keep_alive' => null,
                        'iframe_url' => null,
                        'component' => null,
                        'is_full' => 0,
                        'extension' => null,
                        'children' => []
                    ],
                    // --- 二级菜单：短信设置 ---
                    // 路由：/setting/other_config/sms/sms_config
                    // 功能：短信服务商配置、模板管理
                    [
                        'parent_id' => 0,
                        'order' => 3,
                        'title' => 'sms_setting',
                        'icon' => 'la:sms',
                        'url' => '/setting/other_config/sms/sms_config',
                        'url_type' => 1,
                        'visible' => 1,
                        'is_home' => 0,
                        'keep_alive' => 0,
                        'iframe_url' => null,
                        'component' => 'amis',
                        'is_full' => 0,
                        'extension' => null,
                        'children' => []
                    ],
                    // --- 二级菜单：支付配置 ---
                    // 路由：/system/payment_config
                    // 功能：支付渠道配置、支付参数管理
                    [
                        'parent_id' => 0,
                        'order' => 4,
                        'title' => 'payment',
                        'icon' => 'ant-design:pay-circle-outlined',
                        'url' => '/system/payment_config',
                        'url_type' => 1,
                        'visible' => 1,
                        'is_home' => 0,
                        'keep_alive' => 0,
                        'iframe_url' => null,
                        'component' => 'amis',
                        'is_full' => 0,
                        'extension' => null,
                        'children' => []
                    ],
                    // --- 二级菜单：系统维护 ---
                    // 路由：/system_maintenance
                    // 功能：定时任务、日志监控、缓存清理等维护功能
                    [
                        'parent_id' => 0,
                        'order' => 5,
                        'title' => 'system_maintenance',
                        'icon' => 'carbon:license-maintenance',
                        'url' => '/system_maintenance',
                        'url_type' => 1,
                        'visible' => 1,
                        'is_home' => 0,
                        'keep_alive' => null,
                        'iframe_url' => null,
                        'component' => null,
                        'is_full' => 0,
                        'extension' => null,
                        'children' => [
                            // ----- 三级菜单：定时任务 -----
                            // 路由：/system/crontab
                            // 功能：系统定时任务配置和管理
                            [
                                'parent_id' => 0,
                                'order' => 1,
                                'title' => 'crontab',
                                'icon' => 'ant-design:menu-unfold-outlined',
                                'url' => '/system/crontab',
                                'url_type' => 1,
                                'visible' => 1,
                                'is_home' => 0,
                                'keep_alive' => null,
                                'iframe_url' => null,
                                'component' => null,
                                'is_full' => 0,
                                'extension' => null,
                                'children' => []
                            ],
                            // ----- 三级菜单：日志监控 -----
                            // 路由：/admin_log_monitoring
                            // 功能：系统操作日志和登录日志监控
                            [
                                'parent_id' => 0,
                                'order' => 2,
                                'title' => 'admin_log_monitoring',
                                'icon' => 'eos-icons:monitoring',
                                'url' => '/admin_log_monitoring',
                                'url_type' => 1,
                                'visible' => 1,
                                'is_home' => 0,
                                'keep_alive' => null,
                                'iframe_url' => null,
                                'component' => null,
                                'is_full' => 0,
                                'extension' => null,
                                'children' => [
                                    // ------- 四级菜单：登录日志 -------
                                    // 路由：/log_monitoring/admin_login_log
                                    // 功能：管理员登录记录查看
                                    [
                                        'parent_id' => 0,
                                        'order' => 2,
                                        'title' => 'admin_login_log',
                                        'icon' => 'basil:login-outline',
                                        'url' => '/log_monitoring/admin_login_log',
                                        'url_type' => 1,
                                        'visible' => 1,
                                        'is_home' => 0,
                                        'keep_alive' => null,
                                        'iframe_url' => null,
                                        'component' => null,
                                        'is_full' => 0,
                                        'extension' => null,
                                        'children' => []
                                    ],
                                    // ------- 四级菜单：操作日志 -------
                                    // 路由：/log_monitoring/admin_operation_log
                                    // 功能：管理员操作行为记录查看
                                    [
                                        'parent_id' => 0,
                                        'order' => 3,
                                        'title' => 'admin_operation_log',
                                        'icon' => 'carbon:cloud-logging',
                                        'url' => '/log_monitoring/admin_operation_log',
                                        'url_type' => 1,
                                        'visible' => 1,
                                        'is_home' => 0,
                                        'keep_alive' => null,
                                        'iframe_url' => null,
                                        'component' => null,
                                        'is_full' => 0,
                                        'extension' => null,
                                        'children' => []
                                    ],
                                ]
                            ],
                            // ----- 三级菜单：缓存清理 -----
                            // 路由：/system/cache
                            // 功能：系统缓存清理和刷新
                            [
                                'parent_id' => 0,
                                'order' => 3,
                                'title' => 'cache_clear',
                                'icon' => 'mdi:broom',
                                'url' => '/system/cache',
                                'url_type' => 1,
                                'visible' => 1,
                                'is_home' => 0,
                                'keep_alive' => null,
                                'iframe_url' => null,
                                'component' => null,
                                'is_full' => 0,
                                'extension' => null,
                                'children' => []
                            ],
                        ]
                    ],
                ]
            ],
            // === 一级菜单：权限管理 ===
            // 路由：/admin_permission_management
            // 图标：锁图标
            // 包含用户、角色、权限、菜单等RBAC权限管理功能
            [
                'parent_id' => 0,
                'order' => 3,
                'title' => 'admin_permission_management',
                'icon' => 'akar-icons:lock-on',
                'url' => '/admin_permission_management',
                'url_type' => 1,
                'visible' => 1,
                'is_home' => 0,
                'keep_alive' => null,
                'iframe_url' => null,
                'component' => null,
                'is_full' => 0,
                'extension' => null,
                'children' => [
                    // --- 二级菜单：管理员用户 ---
                    // 路由：/system/admin_users
                    // 功能：后台管理员账号管理
                    [
                        'parent_id' => 0,
                        'order' => 0,
                        'title' => 'admin_users',
                        'icon' => 'ph:user-gear',
                        'url' => '/system/admin_users',
                        'url_type' => 1,
                        'visible' => 1,
                        'is_home' => 0,
                        'keep_alive' => null,
                        'iframe_url' => null,
                        'component' => null,
                        'is_full' => 0,
                        'extension' => null,
                        'children' => []
                    ],
                    // --- 二级菜单：角色管理 ---
                    // 路由：/system/admin_roles
                    // 功能：用户角色分配和管理
                    [
                        'parent_id' => 0,
                        'order' => 10,
                        'title' => 'admin_roles',
                        'icon' => 'carbon:user-role',
                        'url' => '/system/admin_roles',
                        'url_type' => 1,
                        'visible' => 1,
                        'is_home' => 0,
                        'keep_alive' => null,
                        'iframe_url' => null,
                        'component' => null,
                        'is_full' => 0,
                        'extension' => null,
                        'children' => []
                    ],
                    // --- 二级菜单：权限管理 ---
                    // 路由：/system/admin_permissions
                    // 功能：系统权限点配置和管理
                    [
                        'parent_id' => 0,
                        'order' => 20,
                        'title' => 'admin_permission',
                        'icon' => 'fluent-mdl2:permissions',
                        'url' => '/system/admin_permissions',
                        'url_type' => 1,
                        'visible' => 1,
                        'is_home' => 0,
                        'keep_alive' => null,
                        'iframe_url' => null,
                        'component' => null,
                        'is_full' => 0,
                        'extension' => null,
                        'children' => []
                    ],
                    // --- 二级菜单：菜单管理 ---
                    // 路由：/system/admin_menus
                    // 功能：后台菜单结构配置和管理
                    [
                        'parent_id' => 0,
                        'order' => 30,
                        'title' => 'admin_menu',
                        'icon' => 'ant-design:menu-unfold-outlined',
                        'url' => '/system/admin_menus',
                        'url_type' => 1,
                        'visible' => 1,
                        'is_home' => 0,
                        'keep_alive' => null,
                        'iframe_url' => null,
                        'component' => null,
                        'is_full' => 0,
                        'extension' => null,
                        'children' => []
                    ]
                ]
            ],
            // === 一级菜单：通知公告 ===
            // 路由：/setting/other_config/notice/n
            // 功能：系统通知公告发布和管理
            [
                'parent_id' => 0,
                'order' => 4,
                'title' => 'notification',
                'icon' => null,
                'url' => '/setting/other_config/notice/n',
                'url_type' => 1,
                'visible' => 1,
                'is_home' => 0,
                'keep_alive' => 0,
                'iframe_url' => null,
                'component' => 'amis',
                'is_full' => 0,
                'extension' => null,
                'children' => []
            ]
        ];

        // 递归插入菜单数据
        $this->insertMenuTree($adminMenu, $menuTree, 0, $data);

        // 权限 - 菜单绑定
        DB::table($this->tableName('admin_permission_menu'))->truncate();
        $menus = $adminMenu->get();
        foreach ($menus as $menu) {
            $_list = [];
            $_list[] = $data(['permission_id' => $menu->id, 'menu_id' => $menu->id]);

            if ($menu->parent_id != 0) {
                $_list[] = $data(['permission_id' => $menu->parent_id, 'menu_id' => $menu->id]);
            }

            DB::table($this->tableName('admin_permission_menu'))->insert($_list);
        }

        // 默认中文
        systemConfig()->set(ConfigDefaults::KEY_ADMIN_LOCALE, 'zh_CN');

        // 默认存储设置
        systemConfig()->set('storage', [
            "upload_size" => 5242880,
            "file_type"   => "txt,doc,docx,xls,xlsx,ppt,pptx,rar,zip,7z,gz,pdf,wps,md",
            "image_type"  => "jpg,jpeg,png,gif,svg,bmp",
            "engine"      => "local",
            "local"       => [
                "domain" => "http://127.0.0.1:8787",
                "path"   => "public",
            ],
        ]);
    }

    /**
     * 递归插入菜单树数据
     * 
     * @param \Illuminate\Database\Query\Builder $adminMenu 菜单表查询构建器
     * @param array $menuTree 菜单树结构
     * @param int $parentId 父级菜单ID
     * @param callable $data 数据处理闭包
     * @return void
     */
    private function insertMenuTree($adminMenu, array $menuTree, int $parentId, callable $data): void
    {
        foreach ($menuTree as $menu) {
            // 设置正确的 parent_id
            $menuData = $menu;
            $menuData['parent_id'] = $parentId;

            // 移除 children 字段，因为数据库表中没有这个字段
            unset($menuData['children']);

            // 插入菜单数据
            $menuId = $adminMenu->insertGetId($data($menuData));

            // 递归插入子菜单
            if (!empty($menu['children'])) {
                $this->insertMenuTree($adminMenu, $menu['children'], $menuId, $data);
            }
        }
    }

    /**
     * 获取所有数据表名称
     * 
     * @return array 数据表名称数组
     */
    public static function getTables(): array
    {
        return array_column(DB::schema()->getTables(), 'name');
    }
}
