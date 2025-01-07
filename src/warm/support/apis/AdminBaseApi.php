<?php

namespace warm\support\apis;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\HigherOrderBuilderProxy;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use warm\model\AdminApi;
use warm\service\AdminApiService;
use warm\service\AdminService;

abstract class AdminBaseApi implements AdminApiInterface
{
    /** @var string 接口名称 */
    public string $title = '';

    public string $method = 'any';

    public static Model|Builder|AdminApi|null $apiRecord;

    /**
     * 获取接口名称
     *
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title ?: Str::of(static::class)->explode('\\')->pop();
    }

    public function getMethod(): string
    {
        return $this->method;
    }

    public function getApiRecord(): Model|AdminApi|Builder|null
    {
        if (!self::$apiRecord) {
            self::$apiRecord = AdminApiService::make()->getApiByTemplate(static::class);
        }

        return self::$apiRecord;
    }

    public function setApiRecord($apiRecord): static
    {
        self::$apiRecord = $apiRecord;
        return $this;
    }

    /**
     * 获取接口参数, 可以通过传入 xxx.xxx 的方式获取指定参数
     *
     * @param null $key
     * @param null $default
     *
     * @return array|HigherOrderBuilderProxy|mixed
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
     * @return AdminService
     */
    public function blankService(): AdminService
    {
        return new class extends AdminService {
        };
    }
}
