<?php

namespace warm\admin\support\code_generator;

use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use support\Model;

/**
 * 代码生成器基础类
 * 
 * 提供代码生成器的通用功能，包括文件操作、类名处理、命名空间处理等基础方法
 * 所有具体的代码生成器都应继承此类
 */
class BaseGenerator
{
    /** @var array 存储已生成的文件路径 */
    protected static array $files = [];

    /** @var string 主键字段名 */
    protected string $primaryKey = '';

    /** @var string 标题 */
    protected string $title = '';

    /** @var Model 模型实例 */
    protected Model $model;

    /**
     * 构造函数
     * 
     * @param Model $model 模型实例
     */
    public function __construct($model)
    {
        $this->model = $model;
    }

    /**
     * 创建静态实例方法
     * 
     * @param Model $model 模型实例
     * @return static 返回当前类的实例
     */
    public static function make(Model $model): static
    {
        return new static($model);
    }

    /**
     * 设置主键
     * 
     * @param string $key 主键字段名
     * @return static 返回当前实例以支持链式调用
     */
    public function primary($key): static
    {
        $this->primaryKey = $key;

        return $this;
    }

    /**
     * 设置标题
     * 
     * @param string $title 标题
     * @return static 返回当前实例以支持链式调用
     */
    public function title($title): static
    {
        $this->title = $title;

        return $this;
    }

    /**
     * 猜测类文件路径
     * 
     * 根据类名获取其对应的文件路径，支持PSR-4自动加载标准
     * 
     * @param string|object $class 类名或类实例
     * @return bool|string 类文件路径，如果找不到则返回false
     */
    public static function guessClassFileName($class): bool|string
    {
        if (is_object($class)) {
            $class = get_class($class);
        }

        try {
            if (class_exists($class)) {
                return (new \ReflectionClass($class))->getFileName();
            }
        } catch (\Throwable $e) {
        }

        $class        = trim($class, '\\');
        $autoloadFile = base_path('/vendor/autoload.php');
        $loader       = require $autoloadFile;
        $prefix       = explode($class, '\\')[0];
        // 获取并处理命名空间和路径映射
        $map = collect($loader->getPrefixesPsr4())
            ->mapWithKeys(function ($path, $namespace) {
                $namespace = trim($namespace, '\\') . '\\';
                $path      = str_replace([base_path() . '/', base_path() . '\\'], '', realpath(current($path)) . '/');
                return [$namespace => [$namespace, $path]];
            })
            ->sortKeysDesc(SORT_STRING);

        if ($map->isEmpty()) {
            if (Str::startsWith($class, 'App\\')) {
                $values = ['App\\', 'app/'];
            }
        } else {
            $values = $map->filter(function ($_, $k) use ($class) {
                return Str::startsWith($class, $k);
            })->first();
            // $values[1] .= '/';  // webman根目录没有/
        }

        if (empty($values)) {
            $values = [$prefix . '\\', self::slug($prefix) . '/'];
        }

        [$namespace, $path] = $values;
        return base_path(str_replace(["/", $namespace, '\\'], ["\\", $path, '/'], $class)) . '.php';
    }

    /**
     * 将驼峰命名转换为短横线分隔命名
     * 
     * @param string $name 驼峰命名的字符串
     * @param string $symbol 分隔符，默认为短横线
     * @return array|string 转换后的字符串
     */
    public static function slug(string $name, string $symbol = '-'): array|string
    {
        $text = preg_replace_callback('/([A-Z])/', function ($text) use ($symbol) {
            return $symbol . strtolower($text[1]);
        }, $name);

        return str_replace('_', $symbol, ltrim($text, $symbol));
    }

    /**
     * 获取命名空间
     * 
     * 从完整类名中提取命名空间部分
     * 
     * @param string $name 完整类名
     * @return string 命名空间
     */
    protected function getNamespace($name): string
    {
        return trim(implode('\\', array_slice(explode('\\', str_replace('/', '\\', $name)), 0, -1)), '\\');
    }

    /**
     * 写入文件
     * 
     * 将生成的内容写入指定文件
     * 
     * @param string $name 类名
     * @param string $type 文件类型
     * @return bool|string 文件路径，如果写入失败则返回false
     */
    protected function writeFile($name, $type): bool|string
    {
        $name = str_replace('/', '\\', $name);
        $path = static::guessClassFileName($name);
        $dir  = dirname($path);

        $files = (new \Illuminate\Filesystem\Filesystem);

        if (!is_dir($dir)) {
            $files->makeDirectory($dir, 0755, true);
        }

        if ($files->exists($path)) {
            abort(400, "{$type} [{$name}] already exists!");
        }

        $content = $this->assembly();

        $files->put($path, $content);
        $files->chmod($path, 0777);

        return $path;
    }

    /**
     * 将JSON字符串转换为PHP数组字符串
     * 
     * @param string $jsonString JSON字符串
     * @return mixed 转换后的PHP数组字符串或原始字符串
     */
    protected function jsonToStringArray($jsonString)
    {
        // 首先，检查输入是否为有效的JSON字符串
        if (!is_json($jsonString)) {
            return $jsonString;
        }

        $dataArray = json_decode($jsonString, true);

        // 遍历数组，确保所有的字符串都正确处理Unicode编码，特别是中文
        array_walk_recursive($dataArray, function (&$item) {
            if (is_string($item)) {
                $item = mb_convert_encoding($item, 'UTF-8', 'UTF-8');
            }
        });

        // 使用var_export()生成数组的字符串表示，然后将其返回
        $phpArrayString = var_export($dataArray, true);

        // 转换为短数组语法
        $phpArrayString = preg_replace('/array \(/', '[', $phpArrayString);
        $phpArrayString = preg_replace('/\)$/', ']', $phpArrayString);
        $phpArrayString = str_replace(')', ']', $phpArrayString);

        // 去除数字索引
        $phpArrayString = preg_replace('/\d+ => /', '', $phpArrayString);

        // 去除多余的空格和换行，使输出更为紧凑
        $phpArrayString = preg_replace('/ =>\s+\[/', '=> [', $phpArrayString);

        return str_replace(["\r\n", "\r", "\n", "\t", "  "], '', $phpArrayString);
    }

    /**
     * 构建组件属性字符串
     * 
     * @param array $property 属性数组
     * @return string 构建后的属性字符串
     */
    protected function buildComponentProperty($property): string
    {
        return collect($property)->map(function ($item) {
            $_val = Arr::get($item, 'value');

            if (is_json($_val)) {
                return '->' . Arr::get($item, 'name') . '(' . $this->jsonToStringArray($_val) . ')';
            }

            if (filled($_val) && !in_array($_val, ['true', 'false']) && !is_numeric($_val)) {
                $_val = "'{$_val}'";
            }

            return '->' . Arr::get($item, 'name') . '(' . $_val . ')';
        })->implode('');
    }
}