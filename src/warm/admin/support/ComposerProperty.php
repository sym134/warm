<?php

namespace warm\admin\support;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Support\Arr;

/**
 * Composer属性类
 * 
 * 该类用于封装和操作composer.json文件的属性信息，提供便捷的访问和修改方法。
 * 支持通过魔术方法访问常见的Composer属性，如name、description、version等。
 * 
 * @property string $name 包名称
 * @property string $description 包描述
 * @property string $type 包类型
 * @property array $keywords 关键词数组
 * @property string $homepage 主页URL
 * @property string $license 许可证
 * @property array $authors 作者信息数组
 * @property array $require 依赖包列表
 * @property array $require_dev 开发依赖包列表
 * @property array $suggest 建议依赖包列表
 * @property array $autoload 自动加载配置
 * @property array $autoload_dev 开发自动加载配置
 * @property array $scripts 脚本配置
 * @property array $extra 额外配置
 * @property string $version 版本号
 */
class ComposerProperty implements Arrayable
{
    /**
     * 存储Composer属性的数组
     * 
     * @var array
     */
    protected array $attributes = [];

    /**
     * 构造函数
     * 
     * 初始化Composer属性对象
     * 
     * @param array $attributes Composer属性数组
     */
    public function __construct(array $attributes = [])
    {
        $this->attributes = $attributes;
    }

    /**
     * 获取指定键的属性值
     * 
     * 使用点号表示法访问嵌套数组中的值
     * 
     * @param string $key 属性键名，支持点号表示法
     * @param mixed $default 默认值，当键不存在时返回
     * @return mixed 属性值
     */
    public function get($key, $default = null): mixed
    {
        return Arr::get($this->attributes, $key, $default);
    }

    /**
     * 设置指定键的属性值
     * 
     * 使用点号表示法设置嵌套数组中的值，并返回新的对象实例
     * 
     * @param string $key 属性键名，支持点号表示法
     * @param mixed $val 属性值
     * @return static 新的ComposerProperty对象实例
     */
    public function set($key, $val): static
    {
        $new = $this->attributes;

        Arr::set($new, $key, $val);

        return new static($new);
    }

    /**
     * 删除指定键的属性
     * 
     * 使用点号表示法删除嵌套数组中的值，并返回新的对象实例
     * 
     * @param string $key 属性键名，支持点号表示法
     * @return static 新的ComposerProperty对象实例
     */
    public function delete($key): static
    {
        $new = $this->attributes;

        Arr::forget($new, $key);

        return new static($new);
    }

    /**
     * 魔术方法：获取属性值
     * 
     * 允许通过对象属性的方式访问Composer属性，如$property->name
     * 自动将下划线命名转换为连字符命名
     * 
     * @param string $name 属性名称
     * @return mixed 属性值
     */
    public function __get($name)
    {
        return $this->get(str_replace('_', '-', $name));
    }

    /**
     * 将对象转换为数组
     * 
     * @return array Composer属性数组
     */
    public function toArray(): array
    {
        return $this->attributes;
    }

    /**
     * 将对象转换为JSON字符串
     * 
     * @return bool|string JSON字符串，失败时返回false
     */
    public function toJson(): bool|string
    {
        return json_encode($this->toArray());
    }
}