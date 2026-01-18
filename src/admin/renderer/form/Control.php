<?php
namespace warm\admin\renderer\form;

use warm\admin\renderer\BaseRenderer;
use warm\admin\renderer\trait\FormItem;

/**
 * Control 控件
 *
 * @see https://aisuda.bce.baidu.com/amis/zh-CN/components/control
 */
class Control extends BaseRenderer
{
    use FormItem;

    public string $type = 'control';


}
