<?php
namespace warm\admin\renderer;
/**
 * Each
 *
 * @see https://aisuda.bce.baidu.com/amis/zh-CN/components/each
 */
class Each extends BaseRenderer
{
    public string $type = 'each';

    /**
     * 用于循环的值
     *
     * @param array $value
     * @return self
     */
    public function value(array $value = []): static
    {
        return $this->set('value', $value);
    }

    /**
     * 获取数据域中变量
     *
     * @param string $value
     * @return self
     */
    public function name(string $value = ''): static
    {
        return $this->set('name', $value);
    }

    /**
     * 获取数据域中变量， 支持 [数据映射](../../docs/concepts/data-mapping)
     *
     * @param string $value
     * @return self
     */
    public function source(string $value = ''): static
    {
        return $this->set('source', $value);
    }

    /**
     * 使用`value`中的数据，循环输出渲染器。
     *
     * @param array $value
     * @return self
     */
    public function items(array $value = []): static
    {
        return $this->set('items', $value);
    }

    /**
     * 当 `value` 值不存在或为空数组时的占位文本
     *
     * @param string $value
     * @return self
     */
    public function placeholder(string $value = ''): static
    {
        return $this->set('placeholder', $value);
    }

    /**
     * 获取循环当前数组成员
     *
     * @param string $value
     * @return self
     */
    public function itemKeyName(string $value = 'item'): static
    {
        return $this->set('itemKeyName', $value);
    }

    /**
     * 获取循环当前索引
     *
     * @param string $value
     * @return self
     */
    public function indexKeyName(string $value = 'index'): static
    {
        return $this->set('indexKeyName', $value);
    }
}
