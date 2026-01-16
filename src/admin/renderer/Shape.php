<?php
namespace warm\admin\renderer;
/**
 * Shape
 *
 * @see https://aisuda.bce.baidu.com/amis/zh-CN/components/shape
 */
class Shape extends BaseRenderer
{
    public string $type = 'shape';

    /**
     * 图形类型
     *
     * @param mixed $value
     * @return self
     */
    public function shapeType(mixed $value = null): self
    {
        return $this->set('shapeType', $value);
    }

    /**
     * 自定义 CSS 样式类名
     *
     * @param string $value
     * @return self
     */
    public function className(string $value = ''): self
    {
        return $this->set('className', $value);
    }

    /**
     * 填充颜色
     *
     * @param string $value
     * @return self
     */
    public function color(string $value = ''): self
    {
        return $this->set('color', $value);
    }

    /**
     * 图形宽度
     *
     * @param int|float $value
     * @return self
     */
    public function width(int|float $value = 200): self
    {
        return $this->set('width', $value);
    }

    /**
     * 图形大小
     *
     * @param int|float $value
     * @return self
     */
    public function height(int|float $value = 200): self
    {
        return $this->set('height', $value);
    }

    /**
     * 圆角大小,负数表示内弧
     *
     * @param int|float $value
     * @return self
     */
    public function radius(int|float $value = 0): self
    {
        return $this->set('radius', $value);
    }
}
