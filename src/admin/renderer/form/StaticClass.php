<?php
namespace warm\admin\renderer\form;

use warm\admin\renderer\BaseRenderer;
/**
 * Static
 *
 * @see https://aisuda.bce.baidu.com/amis/zh-CN/components/static
 */
class StaticClass extends BaseRenderer
{
    use FormItemTrait;

    public string $type = 'static';


}
