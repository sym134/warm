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
     * 外层 Dom 的类名
     *
     * @param string $value
     * @return self
     */
    public function className(string $value = ''): self
    {
        return $this->set('className', $value);
    }

    /**
     * 分割线的样式，支持`dashed`和`solid`
     *
     * @param string $value
     * @return self
     */
    public function lineStyle(string $value = 'solid'): self
    {
        return $this->set('lineStyle', $value);
    }

    /**
     * 分割线的方向，支持`horizontal`和`vertical`
     *
     * @param string $value
     * @return self
     */
    public function direction(string $value = 'horizontal'): self
    {
        return $this->set('direction', $value);
    }

    /**
     * 分割线的颜色
     *
     * @param string $value
     * @return self
     */
    public function color(string $value = ''): self
    {
        return $this->set('color', $value);
    }

    /**
     * 分割线的旋转角度
     *
     * @param int|float $value
     * @return self
     */
    public function rotate(int|float $value = 0): self
    {
        return $this->set('rotate', $value);
    }

    /**
     * 分割线的标题
     *
     * @param mixed $value
     * @return self
     */
    public function title(mixed $value = null): self
    {
        return $this->set('title', $value);
    }

    /**
     * 分割线的标题类名
     *
     * @param string $value
     * @return self
     */
    public function titleClassName(string $value = ''): self
    {
        return $this->set('titleClassName', $value);
    }

    /**
     * 分割线的标题位置，支持`left`、`center`和`right`
     *
     * @param string $value
     * @return self
     */
    public function titlePosition(string $value = 'center'): self
    {
        return $this->set('titlePosition', $value);
    }
}
