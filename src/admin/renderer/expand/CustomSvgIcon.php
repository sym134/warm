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
     */
    public function icon($value = '')
    {
        return $this->set('icon', $value);
    }

    /**
     * 指定为 custom-svg-icon 渲染器。
     */
    public function type($value = 'custom-svg-icon')
    {
        return $this->set('type', $value);
    }


}
