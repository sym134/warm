<?php
namespace warm\admin\renderer;
/**
 * Tag 标签
 *
 * @see https://aisuda.bce.baidu.com/amis/zh-CN/components/tag
 */
class Tag extends BaseRenderer
{
    public string $type = 'tag';
    /**
     * 'status'`
     *
     * @param mixed $value
     * @return self
     */
    public function displayMode(mixed $value = null): static
    {
        return $this->set('displayMode', $value);
    }

    /**
     * 'error' \
     *
     * @param mixed $value
     * @return self
     */
    public function color(mixed $value = null): static
    {
        return $this->set('color', $value);
    }

    /**
     * 标签内容
     *
     * @param mixed $value
     * @return self
     */
    public function label(mixed $value = '-'): static
    {
        return $this->set('label', $value);
    }

    /**
     * status 模式下的前置图标
     *
     * @param mixed $value
     * @return self
     */
    public function icon(mixed $value = null): static
    {
        return $this->set('icon', $value);
    }

    /**
     * 自定义 CSS 样式类名
     *
     * @param mixed $value
     * @return self
     */
    public function className(mixed $value = ''): static
    {
        return $this->set('className', $value);
    }

    /**
     * 自定义样式（行内样式），优先级最高
     *
     * @param array $value
     * @return self
     */
    public function style(array $value = []): static
    {
        return $this->set('style', $value);
    }

    /**
     * 是否展示关闭按钮
     *
     * @param bool $value
     * @return self
     */
    public function closable(bool $value = true): static
    {
        return $this->set('closable', $value);
    }
}
