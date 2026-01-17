<?php
namespace warm\admin\renderer;
/**
 * AnchorNav
 *
 * @see https://aisuda.bce.baidu.com/amis/zh-CN/components/anchor-nav
 */
class AnchorNav extends BaseRenderer
{
    public string $type = 'anchor-nav';

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
     * 导航 Dom 的类名
     *
     * @param string $value
     * @return self
     */
    public function linkClassName(string $value = ''): static
    {
        return $this->set('linkClassName', $value);
    }

    /**
     * 锚点区域 Dom 的类名
     *
     * @param string $value
     * @return self
     */
    public function sectionClassName(string $value = ''): static
    {
        return $this->set('sectionClassName', $value);
    }

    /**
     * links 内容
     *
     * @param array $value
     * @return self
     */
    public function links(array $value = []): static
    {
        return $this->set('links', $value);
    }

    /**
     * 可以配置导航水平展示还是垂直展示。对应的配置项分别是：vertical、horizontal
     *
     * @param string $value
     * @return self
     */
    public function direction(string $value = 'vertical'): static
    {
        return $this->set('direction', $value);
    }

    /**
     * 需要定位的区域
     *
     * @param string $value
     * @return self
     */
    public function active(string $value = ''): static
    {
        return $this->set('active', $value);
    }
}
