<?php

namespace warm\admin\model;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Concerns\HasTimestamps;
use ReflectionClass;
use warm\admin\support\apis\AdminBaseApi;
use warm\common\model\BaseModel;

/**
 * 管理API模型类
 * 
 * 该模型用于管理后台API接口的配置信息，包括：
 * 1. API标题和路径
 * 2. API模板类
 * 3. API参数配置
 * 4. API启用状态
 * 
 * 该模型还提供了API方法和模板标题的访问器。
 */
class AdminApi extends BaseModel
{
    use HasTimestamps;

    /**
     * 需要追加到模型数组/JSON表示中的访问器
     * 
     * @var array
     */
    protected $appends = ['template_title', 'method'];

    /**
     * 需要进行类型转换的字段
     * 
     * @var array
     */
    protected $casts = [
        'args' => 'json',
    ];

    /**
     * 支持的HTTP方法列表
     * 
     * @var array
     */
    const METHODS = ['get', 'head', 'post', 'put', 'patch', 'delete', 'options'];

    /**
     * 获取模板标题访问器
     * 
     * 通过模板类获取API的方法和标题信息，格式为"方法 - 标题"
     * 如果模板类不存在或不是AdminBaseApi的子类，则返回空字符串
     * 
     * @return Attribute 模板标题属性访问器
     */
    public function templateTitle(): Attribute
    {
        return Attribute::get(function () {
            // 检查模板类是否存在
            if (!class_exists($this->template)) return '';
            
            // 检查模板类是否继承自AdminBaseApi
            if (!(new ReflectionClass($this->template))->isSubclassOf(AdminBaseApi::class)) return '';

            // 获取API实例
            $api = appw($this->template);

            // 返回方法和标题的组合
            return $api->getMethod() . ' - ' . $api->getTitle();
        });
    }

    /**
     * 获取HTTP方法访问器
     * 
     * 通过模板类获取API的HTTP方法
     * 如果模板类不存在或不是AdminBaseApi的子类，则返回'any'
     * 如果方法不在支持的方法列表中，也返回'any'
     * 
     * @return Attribute HTTP方法属性访问器
     */
    public function method(): Attribute
    {
        return Attribute::get(function () {
            // 检查模板类是否存在
            if (!class_exists($this->template)) return '';
            
            // 检查模板类是否继承自AdminBaseApi
            if (!(new ReflectionClass($this->template))->isSubclassOf(AdminBaseApi::class)) return 'any';

            // 获取API方法
            $method = appw($this->template)->getMethod();

            // 检查方法是否在支持的方法列表中
            return in_array($method, self::METHODS) ? $method : 'any';
        });
    }
}