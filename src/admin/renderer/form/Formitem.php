<?php
namespace warm\admin\renderer\form;

use warm\admin\renderer\BaseRenderer;
use warm\admin\renderer\form\FormItemTrait;

/**
 * Formitem
 *
 * @see https://aisuda.bce.baidu.com/amis/zh-CN/components/formitem
 */
class Formitem extends BaseRenderer
{
    use FormItemTrait;

    public string $type = 'formitem';
}
