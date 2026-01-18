<?php
namespace warm\admin\renderer;

/**
 * Alert 提示
 *
 * @see https://aisuda.bce.baidu.com/amis/zh-CN/components/alert
 */
class Alert extends BaseRenderer
{
    public string $type = 'alert';

    /**
     * alert标题
     *
     * @param string $value
     * @return self
     */
    public function title(string $value = ''): static
    {
        return $this->set('title', $value);
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
     * 级别，可以是：`info`、`success`、`warning` 或者 `danger`
     *
     * @param string $value
     * @return self
     */
    public function level(string $value = 'info'): static
    {
        return $this->set('level', $value);
    }

    /**
     * 内容
     *
     * @param string|array $value
     * @return self
     */
    public function body(string|array $value = []): static
    {
        return $this->set('body', $value);
    }

    /**
     * 是否显示关闭按钮
     *
     * @param bool $value
     * @return self
     */
    public function showCloseButton(bool $value = true): static
    {
        return $this->set('showCloseButton', $value);
    }

    /**
     * 关闭按钮的 CSS 类名
     *
     * @param string $value
     * @return self
     */
    public function closeButtonClassName(string $value = ''): static
    {
        return $this->set('closeButtonClassName', $value);
    }

    /**
     * 是否显示 icon
     *
     * @param bool $value
     * @return self
     */
    public function showIcon(bool $value = true): static
    {
        return $this->set('showIcon', $value);
    }

    /**
     * 自定义 icon
     *
     * @param string $value
     * @return self
     */
    public function icon(string $value = ''): static
    {
        return $this->set('icon', $value);
    }

    /**
     * icon 的 CSS 类名
     *
     * @param string $value
     * @return self
     */
    public function iconClassName(string $value = ''): static
    {
        return $this->set('iconClassName', $value);
    }

    /**
     * 
     *
     * @param array $value
     * @return self
     */
    public function actions(array $value = []): static
    {
        return $this->set('actions', $value);
    }
}
