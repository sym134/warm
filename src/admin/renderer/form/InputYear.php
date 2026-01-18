<?php
namespace warm\admin\renderer\form;

use warm\admin\renderer\BaseRenderer;
use warm\admin\renderer\trait\FormItem;

/**
 * InputYear 年份选择器
 *
 * @see https://aisuda.bce.baidu.com/amis/zh-CN/components/input-year
 */
class InputYear extends InputDate
{
    use FormItem;
    public string $type = 'input-year';


}