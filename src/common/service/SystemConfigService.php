<?php

namespace warm\common\service;

use ArrayAccess;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Arr;
use support\Db as DB;
use warm\common\model\SystemConfig;

/**
 * 通用配置服务类
 * 
 * 提供配置项管理的通用功能，包括设置、获取、删除配置项等操作
 * 支持单个和批量配置操作，以及缓存管理
 */
class SystemConfigService
{
    /**
     * 模型名称
     * 
     * @var string
     */
    protected static string $modelName = SystemConfig::class;

    /**
     * 缓存键前缀
     * 
     * @var string
     */
    protected static string $cacheKeyPrefix = 'system_config_';

    /**
     * 获取模型查询构造器
     *
     * @return Builder 模型查询构造器
     */
    protected static function query(): Builder
    {
        return static::$modelName::query();
    }

    /**
     * 保存设置
     *
     * 保存单个配置项，如果配置项不存在则创建，存在则更新
     *
     * @param string $key 配置项键名
     * @param mixed|null $value 配置项值
     * @return bool 保存是否成功
     */
    public static function set(string $key, mixed $value = null): bool
    {
        $setting = static::query()->firstOrNew(['key' => $key]);

        $setting->values = $value;
        static::clearCache($key);
        return $setting->save();
    }

    /**
     * 批量保存设置
     *
     * 批量保存多个配置项，使用数据库事务确保数据一致性
     *
     * @param array $data 配置项键值对数组
     * @return bool 保存是否成功
     */
    public static function setMany(array $data): bool
    {
        DB::beginTransaction();
        try {
            foreach ($data as $key => $value) {
                if (!static::set($key, $value)) {
                    throw new Exception('保存配置项失败: ' . $key);
                }
            }

            DB::commit();
            return true;
        } catch (Exception $e) {
            DB::rollBack();
            return false;
        }
    }

    /**
     * 以数组形式返回所有设置
     *
     * 获取所有配置项，以键值对数组形式返回
     *
     * @return array 所有配置项
     */
    public static function all(): array
    {
        return static::query()->pluck('values', 'key')->toArray();
    }

    /**
     * 获取设置项
     *
     * 获取指定键名的配置项值，支持默认值和强制刷新选项
     *
     * @param string $key 设置项key
     * @param mixed|null $default 默认值
     * @param bool $fresh 是否直接从数据库获取
     * @return mixed|null 配置项值
     */
    public static function get(string $key, mixed $default = null, bool $fresh = false): mixed
    {
        if ($fresh) {
            return static::query()->where('key', $key)->value('values') ?? $default;
        }

        $value = cache()->rememberForever(static::getCacheKey($key), function () use ($key) {
            return static::query()->where('key', $key)->value('values');
        });

        return $value ?? $default;
    }

    /**
     * 获取设置项中的某个值
     *
     * 通过点号分隔的路径获取配置项中的嵌套值
     *
     * @param string $key 设置项key
     * @param string $path 通过点号分隔的路径, 同Arr::get()
     * @param mixed|null $default 默认值
     * @return array|ArrayAccess|mixed|null 配置项中的值
     */
    public static function arrayGet(string $key, string $path, mixed $default = null): mixed
    {
        $value = static::get($key);

        if (is_array($value)) {
            return Arr::get($value, $path, $default);
        }

        return $default;
    }

    /**
     * 清除指定设置项
     *
     * 删除指定键名的配置项
     *
     * @param string $key 配置项键名
     * @return bool 删除是否成功
     */
    public static function del(string $key): bool
    {
        if (static::query()->where('key', $key)->delete()) {
            static::clearCache($key);
            return true;
        }

        return false;
    }

    /**
     * 清除指定设置项的缓存
     *
     * 删除指定配置项的缓存
     *
     * @param string $key 配置项键名
     * @return void
     */
    public static function clearCache(string $key): void
    {
        cache()->forget(static::getCacheKey($key));
    }

    /**
     * 获取缓存键名
     *
     * 生成配置项的缓存键名
     *
     * @param string $key 配置项键名
     * @return string 缓存键名
     */
    public static function getCacheKey(string $key): string
    {
        return static::$cacheKeyPrefix . $key;
    }
}