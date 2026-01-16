<?php
namespace warm\admin\renderer\form;

use warm\admin\renderer\BaseRenderer;
use warm\admin\renderer\form\FormItemTrait;

/**
 * Textarea
 *
 * @see https://aisuda.bce.baidu.com/amis/zh-CN/components/textarea
 */
class Textarea extends BaseRenderer
{
    use FormItemTrait;

    public string $type = 'textarea';

    /**
     * 最小行数
     *
     * @param int|float $value
     * @return self
     */
    public function minRows(int|float $value = 3): self
    {
        return $this->set('minRows', $value);
    }

    /**
     * 最大行数
     *
     * @param int|float $value
     * @return self
     */
    public function maxRows(int|float $value = 20): self
    {
        return $this->set('maxRows', $value);
    }

    /**
     * 是否去除首尾空白文本
     *
     * @param bool $value
     * @return self
     */
    public function trimContents(bool $value = true): self
    {
        return $this->set('trimContents', $value);
    }

    /**
     * 是否只读
     *
     * @param bool $value
     * @return self
     */
    public function readOnly(bool $value = true): self
    {
        return $this->set('readOnly', $value);
    }

    /**
     * 是否显示计数器
     *
     * @param bool $value
     * @return self
     */
    public function showCounter(bool $value = true): self
    {
        return $this->set('showCounter', $value);
    }

    /**
     * 限制最大字数
     *
     * @param int|float $value
     * @return self
     */
    public function maxLength(int|float $value = 0): self
    {
        return $this->set('maxLength', $value);
    }

    /**
     * 是否可清除
     *
     * @param bool $value
     * @return self
     */
    public function clearable(bool $value = true): self
    {
        return $this->set('clearable', $value);
    }

    /**
     * 清除后设置此配置项给定的值。
     *
     * @param string $value
     * @return self
     */
    public function resetValue(string $value = ''): self
    {
        return $this->set('resetValue', $value);
    }
}
