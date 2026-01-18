<?php

namespace warm\admin\renderer\expand;
use warm\admin\renderer\BaseRenderer;

/**
 * CustomSvgIcon
 *
 * @author slowlyo
 * @version 6.13.0
 */
class CustomSvgIcon extends BaseRenderer
{
    public string $type = 'custom-svg-icon';

    /**
     * 设置样式
     *
     * @param mixed $value
     * @return $this
     */
    public function className(mixed $value): static
    {
        return $this->set('className', $value);
    }

    /**
     * 设置图标的名称
     *
     * @param string $value
     * @return $this
     */
    public function icon(string $value = ''): static
    {
        return $this->set('icon', $value);
    }

}
