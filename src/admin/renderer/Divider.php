<?php
namespace warm\admin\renderer;
/**
 * Divider
 *
 * @see https://aisuda.bce.baidu.com/amis/zh-CN/components/divider
 */
class Divider extends BaseRenderer
{
    public string $type = 'divider';

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
     * 分割线的样式，支持`dashed`和`solid`
     *
     * @param string $value
     * @return self
     */
    public function lineStyle(string $value = 'solid'): static
    {
        return $this->set('lineStyle', $value);
    }

    /**
     * 分割线的方向，支持`horizontal`和`vertical`
     *
     * @param string $value
     * @return self
     */
    public function direction(string $value = 'horizontal'): static
    {
        return $this->set('direction', $value);
    }

    /**
     * 分割线的颜色
     *
     * @param string $value
     * @return self
     */
    public function color(string $value = ''): static
    {
        return $this->set('color', $value);
    }

    /**
     * 分割线的旋转角度
     *
     * @param int|float $value
     * @return self
     */
    public function rotate(int|float $value = 0): static
    {
        return $this->set('rotate', $value);
    }

    /**
     * 分割线的标题
     *
     * @param mixed $value
     * @return self
     */
    public function title(mixed $value = null): static
    {
        return $this->set('title', $value);
    }

    /**
     * 分割线的标题类名
     *
     * @param string $value
     * @return self
     */
    public function titleClassName(string $value = ''): static
    {
        return $this->set('titleClassName', $value);
    }

    /**
     * 分割线的标题位置，支持`left`、`center`和`right`
     *
     * @param string $value
     * @return self
     */
    public function titlePosition(string $value = 'center'): static
    {
        return $this->set('titlePosition', $value);
    }
}
