<?php
namespace warm\admin\renderer;

/**
 * Alert
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
    public function title(string $value = ''): self
    {
        return $this->set('title', $value);
    }

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
     * 级别，可以是：`info`、`success`、`warning` 或者 `danger`
     *
     * @param string $value
     * @return self
     */
    public function level(string $value = 'info'): self
    {
        return $this->set('level', $value);
    }

    /**
     * 
     *
     * @param array $value
     * @return self
     */
    public function body(array $value = []): self
    {
        return $this->set('body', $value);
    }

    /**
     * 是否显示关闭按钮
     *
     * @param bool $value
     * @return self
     */
    public function showCloseButton(bool $value = true): self
    {
        return $this->set('showCloseButton', $value);
    }

    /**
     * 关闭按钮的 CSS 类名
     *
     * @param string $value
     * @return self
     */
    public function closeButtonClassName(string $value = ''): self
    {
        return $this->set('closeButtonClassName', $value);
    }

    /**
     * 是否显示 icon
     *
     * @param bool $value
     * @return self
     */
    public function showIcon(bool $value = true): self
    {
        return $this->set('showIcon', $value);
    }

    /**
     * 自定义 icon
     *
     * @param string $value
     * @return self
     */
    public function icon(string $value = ''): self
    {
        return $this->set('icon', $value);
    }

    /**
     * icon 的 CSS 类名
     *
     * @param string $value
     * @return self
     */
    public function iconClassName(string $value = ''): self
    {
        return $this->set('iconClassName', $value);
    }

    /**
     * 
     *
     * @param array $value
     * @return self
     */
    public function actions(array $value = []): self
    {
        return $this->set('actions', $value);
    }
}
