<?php
namespace warm\admin\renderer;
use warm\admin\renderer\trait\OnEvent;

/**
 * DropdownButton
 *
 * @see https://aisuda.bce.baidu.com/amis/zh-CN/components/dropdown-button
 */
class DropdownButton extends BaseRenderer
{
    use OnEvent;

    public string $type = 'dropdown-button';

    public function level(string $value = 'link'): static
    {
        return $this->set('level', $value);
    }

    /**
     * 按钮文本
     *
     * @param mixed $value
     * @return self
     */
    public function label(mixed $value = ''): static
    {
        return $this->set('label', $value);
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
     * 按钮 CSS 类名
     *
     * @param string $value
     * @return self
     */
    public function btnClassName(string $value = ''): static
    {
        return $this->set('btnClassName', $value);
    }

    /**
     * 下拉菜单 CSS 类名
     *
     * @param string $value
     * @return self
     */
    public function menuClassName(string $value = ''): static
    {
        return $this->set('menuClassName', $value);
    }

    /**
     * 块状样式
     *
     * @param bool $value
     * @return self
     */
    public function block(bool $value = true): static
    {
        return $this->set('block', $value);
    }

    /**
     * 尺寸，支持`'xs'`、`'sm'`、`'md'` 、`'lg'`
     *
     * @param string $value
     * @return self
     */
    public function size(string $value = ''): static
    {
        return $this->set('size', $value);
    }

    /**
     * 位置，可选`'left'`或`'right'`
     *
     * @param string $value
     * @return self
     */
    public function align(string $value = ''): static
    {
        return $this->set('align', $value);
    }

    /**
     * 配置下拉按钮
     *
     * @param array $value
     * @return self
     */
    public function buttons(array $value = []): static
    {
        return $this->set('buttons', $value);
    }

    /**
     * 只显示 icon
     *
     * @param bool $value
     * @return self
     */
    public function iconOnly(bool $value = true): static
    {
        return $this->set('iconOnly', $value);
    }

    /**
     * 默认是否打开
     *
     * @param bool $value
     * @return self
     */
    public function defaultIsOpened(bool $value = true): static
    {
        return $this->set('defaultIsOpened', $value);
    }

    /**
     * 点击外侧区域是否收起
     *
     * @param bool $value
     * @return self
     */
    public function closeOnOutside(bool $value = true): static
    {
        return $this->set('closeOnOutside', $value);
    }

    /**
     * 点击按钮后自动关闭下拉菜单
     *
     * @param bool $value
     * @return self
     */
    public function closeOnClick(bool $value = true): static
    {
        return $this->set('closeOnClick', $value);
    }

    /**
     * 触发方式
     *
     * @param mixed $value
     * @return self
     */
    public function trigger(mixed $value = null): static
    {
        return $this->set('trigger', $value);
    }

    /**
     * 隐藏下拉图标
     *
     * @param bool $value
     * @return self
     */
    public function hideCaret(bool $value = true): static
    {
        return $this->set('hideCaret', $value);
    }

    /**
     * 弹出的下拉按钮放在哪个节点下
     *
     * @param string $value
     * @return self
     */
    public function popOverContainerSelector(string $value = ''): static
    {
        return $this->set('popOverContainerSelector', $value);
    }
}
