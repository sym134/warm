<?php
namespace warm\admin\renderer;
/**
 * Color 颜色
 *
 * @see https://aisuda.bce.baidu.com/amis/zh-CN/components/color
 */
class Color extends BaseRenderer
{
    public string $type = 'color';

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
     * 显示的颜色值
     *
     * @param string $value
     * @return self
     */
    public function value(string $value = ''): static
    {
        return $this->set('value', $value);
    }

    /**
     * 在其他组件中，时，用作变量映射
     *
     * @param string $value
     * @return self
     */
    public function name(string $value = ''): static
    {
        return $this->set('name', $value);
    }

    /**
     * 默认颜色值
     *
     * @param string $value
     * @return self
     */
    public function defaultColor(string $value = ''): static
    {
        return $this->set('defaultColor', $value);
    }

    /**
     * 是否显示右边的颜色值
     *
     * @param bool $value
     * @return self
     */
    public function showValue(bool $value = true): static
    {
        return $this->set('showValue', $value);
    }

    /**
     * 弹层挂载位置选择器，会通过`querySelector`获取
     *
     * @param string $value
     * @return self
     */
    public function popOverContainerSelector(string $value = ''): static
    {
        return $this->set('popOverContainerSelector', $value);
    }
}
