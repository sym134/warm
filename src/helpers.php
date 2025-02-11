<?php

use support\Container;
use think\Validate;
use warm\admin\service\StorageService;

if (!function_exists('validate')) {
    /**
     * 生成验证对象
     * @param string|array $validate      验证器类名或者验证规则数组
     * @param array        $message       错误提示信息
     * @param bool         $batch         是否批量验证
     * @param bool         $failException 是否抛出异常
     * @return Validate
     */
    function validate($validate = '', array $message = [], bool $batch = false, bool $failException = true): Validate
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

if (! function_exists('bcrypt')) {
    /**
     * Hash the given value.
     *
     * @param  string  $value
     * @param  array   $options
     * @return string
     */
    function bcrypt($value, $options = [])
    {
        return appw('hash')->make($value, $options);
    }
}


if (!function_exists('admin_url')) {
    function admin_url($path = null, $needPrefix = false): string
    {
        $prefix = $needPrefix ? '/' . \warm\admin\Admin::config('app.route.prefix') : '';

        return $prefix . '/' . trim($path, '/');
    }
}

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
        return \support\Db::schema()->getColumnListing($tableName);
    }
}

if (!function_exists('array2tree')) {
    /**
     * 生成树状数据
     *
     * @param array $list
     * @param int   $parentId
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

if (!function_exists('admin_resource_full_path')) {
    function admin_resource_full_path($path, $server = null): array|string|null
    {
        if (!$path) {
            return '';
        }
        if (filter_var($path,FILTER_VALIDATE_URL) || mb_strpos($path, 'data:image') === 0) {
            $src = $path;
        } else if ($server) {
            $src = rtrim($server, '/') . 'helpers.php/' . ltrim($path, '/');
        } else {
            $disk = \warm\admin\Admin::config('app.upload.disk');

            if (config("filesystems.disks.{$disk}")) {
                $src = StorageService::disk()->url($path);
            } else {
                $src = '';
            }
        }
        $scheme = 'http:';
        if (\warm\admin\Admin::config('app.https', false)) {
            $scheme = 'https:';
        }
        return preg_replace('/^http[s]{0,1}:/', $scheme, $src, 1);
    }
}

if (!function_exists('amis')) {
    /**
     * @param $type
     *
     * @return \warm\admin\renderer\Amis|\warm\admin\renderer\Component
     */
    function amis($type = null): \warm\admin\renderer\Amis|\warm\admin\renderer\Component
    {
        if (filled($type)) {
            return \warm\admin\renderer\Component::make()->setType($type);
        }

        return \warm\admin\renderer\Amis::make();
    }
}

if (!function_exists('amisMake')) {
    /**
     * @return \warm\admin\renderer\Amis
     * @deprecated
     */
    function amisMake(): \warm\admin\renderer\Amis
    {
        return \warm\admin\renderer\Amis::make();
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
        $storage = StorageService::disk();

        return \Illuminate\Database\Eloquent\Casts\Attribute::make(
            get: fn($value) => $value ? $storage->url($value) : '',
            set: fn($value) => str_replace($storage->url(''), '', $value)
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
        $storage = StorageService::disk();

        return \Illuminate\Database\Eloquent\Casts\Attribute::make(
            get: function ($value) use ($storage) {
                return array_map(fn($item) => $item ? admin_resource_full_path($item) : '', explode(',', $value));
            },
            set: function ($value) use ($storage) {
                if (is_string($value)) {
                    return str_replace($storage->url(''), '', $value);
                }

                $list = array_map(fn($item) => str_replace($storage->url(''), '', $item), \Illuminate\Support\Arr::wrap($value));

                return implode(',', $list);
            }
        );
    }
}

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

if (!function_exists('warmConfig')) {
    function warmConfig(): \warm\common\service\ConfigService
    {
        return \warm\common\service\ConfigService::make();
    }
}

if (!function_exists('admin_extension_path')) {
    /**
     * @param string|null $path
     *
     * @return string
     */
    function admin_extension_path(?string $path = ''): string
    {
        $dir = rtrim(\warm\admin\Admin::config('app.extension.dir'), '/') ?: base_path('extensions');

        $path = ltrim($path, '/');

        return $path ? $dir . '/' . $path : $dir;
    }
}

if (!function_exists('admin_user')) {
    function admin_user(): \warm\admin\model\AdminUser|\Illuminate\Contracts\Auth\Authenticatable|null
    {
        return \warm\admin\Admin::user();
    }
}

if (!function_exists('admin_abort')) {
    /**
     * 抛出异常
     *
     * @param string $message           异常信息
     * @param array  $data              异常数据
     * @param int    $doNotDisplayToast 是否显示提示 (解决在 amis 中抛出异常时，会显示两次提示的问题)
     *
     * @return mixed
     * @throws null
     */
    function admin_abort(string $message = '', array $data = [], int $doNotDisplayToast = 0): mixed
    {
        throw new \warm\exception\AdminException($message, $data, $doNotDisplayToast);
    }

    function amis_abort($message = '', $data = []): void
    {
        admin_abort($message, $data, 1);
    }

    /**
     * 如果条件成立，抛出异常
     *
     * @param boolean $flag              条件
     * @param string  $message           异常信息
     * @param array   $data              异常数据
     * @param int     $doNotDisplayToast 是否显示提示 (解决在 amis 中抛出异常时，会显示两次提示的问题)
     *
     * @return void
     */
    function admin_abort_if($flag, $message = '', $data = [], $doNotDisplayToast = 0): void
    {
        if ($flag) {
            admin_abort($message, $data, $doNotDisplayToast);
        }
    }

    function amis_abort_if($flag, $message = '', $data = []): void
    {
        admin_abort_if($flag, $message, $data, 1);
    }
}

if (!function_exists('admin_path')) {
    function admin_path($path = ''): string
    {
        $path = ltrim($path, '/');

        return base_path('/vendor/jizhi/warm/src/warm/admin/' . $path);
    }
}

if (!function_exists('admin_pages')) {
    function admin_pages($sign)
    {
        return \warm\admin\service\AdminPageService::make()->get($sign);
    }
}

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

if (!function_exists('admin_trans')) {
    function admin_trans(string|null $key = null, array $replace = [], string|null $locale = null): ?string
    {
        if (is_null($key)) {
            return $key;
        }
        $arr = explode('.', $key);
        return trans(str_replace($arr[0] . '.', '', $key), $replace, $arr[0], $locale);
    }
}

if (!function_exists('plugin_path')) {
    function plugin_path(string $path = ''): string
    {
        return path_combine(BASE_PATH . DIRECTORY_SEPARATOR . 'plugin', $path);
    }
}

if (!function_exists('url')) {
    function url($val): string
    {
        return route($val);
    }
}

if (!function_exists('abort')) {
    /**
     * @throws Exception
     */
    function abort($code, $message)
    {
        throw new Exception($message, $code);
    }
}

if (!function_exists('runCommand')) {
    // 执行命令
    function runCommand(string $commandName, array $arguments = []): array
    {
        $array = explode(' ', 'php webman ' . $commandName);
        $array = array_merge($array, $arguments);
        // 创建进程对象
        $process = new Symfony\Component\Process\Process($array);
        // 执行命令
        $process->run();
        return [$process->isSuccessful(), $process->getOutput()];
    }
}

if (!function_exists('appw')) {
    /**
     * 获取容器实例或从容器中解析依赖
     * 
     * @param string|null $abstract 要解析的依赖标识
     * @param array $parameters 解析时的参数
     * @return mixed|Container
     */
    function appw(string $abstract = null, array $parameters = [])
    {
        if (is_null($abstract)) {
            return Container::instance('jizhi.warm');
        }
        return Container::instance('jizhi.warm')->get($abstract);
    }
}

if (!function_exists('database_path')) {
    function database_path($name): string
    {
        return 'database/' . $name;
    }
}

if (!function_exists('cache')) {
    function cache(): \warm\framework\facade\Cache
    {
        return new \warm\framework\facade\Cache();
    }
}

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

if (!function_exists('admin_pipeline')) {
    function admin_pipeline($passable)
    {
        return \warm\admin\support\Pipeline::handle($passable);
    }
}
