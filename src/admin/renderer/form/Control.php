<?php
namespace warm\admin\renderer\form;

use warm\admin\renderer\BaseRenderer;
use warm\admin\renderer\form\FormItemTrait;

/**
 * Control
 *
 * @see https://aisuda.bce.baidu.com/amis/zh-CN/components/control
 */
class Control extends BaseRenderer
{
    use FormItemTrait;

    public string $type = 'control';


}
