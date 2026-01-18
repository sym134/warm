<?php
namespace warm\admin\renderer\form;

use warm\admin\renderer\BaseRenderer;
use warm\admin\renderer\trait\FormItem;
use warm\admin\renderer\trait\OnEvent;

/**
 * Textarea 多行文本输入框
 *
 * @see https://aisuda.bce.baidu.com/amis/zh-CN/components/textarea
 */
class Textarea extends BaseRenderer
{
    use FormItem;
    use OnEvent;

    public string $type = 'textarea';

    /**
     * 最小行数
     *
     * @param int|float $value
     * @return self
     */
    public function minRows(int|float $value = 3): static
    {
        return $this->set('minRows', $value);
    }

    /**
     * 最大行数
     *
     * @param int|float $value
     * @return self
     */
    public function maxRows(int|float $value = 20): static
    {
        return $this->set('maxRows', $value);
    }

    /**
     * 是否去除首尾空白文本
     *
     * @param bool $value
     * @return self
     */
    public function trimContents(bool $value = true): static
    {
        return $this->set('trimContents', $value);
    }

    /**
     * 是否只读
     *
     * @param bool $value
     * @return self
     */
    public function readOnly(bool $value = true): static
    {
        return $this->set('readOnly', $value);
    }

    /**
     * 是否显示计数器
     *
     * @param bool $value
     * @return self
     */
    public function showCounter(bool $value = true): static
    {
        return $this->set('showCounter', $value);
    }

    /**
     * 限制最大字数
     *
     * @param int|float $value
     * @return self
     */
    public function maxLength(int|float $value = 0): static
    {
        return $this->set('maxLength', $value);
    }

    /**
     * 是否可清除
     *
     * @param bool $value
     * @return self
     */
    public function clearable(bool $value = true): static
    {
        return $this->set('clearable', $value);
    }

    /**
     * 清除后设置此配置项给定的值。
     *
     * @param string $value
     * @return self
     */
    public function resetValue(string $value = ''): static
    {
        return $this->set('resetValue', $value);
    }
}
