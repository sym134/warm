<?php
namespace warm\admin\renderer\form;

use warm\admin\renderer\BaseRenderer;
use warm\admin\renderer\trait\NameAndLabel;

/**
 * ButtonToolbar
 *
 * @see https://aisuda.bce.baidu.com/amis/zh-CN/components/button-toolbar
 */
class ButtonToolbar extends BaseRenderer
{
    use NameAndLabel;

    public string $type = 'button-toolbar';

    /**
     * 按钮组
     *
     * @param mixed $value
     * @return self
     */
    public function buttons(mixed $value = null): static
    {
        return $this->set('buttons', $value);
    }
}
