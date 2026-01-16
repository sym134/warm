<?php
namespace warm\admin\renderer\form;

use warm\admin\renderer\BaseRenderer;
use warm\admin\renderer\form\FormItemTrait;

/**
 * InputKv
 *
 * @see https://aisuda.bce.baidu.com/amis/zh-CN/components/input-kv
 */
class InputKv extends BaseRenderer
{
    use FormItemTrait;

    public string $type = 'input-kv';

    /**
     * 值类型
     *
     * @param mixed $value
     * @return self
     */
    public function valueType(mixed $value = null): self
    {
        return $this->set('valueType', $value);
    }

    /**
     * key 的提示信息的
     *
     * @param string $value
     * @return self
     */
    public function keyPlaceholder(string $value = ''): self
    {
        return $this->set('keyPlaceholder', $value);
    }

    /**
     * value 的提示信息的
     *
     * @param string $value
     * @return self
     */
    public function valuePlaceholder(string $value = ''): self
    {
        return $this->set('valuePlaceholder', $value);
    }

    /**
     * 是否可拖拽排序
     *
     * @param bool $value
     * @return self
     */
    public function draggable(bool $value = true): self
    {
        return $this->set('draggable', $value);
    }

    /**
     * 默认值
     *
     * @param mixed $value
     * @return self
     */
    public function defaultValue(mixed $value = null): self
    {
        return $this->set('defaultValue', $value);
    }

    /**
     * 是否自动转换 json 对象字符串
     *
     * @param bool $value
     * @return self
     */
    public function autoParseJSON(bool $value = true): self
    {
        return $this->set('autoParseJSON', $value);
    }
}
