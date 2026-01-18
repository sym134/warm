<?php
namespace warm\admin\renderer;
/**
 * Button 按钮
 *
 * @see https://aisuda.bce.baidu.com/amis/zh-CN/components/button
 */
class Button extends Action
{
    public string $type = 'button';

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
     * 点击跳转的地址，指定此属性 button 的行为和 a 链接一致
     *
     * @param string $value
     * @return self
     */
    public function url(string $value = ''): static
    {
        return $this->set('url', $value);
    }

    /**
     * 'md' \
     *
     * @param mixed $value
     * @return self
     */
    public function size(mixed $value = null): static
    {
        return $this->set('size', $value);
    }

    /**
     * 'submit'\
     *
     * @param mixed $value
     * @return self
     */
    public function actionType(mixed $value = null): static
    {
        return $this->set('actionType', $value);
    }

    /**
     * 'enhance' \
     *
     * @param mixed $value
     * @return self
     */
    public function level(mixed $value = null): static
    {
        return $this->set('level', $value);
    }

    /**
     * 
     *
     * @param mixed $value
     * @return self
     */
    public function tooltip(mixed $value = null): static
    {
        return $this->set('tooltip', $value);
    }

    /**
     * 'bottom' \
     *
     * @param mixed $value
     * @return self
     */
    public function tooltipPlacement(mixed $value = null): static
    {
        return $this->set('tooltipPlacement', $value);
    }

    /**
     * 
     *
     * @param mixed $value
     * @return self
     */
    public function tooltipTrigger(mixed $value = null): static
    {
        return $this->set('tooltipTrigger', $value);
    }

    /**
     * 按钮失效状态
     *
     * @param mixed $value
     * @return self
     */
    public function disabled(mixed $value = false): static
    {
        return $this->set('disabled', $value);
    }

    /**
     * 
     *
     * @param mixed $value
     * @return self
     */
    public function disabledTip(mixed $value = null): static
    {
        return $this->set('disabledTip', $value);
    }

    /**
     * 将按钮宽度调整为其父宽度的选项
     *
     * @param mixed $value
     * @return self
     */
    public function block(mixed $value = false): static
    {
        return $this->set('block', $value);
    }

    /**
     * 显示按钮 loading 效果
     *
     * @param mixed $value
     * @return self
     */
    public function loading(mixed $value = false): static
    {
        return $this->set('loading', $value);
    }

    /**
     * 显示按钮 loading 表达式
     *
     * @param mixed $value
     * @return self
     */
    public function loadingOn(mixed $value = null): static
    {
        return $this->set('loadingOn', $value);
    }
}
