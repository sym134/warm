<?php
namespace warm\admin\renderer;
/**
 * TooltipWrapper 文字提示包装器
 *
 * @see https://aisuda.bce.baidu.com/amis/zh-CN/components/tooltip-wrapper
 */
class TooltipWrapper extends BaseRenderer
{
    public string $type = 'tooltip-wrapper';

    /**
     * 文字提示标题
     *
     * @param string $value
     * @return self
     */
    public function title(string $value = ''): static
    {
        return $this->set('title', $value);
    }

    /**
     * 文字提示内容, 兼容之前的 tooltip 属性
     *
     * @param string $value
     * @return self
     */
    public function content(string $value = ''): static
    {
        return $this->set('content', $value);
    }

    /**
     * "right" \
     *
     * @param mixed $value
     * @return self
     */
    public function placement(mixed $value = null): static
    {
        return $this->set('placement', $value);
    }

    /**
     * `"light"`
     *
     * @param mixed $value
     * @return self
     */
    public function tooltipTheme(mixed $value = null): static
    {
        return $this->set('tooltipTheme', $value);
    }

    /**
     * 文字提示浮层位置相对偏移量，单位 px
     *
     * @param mixed $value
     * @return self
     */
    public function offset(mixed $value = null): static
    {
        return $this->set('offset', $value);
    }

    /**
     * 是否展示浮层指向箭头
     *
     * @param bool $value
     * @return self
     */
    public function showArrow(bool $value = true): static
    {
        return $this->set('showArrow', $value);
    }

    /**
     * 是否鼠标可以移入到浮层中
     *
     * @param bool $value
     * @return self
     */
    public function enterable(bool $value = true): static
    {
        return $this->set('enterable', $value);
    }

    /**
     * 是否禁用浮层提示
     *
     * @param bool $value
     * @return self
     */
    public function disabled(bool $value = true): static
    {
        return $this->set('disabled', $value);
    }

    /**
     * "focus" \
     *
     * @param mixed $value
     * @return self
     */
    public function trigger(mixed $value = null): static
    {
        return $this->set('trigger', $value);
    }

    /**
     * 浮层延迟展示时间，单位 ms
     *
     * @param int|float $value
     * @return self
     */
    public function mouseEnterDelay(int|float $value = 0): static
    {
        return $this->set('mouseEnterDelay', $value);
    }

    /**
     * 浮层延迟隐藏时间，单位 ms
     *
     * @param int|float $value
     * @return self
     */
    public function mouseLeaveDelay(int|float $value = 300): static
    {
        return $this->set('mouseLeaveDelay', $value);
    }

    /**
     * 是否点击非内容区域关闭提示
     *
     * @param bool $value
     * @return self
     */
    public function rootClose(bool $value = true): static
    {
        return $this->set('rootClose', $value);
    }

    /**
     * 内容区是否内联显示
     *
     * @param bool $value
     * @return self
     */
    public function inline(bool $value = true): static
    {
        return $this->set('inline', $value);
    }

    /**
     * "span"`
     *
     * @param string $value
     * @return self
     */
    public function wrapperComponent(string $value = 'div'): static
    {
        return $this->set('wrapperComponent', $value);
    }

    /**
     * 内容容器
     *
     * @param mixed $value
     * @return self
     */
    public function body(mixed $value = null): static
    {
        return $this->set('body', $value);
    }

    /**
     * 
     *
     * @param mixed $value
     * @return self
     */
    public function style(mixed $value = null): static
    {
        return $this->set('style', $value);
    }

    /**
     * 
     *
     * @param mixed $value
     * @return self
     */
    public function tooltipStyle(mixed $value = null): static
    {
        return $this->set('tooltipStyle', $value);
    }

    /**
     * 内容区类名
     *
     * @param mixed $value
     * @return self
     */
    public function className(mixed $value = ''): static
    {
        return $this->set('className', $value);
    }

    /**
     * 文字提示浮层类名
     *
     * @param string $value
     * @return self
     */
    public function tooltipClassName(string $value = ''): static
    {
        return $this->set('tooltipClassName', $value);
    }

    /**
     * 箭头类名
     *
     * @param string $value
     * @return self
     */
    public function tooltipArrowClassName(string $value = ''): static
    {
        return $this->set('tooltipArrowClassName', $value);
    }
}
