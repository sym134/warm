<?php
namespace warm\admin\renderer;
/**
 * Portlet
 *
 * @see https://aisuda.bce.baidu.com/amis/zh-CN/components/portlet
 */
class Portlet extends BaseRenderer
{
    public string $type = 'portlet';

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
     * Tabs Dom 的类名
     *
     * @param string $value
     * @return self
     */
    public function tabsClassName(string $value = ''): self
    {
        return $this->set('tabsClassName', $value);
    }

    /**
     * Tabs content Dom 的类名
     *
     * @param string $value
     * @return self
     */
    public function contentClassName(string $value = ''): self
    {
        return $this->set('contentClassName', $value);
    }

    /**
     * tabs 内容
     *
     * @param array $value
     * @return self
     */
    public function tabs(array $value = []): self
    {
        return $this->set('tabs', $value);
    }

    /**
     * tabs 关联数据，关联后可以重复生成选项卡
     *
     * @param array $value
     * @return self
     */
    public function source(array $value = []): self
    {
        return $this->set('source', $value);
    }

    /**
     * tabs 中的工具栏，不随 tab 切换而变化
     *
     * @param mixed $value
     * @return self
     */
    public function toolbar(mixed $value = null): self
    {
        return $this->set('toolbar', $value);
    }

    /**
     * 
     *
     * @param mixed $value
     * @return self
     */
    public function style(mixed $value = null): self
    {
        return $this->set('style', $value);
    }

    /**
     * 标题右侧信息
     *
     * @param mixed $value
     * @return self
     */
    public function description(mixed $value = null): self
    {
        return $this->set('description', $value);
    }

    /**
     * 隐藏头部
     *
     * @param bool $value
     * @return self
     */
    public function hideHeader(bool $value = true): self
    {
        return $this->set('hideHeader', $value);
    }

    /**
     * 去掉分隔线
     *
     * @param bool $value
     * @return self
     */
    public function divider(bool $value = true): self
    {
        return $this->set('divider', $value);
    }

    /**
     * 只有在点中 tab 的时候才渲染
     *
     * @param bool $value
     * @return self
     */
    public function mountOnEnter(bool $value = true): self
    {
        return $this->set('mountOnEnter', $value);
    }

    /**
     * 切换 tab 的时候销毁
     *
     * @param bool $value
     * @return self
     */
    public function unmountOnExit(bool $value = true): self
    {
        return $this->set('unmountOnExit', $value);
    }

    /**
     * 是否导航支持内容溢出滚动，`vertical`和`chrome`模式下不支持该属性；`chrome`模式默认压缩标签
     *
     * @param bool $value
     * @return self
     */
    public function scrollable(bool $value = true): self
    {
        return $this->set('scrollable', $value);
    }
}
