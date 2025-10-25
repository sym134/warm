<?php

namespace warm\common\service;

use ArrayAccess;
use Exception;
use Illuminate\Support\Arr;
use support\Cache;
use support\Db as DB;
use support\Response;
use warm\admin\Admin;
use warm\admin\service\AdminService;
use warm\common\model\SystemConfig;

/**
 * 配置服务类
 * 
 * 提供系统配置项的管理功能，包括设置、获取、删除配置项等操作
 * 支持单个和批量配置操作，以及缓存管理
 */
class ConfigService extends AdminService
{
    /**
     * 模型名称
     * 
     * @var string
     */
    protected string $modelName = SystemConfig::class;

    /**
     * 缓存键前缀
     * 
     * @var string
     */
    protected string $cacheKeyPrefix = 'app_config_';

    /**
     * 保存设置
     *
     * 保存单个配置项，如果配置项不存在则创建，存在则更新
     *
     * @param string $key 配置项键名
     * @param mixed $value 配置项值
     * @return bool 保存是否成功
     */
    public function set($key, $value = null): bool
    {
        try {
            $setting = $this->query()->firstOrNew(['key' => $key]);

            $setting->values = $value;
            $this->clearCache($key);
            return $setting->save();
        } catch (Exception $e) {
            amis_abort($e->getMessage());
            return false;
        }
    }

    /**
     * 批量保存设置
     *
     * 批量保存多个配置项，使用数据库事务确保数据一致性
     *
     * @param array $data 配置项键值对数组
     * @return bool 保存是否成功
     */
    public function setMany(array $data): bool
    {
        DB::beginTransaction();
        try {
            foreach ($data as $key => $value) {
                if (!$this->set($key, $value)) {
                    throw new Exception($this->getError());
                }
            }

            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();

            amis_abort($e->getMessage());
        }

        return true;
    }

    /**
     * 批量保存设置项并返回后台响应格式数据
     *
     * 批量保存配置项并返回标准的后台响应格式
     *
     * @param array $data 配置项键值对数组
     * @return Response 响应对象
     */
    public function adminSetMany(array $data): Response
    {
        $prefix = translator('admin.save');

        if ($this->setMany($data)) {
            return Admin::response()->successMessage($prefix . translator('admin.successfully'));
        }

        return Admin::response()->fail($prefix . translator('admin.failed'), $this->getError());
    }

    /**
     * 以数组形式返回所有设置
     *
     * 获取所有配置项，以键值对数组形式返回
     *
     * @return array 所有配置项
     */
    public function all(): array
    {
        return $this->query()->pluck('values', 'key')->toArray();
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
    public function get(string $key, mixed $default = null, bool $fresh = false): mixed
    {
        if ($fresh) {
            return $this->query()->where('key', $key)->value('values') ?? $default;
        }

        $value = cache()->rememberForever($this->getCacheKey($key), function () use ($key) {
            return $this->query()->where('key', $key)->value('values');
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
     * @param mixed $default 默认值
     * @return array|ArrayAccess|mixed|null 配置项中的值
     */
    public function arrayGet(string $key, string $path, $default = null): mixed
    {
        $value = $this->get($key);

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
    public function del(string $key): bool
    {
        if ($this->query()->where('key', $key)->delete()) {
            $this->clearCache($key);

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
    public function clearCache($key): void
    {
        Cache::delete($this->getCacheKey($key));
    }

    /**
     * 获取缓存键名
     *
     * 生成配置项的缓存键名
     *
     * @param string $key 配置项键名
     * @return string 缓存键名
     */
    public function getCacheKey($key): string
    {
        return $this->cacheKeyPrefix . $key;
    }
}