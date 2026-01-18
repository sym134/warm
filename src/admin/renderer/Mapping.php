<?php
namespace warm\admin\renderer;
use warm\admin\renderer\trait\NameAndLabel;

/**
 * Mapping 映射
 *
 * @see https://aisuda.bce.baidu.com/amis/zh-CN/components/mapping
 */
class Mapping extends BaseRenderer
{
    use NameAndLabel;

    public string $type = 'mapping';

    /**
     * 设置组件样式
     *
     * @param mixed $value
     * @return $this
     */
    public function className(mixed $value): static
    {
        return $this->set('className', $value);
    }

    /**
     * 占位文本
     *
     * @param string $value
     * @return self
     */
    public function placeholder(string $value = ''): static
    {
        return $this->set('placeholder', $value);
    }

    /**
     * 映射配置
     *
     * @param array $value
     * @return self
     */
    public function map(array $value = []): static
    {
        return $this->set('map', $value);
    }

    /**
     * [API](../../../docs/types/api) 或 [数据映射](../../../docs/concepts/data-mapping)
     *
     * @param mixed $value
     * @return self
     */
    public function source(mixed $value = null): static
    {
        return $this->set('source', $value);
    }

    /**
     * `2.5.2` map 或 source 为`Array<object>`时，用来匹配映射的字段名
     *
     * @param string $value
     * @return self
     */
    public function valueField(string $value = 'value'): static
    {
        return $this->set('valueField', $value);
    }

    /**
     * `2.5.2` map 或 source 为`Array<object>`时，用来展示的字段名<br />注：配置后映射值无法作为`schema`组件渲染
     *
     * @param string $value
     * @return self
     */
    public function labelField(string $value = 'label'): static
    {
        return $this->set('labelField', $value);
    }

    /**
     * `2.5.2` 自定义渲染模板，支持`html`或`schemaNode`；<br /> 当映射值是`非object`时，可使用`${item}`获取映射值；<br />当映射值是`object`时，可使用映射语法: `${xxx}`获取`object`的值；<br /> 也可使用数据映射语法：`${xxx}`获取数据域中变量值。
     *
     * @param mixed $value
     * @return self
     */
    public function itemSchema(mixed $value = null): static
    {
        return $this->set('itemSchema', $value);
    }
}
