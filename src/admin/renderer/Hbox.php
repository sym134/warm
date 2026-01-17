<?php
namespace warm\admin\renderer;
/**
 * Hbox
 *
 * @see https://aisuda.bce.baidu.com/amis/zh-CN/components/hbox
 */
class Hbox extends BaseRenderer
{
    public string $type = 'hbox';

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
     * 'base' \
     *
     * @param mixed $value
     * @return self
     */
    public function gap(mixed $value = null): static
    {
        return $this->set('gap', $value);
    }

    /**
     * 'bottom' \
     *
     * @param mixed $value
     * @return self
     */
    public function valign(mixed $value = null): static
    {
        return $this->set('valign', $value);
    }

    /**
     * 'between' \
     *
     * @param mixed $value
     * @return self
     */
    public function align(mixed $value = null): static
    {
        return $this->set('align', $value);
    }

    /**
     * 成员可以是其他渲染器
     *
     * @param mixed $value
     * @return self
     */
    public function columns(mixed $value = null): static
    {
        return $this->set('columns', $value);
    }
}
