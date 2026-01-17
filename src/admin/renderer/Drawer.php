<?php
namespace warm\admin\renderer;
use warm\admin\renderer\trait\DataDomain;

/**
 * Drawer
 *
 * @see https://aisuda.bce.baidu.com/amis/zh-CN/components/drawer
 */
class Drawer extends BaseRenderer
{
    use DataDomain;

    public string $type = 'drawer';

    /**
     * 弹出层标题
     *
     * @param mixed $value
     * @return self
     */
    public function title(mixed $value = null): static
    {
        return $this->set('title', $value);
    }

    /**
     * 往 Drawer 内容区加内容
     *
     * @param mixed $value
     * @return self
     */
    public function body(mixed $value = null): static
    {
        return $this->set('body', $value);
    }

    /**
     * 指定 Drawer 大小，支持: `xs`、`sm`、`md`、`lg`、`xl`
     *
     * @param string $value
     * @return self
     */
    public function size(string $value = ''): static
    {
        return $this->set('size', $value);
    }

    /**
     * 指定 Drawer 方向，支持: `left`、`right`、`top`、`bottom`
     *
     * @param string $value
     * @return self
     */
    public function position(string $value = 'right'): static
    {
        return $this->set('position', $value);
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
     * Drawer 头部 区域的样式类名
     *
     * @param string $value
     * @return self
     */
    public function headerClassName(string $value = ''): static
    {
        return $this->set('headerClassName', $value);
    }

    /**
     * Drawer body 区域的样式类名
     *
     * @param string $value
     * @return self
     */
    public function bodyClassName(string $value = 'modal-body'): static
    {
        return $this->set('bodyClassName', $value);
    }

    /**
     * Drawer 页脚 区域的样式类名
     *
     * @param string $value
     * @return self
     */
    public function footerClassName(string $value = ''): static
    {
        return $this->set('footerClassName', $value);
    }

    /**
     * 是否展示关闭按钮，当值为 `false` 时，默认开启 closeOnOutside
     *
     * @param bool $value
     * @return self
     */
    public function showCloseButton(bool $value = true): static
    {
        return $this->set('showCloseButton', $value);
    }

    /**
     * 是否支持按 <kbd>Esc</kbd> 关闭 Drawer
     *
     * @param bool $value
     * @return self
     */
    public function closeOnEsc(bool $value = true): static
    {
        return $this->set('closeOnEsc', $value);
    }

    /**
     * 点击内容区外是否关闭 Drawer
     *
     * @param bool $value
     * @return self
     */
    public function closeOnOutside(bool $value = true): static
    {
        return $this->set('closeOnOutside', $value);
    }

    /**
     * 是否显示蒙层
     *
     * @param bool $value
     * @return self
     */
    public function overlay(bool $value = true): static
    {
        return $this->set('overlay', $value);
    }

    /**
     * 是否可通过拖拽改变 Drawer 大小
     *
     * @param bool $value
     * @return self
     */
    public function resizable(bool $value = true): static
    {
        return $this->set('resizable', $value);
    }

    /**
     * `500px`
     *
     * @param mixed $value
     * @return self
     */
    public function width(mixed $value = null): static
    {
        return $this->set('width', $value);
    }

    /**
     * `500px`
     *
     * @param mixed $value
     * @return self
     */
    public function height(mixed $value = null): static
    {
        return $this->set('height', $value);
    }

    /**
     * 可以不设置，默认只有两个按钮。
     *
     * @param mixed $value
     * @return self
     */
    public function actions(mixed $value = null): static
    {
        return $this->set('actions', $value);
    }

    /**
     * 支持 [数据映射](../../docs/concepts/data-mapping)，如果不设定将默认将触发按钮的上下文中继承数据。
     *
     * @param array $value
     * @return self
     */
    public function data(array $value = []): static
    {
        return $this->set('data', $value);
    }
}
