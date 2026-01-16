<?php
namespace warm\admin\renderer\form;

use warm\admin\renderer\BaseRenderer;
use warm\admin\renderer\form\FormItemTrait;

/**
 * InputKvs
 *
 * @see https://aisuda.bce.baidu.com/amis/zh-CN/components/input-kvs
 */
class InputKvs extends BaseRenderer
{
    use FormItemTrait;

    public string $type = 'input-kvs';


}
