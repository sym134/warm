<?php

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Arr;
use support\Container;
use support\Db;
use support\Translation;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\BufferedOutput;
use think\Validate;
use warm\admin\admin;
use warm\admin\model\AdminUser;
use warm\admin\renderer\expand\Amis;
use warm\admin\renderer\expand\Component;
use warm\admin\service\AdminPageService;
use warm\common\service\SystemConfigService;
use warm\exception\AdminException;
use warm\framework\cache\facade\Cache;
use warm\framework\filesystem\facade\Storage;
use warm\framework\hash\facade\Hash;
use Webman\Console\Command;
use Webman\Console\Util;

if (!function_exists('app')) {
    /**
     * Get the available container instance.
     *
     * @template TClass of object
     *
     * @param null $abstract
     * @param array $parameters
     * @return ($abstract is class-string<TClass> ? TClass : ($abstract is null ? mixed : mixed))
     */
    function app($abstract = null, array $parameters = []): mixed
    {
        // 使用 webman Container
        if (is_null($abstract)) {
            return \support\Container::instance();
        }

        // 尝试从容器获取
        // 注意：webman Container 的 has() 可能不会识别所有 instance() 注册的服务
        // 所以我们直接尝试 get()，如果失败再尝试其他方式
        try {
            $instance = \support\Container::get($abstract);
            // 如果是闭包，执行它
            if ($instance instanceof \Closure) {
                return $instance();
            }
            // 如果是字符串类名，实例化它
            if (is_string($instance) && class_exists($instance)) {
                return \support\Container::make($instance, $parameters);
            }
            return $instance;
        } catch (\Throwable $e) {
            // get() 失败，尝试直接实例化类
            if (class_exists($abstract)) {
                return \support\Container::make($abstract, $parameters);
            }
            // 如果都不是，抛出原始异常或新的异常
            throw new \RuntimeException("Service [{$abstract}] not found.", 0, $e);
        }
    }
}

/**
 * 验证函数
 *
 * 生成并返回验证对象，支持验证器类和验证规则数组两种方式
 *
 * @param string|array $validate 验证器类名或者验证规则数组
 * @param array $message 错误提示信息
 * @param bool $batch 是否批量验证
 * @param bool $failException 是否抛出异常
 * @return Validate 验证对象
 */
if (!function_exists('validate')) {
    /**
     * 生成验证对象
     * @param array|string $validate 验证器类名或者验证规则数组
     * @param array $message 错误提示信息
     * @param bool $batch 是否批量验证
     * @param bool $failException 是否抛出异常
     * @return Validate
     */
    function validate(array|string $validate = '', array $message = [], bool $batch = false, bool $failException = true): Validate
    {
        if (is_array($validate) || '' === $validate) {
            $v = new Validate();
            if (is_array($validate)) {
                $v->rule($validate);
            }
        } else {
            if (str_contains($validate, '.')) {
                // 支持场景
                [$validate, $scene] = explode('.', $validate);
            }

            $class = str_contains($validate, '\\') ? $validate : app()->parseClass('validate', $validate);

            $v = new $class();

            if (!empty($scene)) {
                $v->scene($scene);
            }
        }

        return $v->message($message)->batch($batch)->failException($failException);
    }
}

if (!function_exists('cache')) {
    /**
     * @param array|string|null $key
     * @param mixed|null $default
     * @return Cache|bool
     */
    function cache(array|string|null $key = null, mixed $default = null): Cache|bool
    {
        if (is_null($key)) {
            return new Cache();
        }

        if (is_string($key)) {
            return Cache::get($key, $default);
        }

        if (!is_array($key)) {
            throw new InvalidArgumentException(
                'When setting a value in the cache, you must pass an array of key / value pairs.'
            );
        }

        return Cache::put(key($key), Arr::first($key), $default);
    }
}

/**
 * Bcrypt哈希函数
 *
 * 对给定值进行bcrypt哈希处理
 *
 * @param string $value 需要哈希的值
 * @param array $options 哈希选项
 * @return string 哈希后的值
 */
if (!function_exists('bcrypt')) {
    /**
     * Hash the given value.
     *
     * @param string $value
     * @param array $options
     * @return string
     */
    function bcrypt(string $value, array $options = []): string
    {
        return Hash::make($value, $options);
    }
}

/**
 * 生成管理后台URL
 *
 * 根据路径生成管理后台URL，可选择是否添加前缀
 *
 * @param string|null $path 路径
 * @param bool $needPrefix 是否需要添加前缀
 * @return string 完整的URL
 */
if (!function_exists('admin_url')) {
    function admin_url($path = null, $needPrefix = false): string
    {
        $prefix = $needPrefix ? Admin::warmConfig('app.route.prefix') : '';

        return $prefix . '/' . trim($path, '/');
    }
}

/**
 * 获取数据表字段列表
 *
 * 获取指定数据表的所有字段名称
 *
 * @param string $tableName 数据表名
 * @return array 字段名称数组
 */
if (!function_exists('table_columns')) {
    /**
     * 获取表字段
     *
     * @param $tableName
     *
     * @return array
     */
    function table_columns($tableName): array
    {
        return Db::schema()->getColumnListing($tableName);
    }
}

/**
 * 数组转树形结构
 *
 * 将扁平的数组结构转换为树形结构
 *
 * @param array $list 扁平的数组列表
 * @param int $parentId 父级ID
 * @return array 树形结构数组
 */
if (!function_exists('array2tree')) {
    /**
     * 生成树状数据
     *
     * @param array $list
     * @param int $parentId
     *
     * @return array
     */
    function array2tree(array $list, int $parentId = 0): array
    {
        $data = [];
        foreach ($list as $key => $item) {
            if ($item['parent_id'] == $parentId) {
                $children = array2tree($list, (int)$item['id']);
                !empty($children) && $item['children'] = $children;
                $data[] = $item;
                unset($list[$key]);
            }
        }
        return $data;
    }
}

/**
 * 获取资源完整路径
 *
 * 根据路径和服务器信息生成资源的完整访问路径
 *
 * @param string $path 资源路径
 * @param string|null $server 服务器地址
 * @return array|string|null 完整路径
 */
if (!function_exists('admin_resource_full_path')) {
    function admin_resource_full_path($path, $server = null): array|string|null
    {
        if (!$path) {
            return '';
        }
        $src = '';
        if (filter_var($path, FILTER_VALIDATE_URL) || mb_strpos($path, 'data:image') === 0) {
            $src = $path;
        } else if ($server) {
            $src = rtrim($server, '/') . 'helpers.php/' . ltrim($path, '/');
        } else {
            Storage::url($path);
        }
        $scheme = 'http:';
        if (Admin::warmConfig('app.https', false)) {
            $scheme = 'https:';
        }
        return preg_replace('/^https?:/', $scheme, $src, 1);
    }
}

/**
 * Amis组件构建函数
 *
 * 创建Amis组件实例，用于构建Amis界面
 *
 * @param string|null $type 组件类型
 * @return Amis|Component Amis组件实例
 */
if (!function_exists('amis')) {
    /**
     * @param $type
     *
     * @return Amis|Component
     */
    function amis($type = null): Amis|Component
    {
        if (filled($type)) {
            return Component::make()->setType($type);
        }

        return Amis::make();
    }
}

/**
 * 创建Amis实例（已弃用）
 *
 * 创建并返回Amis实例，该方法已被弃用，建议使用amis()函数
 *
 * @return Amis Amis实例
 * @deprecated
 */
if (!function_exists('amisMake')) {
    /**
     * @return Amis
     * @deprecated
     */
    function amisMake(): Amis
    {
        return Amis::make();
    }
}

/**
 * 文件上传处理
 *
 * 处理文件上传的显示和存储问题
 *
 * @return \Illuminate\Database\Eloquent\Casts\Attribute 文件上传属性
 */
if (!function_exists('file_upload_handle')) {
    /**
     * 处理文件上传回显问题
     *
     * @return \Illuminate\Database\Eloquent\Casts\Attribute
     */
    function file_upload_handle(): \Illuminate\Database\Eloquent\Casts\Attribute
    {
        return \Illuminate\Database\Eloquent\Casts\Attribute::make(
            get: fn($value) => $value ? Storage::url($value) : '',
            set: fn($value) => str_replace(Storage::url(''), '', $value)
        );
    }
}

/**
 * 多文件上传处理
 *
 * 处理多个文件上传的显示和存储问题
 *
 * @return \Illuminate\Database\Eloquent\Casts\Attribute 多文件上传属性
 */
if (!function_exists('file_upload_handle_multi')) {
    /**
     * 处理文件上传回显问题 (多个)
     *
     * @return \Illuminate\Database\Eloquent\Casts\Attribute
     */
    function file_upload_handle_multi(): \Illuminate\Database\Eloquent\Casts\Attribute
    {
        return \Illuminate\Database\Eloquent\Casts\Attribute::make(
            get: function ($value) {
                return array_map(fn($item) => $item ? admin_resource_full_path($item) : '', explode(',', $value));
            },
            set: function ($value) {
                $url = Storage::url('');
                if (is_string($value)) {
                    return str_replace($url, '', $value);
                }

                $list = array_map(fn($item) => str_replace($url, '', $item), Arr::wrap($value));

                return implode(',', $list);
            }
        );
    }
}

/**
 * 判断是否为JSON字符串
 *
 * 检查给定字符串是否为有效的JSON格式
 *
 * @param string $string 待检查的字符串
 * @return bool 是否为JSON字符串
 */
// 是否是json字符串
if (!function_exists('is_json')) {
    /**
     * 是否是json字符串
     *
     * @param $string
     *
     * @return bool
     */
    function is_json($string): bool
    {
        return is_string($string) && is_array(json_decode($string, true)) && (json_last_error() == JSON_ERROR_NONE);
    }
}

/**
 * 获取配置服务实例
 *
 * 创建并返回配置服务实例
 *
 * @return SystemConfigService 配置服务实例
 */
if (!function_exists('systemConfig')) {
    function systemConfig(): SystemConfigService
    {
        return new SystemConfigService;
    }
}

/**
 * 获取扩展路径
 *
 * 获取管理后台扩展的路径
 *
 * @param string|null $path 相对路径
 * @return string 完整路径
 */
if (!function_exists('admin_extension_path')) {
    /**
     * @param string|null $path
     *
     * @return string
     */
    function admin_extension_path(?string $path = ''): string
    {
        $dir = rtrim(Admin::warmConfig('app.plugin.dir'), '/') ?: base_path('extensions');

        $path = ltrim($path, '/');

        return $path ? $dir . '/' . $path : $dir;
    }
}

/**
 * 获取当前管理员用户
 *
 * 获取当前登录的管理员用户信息
 *
 * @return AdminUser|Authenticatable|null 管理员用户对象或null
 */
if (!function_exists('admin_user')) {
    function admin_user(): AdminUser|Authenticatable|null
    {
        return Admin::user();
    }
}

/**
 * 管理后台异常处理函数
 *
 * 抛出管理后台异常，支持自定义消息、数据和提示控制
 */
if (!function_exists('admin_abort')) {
    /**
     * 抛出异常
     *
     * @param string $message 异常信息
     * @param array $data 异常数据
     * @param int $doNotDisplayToast 是否显示提示 (解决在 amis 中抛出异常时，会显示两次提示的问题)
     *
     * @return mixed
     * @throws null
     */
    function admin_abort(string $message = '', array $data = [], int $doNotDisplayToast = 0): mixed
    {
        throw new AdminException($message, $data, $doNotDisplayToast);
    }

    /**
     * 抛出Amis异常（不显示提示）
     *
     * @param string $message 异常信息
     * @param array $data 异常数据
     * @return void
     */
    function amis_abort(string $message = '', array $data = []): void
    {
        admin_abort($message, $data, 1);
    }

    /**
     * 条件异常抛出
     *
     * 如果条件成立，则抛出异常
     *
     * @param boolean $flag 条件
     * @param string $message 异常信息
     * @param array $data 异常数据
     * @param int $doNotDisplayToast 是否显示提示 (解决在 amis 中抛出异常时，会显示两次提示的问题)
     *
     * @return void
     */
    function admin_abort_if(bool $flag, string $message = '', array $data = [], int $doNotDisplayToast = 0): void
    {
        if ($flag) {
            admin_abort($message, $data, $doNotDisplayToast);
        }
    }
}

/**
 * 获取管理后台路径
 *
 * 获取管理后台相关文件的完整路径
 *
 * @param string $path 相对路径
 * @return string 完整路径
 */
if (!function_exists('admin_path')) {
    function admin_path($path = ''): string
    {
        $path = ltrim($path, '/');

        return base_path('/vendor/jizhi/warm/src/admin/' . $path);
    }
}

/**
 * 获取页面结构数据
 *
 * 根据标识符获取页面结构数据
 *
 * @param string $sign 页面标识符
 * @return mixed 页面结构数据
 */
if (!function_exists('admin_pages')) {
    function admin_pages($sign)
    {
        return AdminPageService::make()->get($sign);
    }
}

/**
 * 映射转选项
 *
 * 将键值对映射转换为选项数组格式
 *
 * @param array $map 键值对映射
 * @return array 选项数组
 */
if (!function_exists('map2options')) {
    /**
     * 键作为value, 值作为label, 返回options格式
     *
     * @param $map
     *
     * @return array
     */
    function map2options($map): array
    {
        return collect($map)->map(fn($v, $k) => ['label' => $v, 'value' => $k])->values()->toArray();
    }
}

/**
 * 语言翻译函数
 * XX应用::翻译文件.变量
 * 根据键名获取翻译后的文本
 *
 * @param string $key 翻译键名
 * @param array $replace 替换参数
 * @param string|null $locale 语言标识
 * @return string|null 翻译后的文本
 */
if (!function_exists('translator')) {
    function translator(string $key, array $replace = [], string|null $locale = null): ?string
    {
        if (empty($key)) {
            return $key;
        }
        if (str_contains($key, '::')) {
            [$domain, $item] = explode('::', $key, 2);
            $itemSegments = explode('.', $item);
            try {
                return Translation::instance($domain)
                    ->trans(count($itemSegments) === 1 ? null : implode('.', array_slice($itemSegments, 1)), $replace, $itemSegments[0], $locale);
            } catch (\Webman\Exception\NotFoundException $e) {
                return $key;
            }
        } else {
            $itemSegments = explode('.', $key);
            return Translation::trans(count($itemSegments) === 1 ? null : implode('.', array_slice($itemSegments, 1)), $replace, $itemSegments[0], $locale);
        }
    }
}

/**
 * 插件路径函数
 *
 * 获取插件目录的完整路径
 *
 * @param string $path 相对路径
 * @return string 完整路径
 */
if (!function_exists('plugin_path')) {
    function plugin_path(string $path = ''): string
    {
        return path_combine(BASE_PATH . DIRECTORY_SEPARATOR . 'plugin', $path);
    }
}

/**
 * URL生成函数
 *
 * 根据路由名称生成URL
 *
 * @param string $val 路由名称
 * @return string URL地址
 */
if (!function_exists('url')) {
    function url($val): string
    {
        return route($val);
    }
}

/**
 * 中止执行函数
 *
 * 抛出带有指定代码和消息的异常
 *
 * @param int $code 错误代码
 * @param string $message 错误消息
 * @return void
 * @throws Exception
 */
if (!function_exists('abort')) {
    /**
     * @throws Exception
     */
    function abort($code, $message)
    {
        throw new Exception($message, $code);
    }
}

/**
 * 运行命令函数
 *
 * 执行指定的控制台命令
 *
 * @param string $commandName 命令名称
 * @param array $arguments 命令参数
 * @return array 执行结果数组，第一个元素为是否成功，第二个为输出内容
 */
if (!function_exists('runCommand')) {
    function runCommand(string $commandName, array $args = []): array
    {
        try {
            // 创建命令实例
            $cli = new Command();
            $cli->setName('webman cli');
            $cli->setAutoExit(false);
            $cli->installInternalCommands();

            // 安装应用命令
            if (is_dir($command_path = Util::guessPath(app_path(), '/command', true))) {
                $cli->installCommands($command_path);
            }

            foreach (config('plugin', []) as $firm => $projects) {
                if (isset($projects['app'])) {
                    foreach (['', '/app'] as $app) {
                        if ($command_str = Util::guessPath(base_path() . "/plugin/$firm{$app}", 'command')) {
                            $command_path = base_path() . "/plugin/$firm{$app}/$command_str";
                            $cli->installCommands($command_path, "plugin\\$firm" . str_replace('/', '\\', $app) . "\\$command_str");
                        }
                    }
                }
                foreach ($projects as $name => $project) {
                    if (!is_array($project)) {
                        continue;
                    }
                    foreach ($project['command'] ?? [] as $class_name) {
                        $reflection = new \ReflectionClass($class_name);
                        if ($reflection->isAbstract()) {
                            continue;
                        }
                        $properties = $reflection->getStaticProperties();
                        $name = $properties['defaultName'];
                        if (!$name) {
                            throw new RuntimeException("Command {$class_name} has no defaultName");
                        }
                        $description = $properties['defaultDescription'] ?? '';
                        $command = Container::get($class_name);
                        $command->setName($name)->setDescription($description);
                        $cli->add($command);
                    }
                }
            }

            // 构建输入：命令名 + 参数
            $inputArray = array_merge([$commandName], $args);

            // 执行命令
            $input = new ArrayInput($inputArray);
            $output = new BufferedOutput();
            $exitCode = $cli->run($input, $output);
            return ['status' => $exitCode === 0, 'output' => $output->fetch()];

//            return [$exitCode === 0, $output->fetch()];
        } catch (\Throwable $e) {
            return [false, $e->getMessage()];
        }
    }
}

/**
 * 数据库路径函数
 *
 * 获取数据库相关文件的路径
 *
 * @param string $name 文件名
 * @return string 完整路径
 */
if (!function_exists('database_path')) {
    function database_path($name = ''): string
    {
        $path = base_path('database');
        return $name ? $path . '/' . ltrim($name, '/') : $path;
    }
}

/**
 * 安全分割函数
 *
 * 可安全处理数组的分割函数
 *
 * @param string $delimiter 分隔符
 * @param string|array $string 待分割的字符串或数组
 * @return array|false 分割结果
 */
if (!function_exists('safe_explode')) {
    /**
     * 可传入数组的 explode
     *
     * @param $delimiter
     * @param $string
     *
     * @return array|false|string[]
     */
    function safe_explode($delimiter, $string): array|bool
    {
        if (is_array($string)) {
            return $string;
        }

        return explode($delimiter, $string);
    }
}

if (!function_exists('file_upload_handle')) {
    /**
     * 处理文件上传回显问题
     *
     * @return \Illuminate\Database\Eloquent\Casts\Attribute
     */
    function file_upload_handle(): \Illuminate\Database\Eloquent\Casts\Attribute
    {
        return \Illuminate\Database\Eloquent\Casts\Attribute::make(
            get: fn($value) => $value ? Storage::url($value) : '',
            set: fn($value) => str_replace(Storage::url(''), '', $value)
        );
    }
}

if (!function_exists('file_upload_handle_multi')) {
    /**
     * 处理文件上传回显问题 (多个)
     *
     * @return \Illuminate\Database\Eloquent\Casts\Attribute
     */
    function file_upload_handle_multi(): \Illuminate\Database\Eloquent\Casts\Attribute
    {

        return \Illuminate\Database\Eloquent\Casts\Attribute::make(
            get: function ($value) {
                return array_values(array_filter(array_map(fn($item) => $item ? Storage::url($item) : '', explode(',', $value))));
            },
            set: function ($value) {
                if (is_string($value)) {
                    return str_replace(Storage::url(''), '', $value);
                }

                $list = array_map(fn($item) => str_replace(Storage::url(''), '', $item), Arr::wrap($value));

                return implode(',', $list);
            }
        );
    }
}

if (!function_exists('pluginContainer')) {
    /**
     * @param string|null $pluginName
     * @return array|mixed|void|null
     */
    function pluginContainer(string $pluginName = null)
    {
        return \support\Container::instance($pluginName);
    }
}

if (!function_exists('isCoroutineEnabled')){
    // 判断是否开启协程（任意驱动）
    function isCoroutineEnabled(): bool
    {
        $eventLoop = \Workerman\Worker::getEventLoop();
        // 判断是否为协程驱动
        return $eventLoop instanceof Workerman\Events\Swoole
            || $eventLoop instanceof Workerman\Events\Swow
            || $eventLoop instanceof Workerman\Events\Fiber;
    }
}