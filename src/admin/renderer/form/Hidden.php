<?php
namespace warm\admin\renderer\form;

use warm\admin\renderer\BaseRenderer;
use warm\admin\renderer\form\FormItemTrait;

/**
 * Hidden
 *
 * @see https://aisuda.bce.baidu.com/amis/zh-CN/components/hidden
 */
class Hidden extends BaseRenderer
{
    use FormItemTrait;

    public string $type = 'hidden';


}
