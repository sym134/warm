<?php
namespace warm\admin\renderer;
/**
 * Custom
 *
 * @see https://aisuda.bce.baidu.com/amis/zh-CN/components/custom
 */
class Custom extends BaseRenderer
{
    public string $type = 'custom';

    /**
     * 节点 id
     *
     * @param string $value
     * @return self
     */
    public function id(string $value = ''): self
    {
        return $this->set('id', $value);
    }

    /**
     * 节点 名称
     *
     * @param string $value
     * @return self
     */
    public function name(string $value = ''): self
    {
        return $this->set('name', $value);
    }

    /**
     * 节点 class
     *
     * @param string $value
     * @return self
     */
    public function className(string $value = ''): self
    {
        return $this->set('className', $value);
    }

    /**
     * 默认使用 div 标签，如果 true 就使用 span 标签
     *
     * @param bool $value
     * @return self
     */
    public function inline(bool $value = true): self
    {
        return $this->set('inline', $value);
    }

    /**
     * 初始化节点 html
     *
     * @param string $value
     * @return self
     */
    public function html(string $value = ''): self
    {
        return $this->set('html', $value);
    }

    /**
     * 节点初始化之后调的用函数
     *
     * @param string $value
     * @return self
     */
    public function onMount(string $value = 'Function'): self
    {
        return $this->set('onMount', $value);
    }

    /**
     * 数据有更新的时候调用的函数
     *
     * @param string $value
     * @return self
     */
    public function onUpdate(string $value = 'Function'): self
    {
        return $this->set('onUpdate', $value);
    }

    /**
     * 节点销毁的时候调用的函数
     *
     * @param string $value
     * @return self
     */
    public function onUnmount(string $value = 'Function'): self
    {
        return $this->set('onUnmount', $value);
    }
}
