<?php
namespace warm\admin\renderer;
/**
 * Panel
 *
 * @see https://aisuda.bce.baidu.com/amis/zh-CN/components/panel
 */
class Panel extends BaseRenderer
{
    public string $type = 'panel';

    /**
     * 外层 Dom 的类名
     *
     * @param string $value
     * @return self
     */
    public function className(string $value = 'panel-default'): self
    {
        return $this->set('className', $value);
    }

    /**
     * header 区域的类名
     *
     * @param string $value
     * @return self
     */
    public function headerClassName(string $value = 'panel-heading'): self
    {
        return $this->set('headerClassName', $value);
    }

    /**
     * footer 区域的类名
     *
     * @param string $value
     * @return self
     */
    public function footerClassName(string $value = 'panel-footer bg-light lter wrapper'): self
    {
        return $this->set('footerClassName', $value);
    }

    /**
     * actions 区域的类名
     *
     * @param string $value
     * @return self
     */
    public function actionsClassName(string $value = 'panel-footer'): self
    {
        return $this->set('actionsClassName', $value);
    }

    /**
     * body 区域的类名
     *
     * @param string $value
     * @return self
     */
    public function bodyClassName(string $value = 'panel-body'): self
    {
        return $this->set('bodyClassName', $value);
    }

    /**
     * 标题
     *
     * @param mixed $value
     * @return self
     */
    public function title(mixed $value = null): self
    {
        return $this->set('title', $value);
    }

    /**
     * 头部容器
     *
     * @param mixed $value
     * @return self
     */
    public function header(mixed $value = null): self
    {
        return $this->set('header', $value);
    }

    /**
     * 内容容器
     *
     * @param mixed $value
     * @return self
     */
    public function body(mixed $value = null): self
    {
        return $this->set('body', $value);
    }

    /**
     * 底部容器
     *
     * @param mixed $value
     * @return self
     */
    public function footer(mixed $value = null): self
    {
        return $this->set('footer', $value);
    }

    /**
     * 是否固定底部容器
     *
     * @param bool $value
     * @return self
     */
    public function affixFooter(bool $value = true): self
    {
        return $this->set('affixFooter', $value);
    }

    /**
     * 按钮区域
     *
     * @param mixed $value
     * @return self
     */
    public function actions(mixed $value = null): self
    {
        return $this->set('actions', $value);
    }
}
