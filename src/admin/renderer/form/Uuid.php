<?php
namespace warm\admin\renderer\form;

use warm\admin\renderer\BaseRenderer;
use warm\admin\renderer\form\FormItemTrait;

/**
 * Uuid
 *
 * @see https://aisuda.bce.baidu.com/amis/zh-CN/components/uuid
 */
class Uuid extends BaseRenderer
{
    use FormItemTrait;

    public string $type = 'uuid';

    /**
     * 目前 uuid 的唯一可设置参数是 length，用于生成短随机数
     *
     * @param int $value
     * @return Uuid
     */
    public function length(int $value): Uuid
    {
        return $this->set('length', $value);
    }
}
