<?php
namespace warm\admin\renderer\form;

use warm\admin\renderer\BaseRenderer;
use warm\admin\renderer\trait\FormItem;

/**
 * JsonSchema
 *
 * @see https://aisuda.bce.baidu.com/amis/zh-CN/components/json-schema
 */
class JsonSchema extends BaseRenderer
{
    use FormItem;

    public string $type = 'json-schema';

    /**
     * 
     *
     * @param mixed $value
     * @return self
     */
    public function schema(mixed $value = null): static
    {
        return $this->set('schema', $value);
    }
}
