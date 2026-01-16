<?php
namespace warm\admin\renderer;
/**
 * DropdownButton
 *
 * @see https://aisuda.bce.baidu.com/amis/zh-CN/components/dropdown-button
 */
class DropdownButton extends BaseRenderer
{
    public string $type = 'dropdown-button';

    /**
     * 按钮文本
     *
     * @param string $value
     * @return self
     */
    public function label(string $value = ''): self
    {
        return $this->set('label', $value);
    }

    /**
     * 外层 CSS 类名
     *
     * @param string $value
     * @return self
     */
    public function className(string $value = ''): self
    {
        return $this->set('className', $value);
    }

    /**
     * 按钮 CSS 类名
     *
     * @param string $value
     * @return self
     */
    public function btnClassName(string $value = ''): self
    {
        return $this->set('btnClassName', $value);
    }

    /**
     * 下拉菜单 CSS 类名
     *
     * @param string $value
     * @return self
     */
    public function menuClassName(string $value = ''): self
    {
        return $this->set('menuClassName', $value);
    }

    /**
     * 块状样式
     *
     * @param bool $value
     * @return self
     */
    public function block(bool $value = true): self
    {
        return $this->set('block', $value);
    }

    /**
     * 尺寸，支持`'xs'`、`'sm'`、`'md'` 、`'lg'`
     *
     * @param string $value
     * @return self
     */
    public function size(string $value = ''): self
    {
        return $this->set('size', $value);
    }

    /**
     * 位置，可选`'left'`或`'right'`
     *
     * @param string $value
     * @return self
     */
    public function align(string $value = ''): self
    {
        return $this->set('align', $value);
    }

    /**
     * 配置下拉按钮
     *
     * @param array $value
     * @return self
     */
    public function buttons(array $value = []): self
    {
        return $this->set('buttons', $value);
    }

    /**
     * 只显示 icon
     *
     * @param bool $value
     * @return self
     */
    public function iconOnly(bool $value = true): self
    {
        return $this->set('iconOnly', $value);
    }

    /**
     * 默认是否打开
     *
     * @param bool $value
     * @return self
     */
    public function defaultIsOpened(bool $value = true): self
    {
        return $this->set('defaultIsOpened', $value);
    }

    /**
     * 点击外侧区域是否收起
     *
     * @param bool $value
     * @return self
     */
    public function closeOnOutside(bool $value = true): self
    {
        return $this->set('closeOnOutside', $value);
    }

    /**
     * 点击按钮后自动关闭下拉菜单
     *
     * @param bool $value
     * @return self
     */
    public function closeOnClick(bool $value = true): self
    {
        return $this->set('closeOnClick', $value);
    }

    /**
     * 触发方式
     *
     * @param mixed $value
     * @return self
     */
    public function trigger(mixed $value = null): self
    {
        return $this->set('trigger', $value);
    }

    /**
     * 隐藏下拉图标
     *
     * @param bool $value
     * @return self
     */
    public function hideCaret(bool $value = true): self
    {
        return $this->set('hideCaret', $value);
    }

    /**
     * 弹出的下拉按钮放在哪个节点下
     *
     * @param string $value
     * @return self
     */
    public function popOverContainerSelector(string $value = ''): self
    {
        return $this->set('popOverContainerSelector', $value);
    }
}
