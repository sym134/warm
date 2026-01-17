<?php
namespace warm\admin\renderer;
/**
 * ButtonGroup
 *
 * @see https://aisuda.bce.baidu.com/amis/zh-CN/components/button-group
 */
class ButtonGroup extends BaseRenderer
{
    public string $type = 'button-group';

    /**
     * 是否使用垂直模式
     *
     * @param bool $value
     * @return self
     */
    public function vertical(bool $value = true): static
    {
        return $this->set('vertical', $value);
    }

    /**
     * 是否使用平铺模式
     *
     * @param bool $value
     * @return self
     */
    public function tiled(bool $value = true): static
    {
        return $this->set('tiled', $value);
    }

    /**
     * 'secondary' \
     *
     * @param mixed $value
     * @return self
     */
    public function btnLevel(mixed $value = null): static
    {
        return $this->set('btnLevel', $value);
    }

    /**
     * 'secondary' \
     *
     * @param mixed $value
     * @return self
     */
    public function btnActiveLevel(mixed $value = null): static
    {
        return $this->set('btnActiveLevel', $value);
    }

    /**
     * [按钮](./action)
     *
     * @param array $value
     * @return self
     */
    public function buttons(array $value = []): static
    {
        return $this->set('buttons', $value);
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
}
