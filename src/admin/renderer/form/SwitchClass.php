<?php
namespace warm\admin\renderer\form;

use warm\admin\renderer\BaseRenderer;
use warm\admin\renderer\form\FormItemTrait;

/**
 * Switch
 *
 * @see https://aisuda.bce.baidu.com/amis/zh-CN/components/switch
 */
class SwitchClass extends BaseRenderer
{
    use FormItemTrait;

    public string $type = 'switch';

    /**
     * 选项说明
     *
     * @param string $value
     * @return self
     */
    public function option(string $value = ''): self
    {
        return $this->set('option', $value);
    }

    /**
     * SchemaCollection`
     *
     * @param mixed $value
     * @return self
     */
    public function onText(mixed $value = null): self
    {
        return $this->set('onText', $value);
    }

    /**
     * SchemaCollection`
     *
     * @param mixed $value
     * @return self
     */
    public function offText(mixed $value = null): self
    {
        return $this->set('offText', $value);
    }

    /**
     * number`
     *
     * @param mixed $value
     * @return self
     */
    public function trueValue(mixed $value = null): self
    {
        return $this->set('trueValue', $value);
    }

    /**
     * number`
     *
     * @param mixed $value
     * @return self
     */
    public function falseValue(mixed $value = null): self
    {
        return $this->set('falseValue', $value);
    }

    /**
     * `"md"`
     *
     * @param mixed $value
     * @return self
     */
    public function size(mixed $value = null): self
    {
        return $this->set('size', $value);
    }

    /**
     * 是否处于加载状态
     *
     * @param bool $value
     * @return self
     */
    public function loading(bool $value = true): self
    {
        return $this->set('loading', $value);
    }

    /**
     * icon 的类型
     *
     * @param string $value
     * @return self
     */
    public function icon(string $value = ''): self
    {
        return $this->set('icon', $value);
    }
}
