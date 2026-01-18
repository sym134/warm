<?php
namespace warm\admin\renderer;
use warm\admin\renderer\trait\OnEvent;

/**
 * Shape 形状
 *
 * @see https://aisuda.bce.baidu.com/amis/zh-CN/components/shape
 */
class Shape extends BaseRenderer
{
    use OnEvent;

    public string $type = 'shape';

    /**
     * 图形类型
     *
     * @param mixed $value
     * @return self
     */
    public function shapeType(mixed $value = null): static
    {
        return $this->set('shapeType', $value);
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
     * 填充颜色
     *
     * @param string $value
     * @return self
     */
    public function color(string $value = ''): static
    {
        return $this->set('color', $value);
    }

    /**
     * 图形宽度
     *
     * @param int|float $value
     * @return self
     */
    public function width(int|float $value = 200): static
    {
        return $this->set('width', $value);
    }

    /**
     * 图形大小
     *
     * @param int|float $value
     * @return self
     */
    public function height(int|float $value = 200): static
    {
        return $this->set('height', $value);
    }

    /**
     * 圆角大小,负数表示内弧
     *
     * @param int|float $value
     * @return self
     */
    public function radius(int|float $value = 0): static
    {
        return $this->set('radius', $value);
    }
}
