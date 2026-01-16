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
     * 'base' \
     *
     * @param mixed $value
     * @return self
     */
    public function gap(mixed $value = null): self
    {
        return $this->set('gap', $value);
    }

    /**
     * 'bottom' \
     *
     * @param mixed $value
     * @return self
     */
    public function valign(mixed $value = null): self
    {
        return $this->set('valign', $value);
    }

    /**
     * 'between' \
     *
     * @param mixed $value
     * @return self
     */
    public function align(mixed $value = null): self
    {
        return $this->set('align', $value);
    }

    /**
     * 成员可以是其他渲染器
     *
     * @param mixed $value
     * @return self
     */
    public function columns(mixed $value = null): self
    {
        return $this->set('columns', $value);
    }
}
