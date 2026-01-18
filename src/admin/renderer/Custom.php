<?php
namespace warm\admin\renderer;
/**
 * Custom 自定义组件
 *
 * @see https://aisuda.bce.baidu.com/amis/zh-CN/components/custom
 */
class Custom extends BaseRenderer
{
    public string $type = 'custom';

    /**
     * 节点 id
     *
     * @param string|int $value
     * @return self
     */
    public function id(string|int $value = ''): static
    {
        return $this->set('id', $value);
    }

    /**
     * 节点 名称
     *
     * @param string $value
     * @return self
     */
    public function name(string $value = ''): static
    {
        return $this->set('name', $value);
    }

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
     * 默认使用 div 标签，如果 true 就使用 span 标签
     *
     * @param bool $value
     * @return self
     */
    public function inline(bool $value = true): static
    {
        return $this->set('inline', $value);
    }

    /**
     * 初始化节点 html
     *
     * @param string $value
     * @return self
     */
    public function html(string $value = ''): static
    {
        return $this->set('html', $value);
    }

    /**
     * 节点初始化之后调的用函数
     *
     * @param string $value
     * @return self
     */
    public function onMount(string $value = 'Function'): static
    {
        return $this->set('onMount', $value);
    }

    /**
     * 数据有更新的时候调用的函数
     *
     * @param string $value
     * @return self
     */
    public function onUpdate(string $value = 'Function'): static
    {
        return $this->set('onUpdate', $value);
    }

    /**
     * 节点销毁的时候调用的函数
     *
     * @param string $value
     * @return self
     */
    public function onUnmount(string $value = 'Function'): static
    {
        return $this->set('onUnmount', $value);
    }
}
