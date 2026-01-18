<?php
namespace warm\admin\renderer;
/**
 * Panel 面板
 *
 * @see https://aisuda.bce.baidu.com/amis/zh-CN/components/panel
 */
class Panel extends BaseRenderer
{
    public string $type = 'panel';

    /**
     * 外层 Dom 的类名
     *
     * @param mixed $value
     * @return self
     */
    public function className(mixed $value = 'panel-default'): static
    {
        return $this->set('className', $value);
    }

    /**
     * header 区域的类名
     *
     * @param string $value
     * @return self
     */
    public function headerClassName(string $value = 'panel-heading'): static
    {
        return $this->set('headerClassName', $value);
    }

    /**
     * footer 区域的类名
     *
     * @param string $value
     * @return self
     */
    public function footerClassName(string $value = 'panel-footer bg-light lter wrapper'): static
    {
        return $this->set('footerClassName', $value);
    }

    /**
     * actions 区域的类名
     *
     * @param string $value
     * @return self
     */
    public function actionsClassName(string $value = 'panel-footer'): static
    {
        return $this->set('actionsClassName', $value);
    }

    /**
     * body 区域的类名
     *
     * @param string $value
     * @return self
     */
    public function bodyClassName(string $value = 'panel-body'): static
    {
        return $this->set('bodyClassName', $value);
    }

    /**
     * 标题
     *
     * @param mixed $value
     * @return self
     */
    public function title(mixed $value = null): static
    {
        return $this->set('title', $value);
    }

    /**
     * 头部容器
     *
     * @param mixed $value
     * @return self
     */
    public function header(mixed $value = null): static
    {
        return $this->set('header', $value);
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
     * 底部容器
     *
     * @param mixed $value
     * @return self
     */
    public function footer(mixed $value = null): static
    {
        return $this->set('footer', $value);
    }

    /**
     * 是否固定底部容器
     *
     * @param bool $value
     * @return self
     */
    public function affixFooter(bool $value = true): static
    {
        return $this->set('affixFooter', $value);
    }

    /**
     * 按钮区域
     *
     * @param mixed $value
     * @return self
     */
    public function actions(mixed $value = null): static
    {
        return $this->set('actions', $value);
    }
}
