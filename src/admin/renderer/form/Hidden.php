<?php
namespace warm\admin\renderer\form;

use warm\admin\renderer\BaseRenderer;
use warm\admin\renderer\trait\FormItem;

/**
 * Hidden 隐藏输入框
 *
 * @see https://aisuda.bce.baidu.com/amis/zh-CN/components/hidden
 */
class Hidden extends BaseRenderer
{
    use FormItem;

    public string $type = 'hidden';


}
