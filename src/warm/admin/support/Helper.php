<?php

namespace warm\admin\support;

use app\process\Monitor;
use Closure;
use Symfony\Component\Process\Process;
use Illuminate\Contracts\Support\Jsonable;
use Illuminate\Contracts\Support\Arrayable;
use Throwable;
use Workerman\Timer;
use Workerman\Worker;

/**
 * 助手工具类
 * 
 * 该类提供各种常用的辅助方法，包括：
 * 1. 数据类型转换（数组转换等）
 * 2. 字符串处理（slug转换等）
 * 3. 进程处理
 * 4. 值比较
 * 5. 文件路径处理
 * 
 * 主要用于简化常见操作，提高代码复用性。
 */
class Helper
{
    /**
     * 把给定的值转化为数组
     * 
     * 支持多种类型的输入转换为数组：
     * 1. null、空字符串、空数组返回空数组
     * 2. 闭包执行后转换
     * 3. Jsonable对象转换为数组
     * 4. Arrayable对象转换为数组
     * 5. 字符串尝试JSON解析，失败则按逗号分割
     * 6. 其他类型强制转换为数组
     * 
     * @param mixed $value 需要转换的值
     * @param bool $filter 是否过滤空值，默认为true
     * @return array 转换后的数组
     */
    public static function array(mixed $value, bool $filter = true): array
    {
        // 处理空值情况
        if ($value === null || $value === '' || $value === []) {
            return [];
        }

        // 如果是闭包，则执行获取结果
        if ($value instanceof Closure) {
            $value = $value();
        }

        // 根据不同类型进行转换
        if (is_array($value)) {
            // 已经是数组，无需转换
        } else if ($value instanceof Jsonable) {
            // Jsonable对象转换为数组
            $value = json_decode($value->toJson(), true);
        } else if ($value instanceof Arrayable) {
            // Arrayable对象转换为数组
            $value = $value->toArray();
        } else if (is_string($value)) {
            // 字符串处理
            $array = null;

            try {
                // 尝试JSON解析
                $array = json_decode($value, true);
            } catch (Throwable $e) {
                // 解析失败则忽略异常
            }

            // 如果JSON解析成功则使用解析结果，否则按逗号分割
            $value = is_array($array) ? $array : explode(',', $value);
        } else {
            // 其他类型强制转换为数组
            $value = (array)$value;
        }

        // 根据参数决定是否过滤空值
        return $filter ? array_filter($value, function ($v) {
            return $v !== '' && $v !== null;
        }) : $value;
    }

    /**
     * 将驼峰命名转换为slug格式
     * 
     * 将大驼峰或小驼峰命名转换为小写连字符分隔格式
     * 例如：UserName -> user-name
     * 
     * @param string $name 需要转换的名称
     * @param string $symbol 分隔符，默认为连字符
     * @return array|string 转换后的slug格式字符串
     */
    public static function slug(string $name, string $symbol = '-'): array|string
    {
        // 使用正则表达式回调函数处理大写字母
        $text = preg_replace_callback('/([A-Z])/', function ($text) use ($symbol) {
            return $symbol . strtolower($text[1]);
        }, $name);

        // 替换下划线并去除开头的分隔符
        return str_replace('_', $symbol, ltrim($text, $symbol));
    }

    /**
     * 创建进程对象
     * 
     * 根据命令创建Symfony Process对象，用于执行系统命令
     * 
     * @param mixed $command 命令字符串或命令数组
     * @param int $timeout 超时时间（秒），默认100秒
     * @param null $input 输入数据
     * @param null $cwd 工作目录
     * @return Process 进程对象
     */
    public static function process(mixed $command, int $timeout = 100, $input = null, $cwd = null): Process
    {
        // 构造参数数组
        $parameters = [
            $command,
            $cwd,
            [],
            $input,
            $timeout,
        ];

        // 根据命令类型创建相应的进程对象
        return is_string($command)
            ? Process::fromShellCommandline(...$parameters)
            : new Process(...$parameters);
    }

    /**
     * 判断两个值是否相等
     * 
     * 比较两个值是否相等，处理了不同类型之间的比较：
     * 1. null值直接返回false
     * 2. 非标量类型使用严格比较
     * 3. 标量类型转换为字符串后比较
     * 
     * @param mixed $value1 第一个值
     * @param mixed $value2 第二个值
     * @return bool 是否相等
     */
    public static function equal(mixed $value1, mixed $value2): bool
    {
        // 如果任一值为null，则不相等
        if ($value1 === null || $value2 === null) {
            return false;
        }

        // 如果任一值不是标量类型，则使用严格比较
        if (!is_scalar($value1) || !is_scalar($value2)) {
            return $value1 === $value2;
        }

        // 标量类型转换为字符串后比较
        return (string)$value1 === (string)$value2;
    }

    /**
     * 获取文件名称
     * 
     * 从完整路径中提取文件名部分
     * 
     * @param string $name 完整路径
     * @return mixed 文件名
     */
    public static function basename(string $name): mixed
    {
        // 如果名称为空，直接返回
        if (!$name) {
            return $name;
        }

        // 按'/'分割路径并返回最后一个部分
        return last(explode('/', $name));
    }

    /**
     * 重新加载webman服务
     * 
     * 通过发送信号或定时器方式重新加载webman服务，
     * 使新的配置或代码生效而无需完全重启服务
     * 
     * @return bool 是否成功发送重新加载信号
     */
    public static function reloadWebman(): bool
    {
        if (function_exists('posix_kill')) {
            try {
                posix_kill(posix_getppid(), SIGUSR1);
                return true;
            } catch (Throwable $e) {}
        } else {
            Timer::add(1, function () {
                Worker::stopAll();
            });
        }
        return false;
    }

    /**
     * 暂停文件监控
     * 
     * 暂停对文件变化的监控，通常在执行某些操作时避免不必要的文件监听触发
     * 
     * @return void
     */
    public static function pauseFileMonitor(): void
    {
        if (method_exists(Monitor::class, 'pause')) {
            Monitor::pause();
        }
    }

    /**
     * 恢复文件监控
     * 
     * 恢复对文件变化的监控，在暂停操作完成后重新启用文件监听功能
     * 
     * @return void
     */
    public static function resumeFileMonitor(): void
    {
        if (method_exists(Monitor::class, 'resume')) {
            Monitor::resume();
        }
    }
}