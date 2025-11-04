<?php

namespace warm\admin\service\system;

use ArrayAccess;
use Exception;
use Illuminate\Support\Arr;
use support\Response;
use warm\admin\Admin;
use warm\admin\service\AdminService;
use warm\common\service\SystemConfigService as ConfigService;

/**
 * 配置服务类
 * 
 * 提供系统配置项的管理功能，包括设置、获取、删除配置项等操作
 * 支持单个和批量配置操作，以及缓存管理
 */
class SystemConfigService extends AdminService
{
    /**
     * 配置服务实例
     *
     */
    protected ConfigService $configService;

    /**
     * 构造函数
     */
    public function __construct()
    {
        parent::__construct();
        $this->configService = new ConfigService();
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
    public function set(string $key, mixed $value = null): bool
    {
        try {
            return $this->configService->set($key, $value);
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
        try {
            return $this->configService->setMany($data);
        } catch (Exception $e) {
            amis_abort($e->getMessage());
        }

        return false;
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
        return $this->configService->all();
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
            return $this->configService->get($key, $default, true);
        }

        return $this->configService->get($key, $default);
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
    public function arrayGet(string $key, string $path, mixed $default = null): mixed
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
        return $this->configService->del($key);
    }

    /**
     * 清除指定设置项的缓存
     *
     * 删除指定配置项的缓存
     *
     * @param string $key 配置项键名
     * @return void
     */
    public function clearCache(string $key): void
    {
        $this->configService->clearCache($key);
    }
}