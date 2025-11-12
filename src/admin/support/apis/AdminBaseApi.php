<?php

namespace warm\admin\support\apis;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\HigherOrderBuilderProxy;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use warm\admin\model\AdminApi;
use warm\admin\service\AdminApiService;
use warm\admin\service\AdminService;

/**
 * 管理后台API基础类
 * 
 * 提供所有管理API的通用功能和属性，作为具体API实现的基类。
 * 包含API记录管理、参数获取、服务实例创建等通用方法。
 * 
 * @package warm\admin\support\apis
 */
abstract class AdminBaseApi implements AdminApiInterface
{
    /** @var string 接口名称 */
    public string $title = '';

    /** @var string 请求方法类型 (get, post, put, delete, any) */
    public string $method = 'any';

    /** @var Model|Builder|AdminApi|null 当前API记录实例 */
    public static Model|Builder|AdminApi|null $apiRecord;

    /**
     * 获取接口名称
     *
     * 如果$title属性未设置，则使用类名作为标题
     * 
     * @return string 接口名称
     */
    public function getTitle(): string
    {
        return $this->title ?: Str::of(static::class)->explode('\\')->pop();
    }

    /**
     * 获取请求方法类型
     * 
     * @return string 请求方法类型
     */
    public function getMethod(): string
    {
        return $this->method;
    }

    /**
     * 获取当前API记录实例
     * 
     * 如果尚未初始化，则从AdminApiService获取对应模板的API记录
     * 
     * @return Model|Builder|AdminApi|null API记录实例
     */
    public function getApiRecord(): Model|AdminApi|Builder|null
    {
        if (!self::$apiRecord) {
            self::$apiRecord = AdminApiService::make()->getApiByTemplate(static::class);
        }

        return self::$apiRecord;
    }

    /**
     * 设置API记录实例
     * 
     * @param mixed $apiRecord API记录实例
     * @return static 返回当前实例以支持链式调用
     */
    public function setApiRecord($apiRecord): static
    {
        self::$apiRecord = $apiRecord;
        return $this;
    }

    /**
     * 获取接口参数, 可以通过传入 xxx.xxx 的方式获取指定参数
     *
     * @param null $key 参数键名，支持点号分隔的嵌套访问
     * @param null $default 默认值，当指定键不存在时返回
     *
     * @return array|HigherOrderBuilderProxy|mixed 参数值
     */
    public function getArgs($key = null, $default = null): mixed
    {
        $args = $this->getApiRecord()->args;

        if ($key) {
            return data_get($args, $key, $default);
        }

        return $args;
    }

    /**
     * 获取空白的 AdminService 实例
     *
     * 创建一个空的AdminService实例，用于执行各种管理操作
     * 
     * @return AdminService 空白的AdminService实例
     */
    public function blankService(): AdminService
    {
        return new class extends AdminService {
        };
    }
}