<?php

namespace warm\admin\support\cores;


use Illuminate\Database\Schema\Blueprint;
use support\Db as DB;
use warm\framework\facade\Hash;

class Database
{
    private string|null $moduleName;

    public function __construct($moduleName = null)
    {
        $this->moduleName = $moduleName;
    }

    public static function make($moduleName = null): Database
    {
        return new self($moduleName);
    }

    public function tableName($name): string
    {
        return $this->moduleName . $name;
    }

    public function create($tableName, $callback): void
    {
        DB::schema()->create($this->tableName($tableName), $callback);
    }

    public function dropIfExists($tableName): void
    {
        DB::schema()->dropIfExists($this->tableName($tableName));
    }

    public function initSchema(): void
    {
        $this->down();
        $this->up();
    }

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
            $table->string('icon', 100)->nullable()->comment('菜单图标');
            $table->string('url')->nullable()->comment('菜单路由');
            $table->tinyInteger('url_type')->default(1)->comment('路由类型(1:路由,2:外链,3:iframe)');
            $table->tinyInteger('visible')->default(1)->comment('是否可见');
            $table->tinyInteger('is_home')->default(0)->comment('是否为首页');
            $table->tinyInteger('keep_alive')->nullable()->comment('页面缓存');
            $table->string('iframe_url')->nullable()->comment('iframe_url');
            $table->string('component')->nullable()->comment('菜单组件');
            $table->tinyInteger('is_full')->default(0)->comment('是否是完整页面');
            $table->string('extension')->nullable()->comment('扩展');

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

        $this->create('admin_extensions', function (Blueprint $table) {
            $table->id();
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

        $this->create('files', function (Blueprint $table) {
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
            $table->tinyInteger('created_by')->comment('创建者');
            $table->timestamps();
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

        $this->create('crontab', function (Blueprint $table) {
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
            $table->unique(['name', 'deleted_at']);
        });

        $this->create('crontab_log', function (Blueprint $table) {
            $table->comment('定时任务日志');
            $table->increments('id');
            $table->unsignedInteger('crontab_id')->index()->comment('任务ID');
            $table->string('target', 500)->comment('调用目标');
            $table->string('parameter', 1000)->comment('调用参数');
            $table->string('exception_info', 2000)->nullable()->comment('异常信息');
            $table->unsignedTinyInteger('execution_status')->default(0)->comment('执行状态');
            $table->dateTime('created_at')->nullable()->comment('创建时间');
        });

        $this->create('config', function (Blueprint $table) {
            $table->string('key')->unique();
            $table->json('values');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        $this->dropIfExists('admin_users');
        $this->dropIfExists('admin_roles');
        $this->dropIfExists('admin_permissions');
        $this->dropIfExists('admin_menus');
        $this->dropIfExists('admin_role_users');
        $this->dropIfExists('admin_role_permissions');
        $this->dropIfExists('admin_permission_menu');

        // 如果是模块，跳过下面的表
        if ($this->moduleName) {
            return;
        }

        $this->dropIfExists('admin_code_generators');
        $this->dropIfExists('admin_settings');
        $this->dropIfExists('admin_extensions');
        $this->dropIfExists('admin_pages');
        $this->dropIfExists('admin_relationships');
        $this->dropIfExists('admin_apis');
        $this->dropIfExists('config');
        $this->schema()->dropIfExists('files');
    }

    /**
     * 填充初始数据
     *
     * @return void
     */
    public function fillInitialData(): void
    {
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
            'slug' => 'administrator',
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
            $data(['name' => '首页', 'slug' => 'home', 'http_path' => ['/home*'], "parent_id" => 0]),
            $data(['name' => '系统', 'slug' => 'system', 'http_path' => '', "parent_id" => 0]),
            $data(['name' => '权限管理', 'slug' => 'admin_permission_management', 'http_path' => '', "parent_id" => 0]),
            $data(['name' => '监控', 'slug' => 'admin_monitor', 'http_path' => '', "parent_id" => 0]),
            $data(['name' => '监控日志', 'slug' => 'admin_log_monitoring', 'http_path' => '', "parent_id" => 4]),

            $data(['name' => '管理员', 'slug' => 'admin_users', 'http_path' => ["/admin_users*"], "parent_id" => 3]),
            $data(['name' => '角色', 'slug' => 'roles', 'http_path' => ["/roles*"], "parent_id" => 3]),
            $data(['name' => '权限', 'slug' => 'permissions', 'http_path' => ["/permissions*"], "parent_id" => 3]),
            $data(['name' => '菜单', 'slug' => 'menus', 'http_path' => ["/menus*"], "parent_id" => 3]),

            $data(['name' => '操作日志', 'slug' => 'admin_operation_log', 'http_path' => ["/log_monitoring/admin_operation_log*"], "parent_id" => 5]),
            $data(['name' => '登陆日志', 'slug' => 'admin_login_log', 'http_path' => ["/log_monitoring/admin_login_log*"], "parent_id" => 5]),
            $data(['name' => '定时任务', 'slug' => 'crontab', 'http_path' => ["/system/crontab*"], "parent_id" => 2]),
            $data(['name' => '定时任务日志', 'slug' => 'crontab_log', 'http_path' => ["/system/crontab_log*"], "parent_id" => 2]),

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
        $adminMenu->insert([
            $data([
                'parent_id' => 0,
                'title'     => 'dashboard',
                'icon'      => 'mdi:chart-line',
                'url'       => '/dashboard',
                'is_home'   => 1,
            ]),
            // 系统 2
            $data([
                'parent_id' => 0,
                'title'     => 'admin_system',
                'icon'      => 'material-symbols:settings-outline',
                'url'       => '/system',
                'is_home'   => 0,
            ]),
            // 权限管理 3
            $data([
                'parent_id' => 0,
                'title'     => 'admin_permission_management',
                'icon'      => 'akar-icons:lock-on',
                'url'       => '/admin_permission_management',
                'is_home'   => 0,
            ]),
            // 监控 4
            $data([
                'parent_id' => 0,
                'title'     => 'admin_monitor',
                'icon'      => 'eos-icons:monitoring',
                'url'       => '/admin_monitor',
                'is_home'   => 0,
            ]),
            // 日志监控
            $data([
                'parent_id' => 4,
                'title'     => 'admin_log_monitoring',
                'icon'      => 'eos-icons:monitoring',
                'url'       => '/admin_log_monitoring',
                'is_home'   => 0,
            ]),
            // 登陆日志
            $data([
                'parent_id' => 5,
                'title'     => 'admin_login_log',
                'icon'      => 'basil:login-outline',
                'url'       => '/log_monitoring/admin_login_log',
                'is_home'   => 0,
            ]),
            // 操作日志
            $data([
                'parent_id' => 5,
                'title'     => 'admin_operation_log',
                'icon'      => 'carbon:cloud-logging',
                'url'       => '/log_monitoring/admin_operation_log',
                'is_home'   => 0,
            ]),
            $data([
                'parent_id' => 3,
                'title'     => 'admin_users',
                'icon'      => 'ph:user-gear',
                'url'       => '/system/admin_users',
                'is_home'   => 0,
            ]),
            $data([
                'parent_id' => 3,
                'title'     => 'admin_roles',
                'icon'      => 'carbon:user-role',
                'url'       => '/system/admin_roles',
                'is_home'   => 0,
            ]),
            $data([
                'parent_id' => 3,
                'title'     => 'admin_permission',
                'icon'      => 'fluent-mdl2:permissions',
                'url'       => '/system/admin_permissions',
                'is_home'   => 0,
            ]),
            $data([
                'parent_id' => 3,
                'title'     => 'admin_menu',
                'icon'      => 'ant-design:menu-unfold-outlined',
                'url'       => '/system/admin_menus',
                'is_home'   => 0,
            ]),
            $data([
                'parent_id' => 2,
                'title'     => 'admin_storage',
                'icon'      => 'akar-icons:settings-horizontal',
                'url'       => '/system/storage',
                'is_home'   => 0,
            ]),
            $data([
                'parent_id' => 2,
                'title'     => 'attachment',
                'icon'      => 'grommet-icons:attachment',
                'url'       => '/system/file',
                'is_home'   => 0,
            ]),
            $data([
                'parent_id' => 2,
                'title'     => 'crontab',
                'icon'      => 'ant-design:menu-unfold-outlined',
                'url'       => '/system/crontab',
                'is_home'   => 0,
            ]),
        ]);

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
        warmConfig()->set('admin_locale', 'zh_CN');

        // 默认存储设置
        warmConfig()->set('storage', [
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

    public static function getTables(): array
    {
        try {
            return collect(json_decode(json_encode(Db::schema()->getAllTables()), true))
            ->map(fn($i) => config('database.default') == 'sqlite' ? $i['name'] : array_shift($i))
                ->toArray();
        } catch (\Throwable $e) {
        }

        // laravel 11+
        return array_column(Db::schema()->getTables(), 'name');  // webman
    }
}
