<?php
namespace warm\admin\renderer\form;

use warm\admin\renderer\BaseRenderer;
use warm\admin\renderer\form\FormItemTrait;

/**
 * JsonSchema
 *
 * @see https://aisuda.bce.baidu.com/amis/zh-CN/components/json-schema
 */
class JsonSchema extends BaseRenderer
{
    use FormItemTrait;

    public string $type = 'json-schema';

    /**
     * 
     *
     * @param mixed $value
     * @return self
     */
    public function schema(mixed $value = null): self
    {
        return $this->set('schema', $value);
    }
}
