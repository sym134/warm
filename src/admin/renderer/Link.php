<?php
namespace warm\admin\renderer;
/**
 * Link 链接
 *
 * @see https://aisuda.bce.baidu.com/amis/zh-CN/components/link
 */
class Link extends BaseRenderer
{
    public string $type = 'link';

    /**
     * 标签内文本
     *
     * @param string $value
     * @return self
     */
    public function body(string $value = ''): static
    {
        return $this->set('body', $value);
    }

    /**
     * 链接地址
     *
     * @param string $value
     * @return self
     */
    public function href(string $value = ''): static
    {
        return $this->set('href', $value);
    }

    /**
     * 是否在新标签页打开
     *
     * @param bool $value
     * @return self
     */
    public function blank(bool $value = true): static
    {
        return $this->set('blank', $value);
    }

    /**
     * a 标签的 target，优先于 blank 属性
     *
     * @param string $value
     * @return self
     */
    public function htmlTarget(string $value = ''): static
    {
        return $this->set('htmlTarget', $value);
    }

    /**
     * a 标签的 title
     *
     * @param string $value
     * @return self
     */
    public function title(string $value = ''): static
    {
        return $this->set('title', $value);
    }

    /**
     * 禁用超链接
     *
     * @param bool $value
     * @return self
     */
    public function disabled(bool $value = true): static
    {
        return $this->set('disabled', $value);
    }

    /**
     * 超链接图标，以加强显示
     *
     * @param string $value
     * @return self
     */
    public function icon(string $value = ''): static
    {
        return $this->set('icon', $value);
    }

    /**
     * 右侧图标
     *
     * @param string $value
     * @return self
     */
    public function rightIcon(string $value = ''): static
    {
        return $this->set('rightIcon', $value);
    }
}
