<?php
namespace warm\admin\renderer\form;

use warm\admin\renderer\BaseRenderer;
use warm\admin\renderer\form\FormItemTrait;

/**
 * InputTag
 *
 * @see https://aisuda.bce.baidu.com/amis/zh-CN/components/input-tag
 */
class InputTag extends BaseRenderer
{
    use FormItemTrait;

    public string $type = 'input-tag';

    /**
     * [选项组](./options#%E9%9D%99%E6%80%81%E9%80%89%E9%A1%B9%E7%BB%84-options)
     *
     * @param array $value
     * @return self
     */
    public function options(array $value = []): self
    {
        return $this->set('options', $value);
    }

    /**
     * 选项提示
     *
     * @param array $value
     * @return self
     */
    public function optionsTip(array $value = []): self
    {
        return $this->set('optionsTip', $value);
    }

    /**
     * [动态选项组](./options#%E5%8A%A8%E6%80%81%E9%80%89%E9%A1%B9%E7%BB%84-source)
     *
     * @param mixed $value
     * @return self
     */
    public function source(mixed $value = null): self
    {
        return $this->set('source', $value);
    }

    /**
     * [拼接符](./options#%E6%8B%BC%E6%8E%A5%E7%AC%A6-delimiter)
     *
     * @param string $value
     * @return self
     */
    public function delimiter(string $value = 'false'): self
    {
        return $this->set('delimiter', $value);
    }

    /**
     * [选项标签字段](./options#%E9%80%89%E9%A1%B9%E6%A0%87%E7%AD%BE%E5%AD%97%E6%AE%B5-labelfield)
     *
     * @param string $value
     * @return self
     */
    public function labelField(string $value = 'label'): self
    {
        return $this->set('labelField', $value);
    }

    /**
     * [选项值字段](./options#%E9%80%89%E9%A1%B9%E5%80%BC%E5%AD%97%E6%AE%B5-valuefield)
     *
     * @param string $value
     * @return self
     */
    public function valueField(string $value = 'value'): self
    {
        return $this->set('valueField', $value);
    }

    /**
     * [拼接值](./options#%E6%8B%BC%E6%8E%A5%E5%80%BC-joinvalues)
     *
     * @param bool $value
     * @return self
     */
    public function joinValues(bool $value = true): self
    {
        return $this->set('joinValues', $value);
    }

    /**
     * [提取值](./options#%E6%8F%90%E5%8F%96%E5%A4%9A%E9%80%89%E5%80%BC-extractvalue)
     *
     * @param bool $value
     * @return self
     */
    public function extractValue(bool $value = true): self
    {
        return $this->set('extractValue', $value);
    }

    /**
     * 在有值的时候是否显示一个删除图标在右侧。
     *
     * @param bool $value
     * @return self
     */
    public function clearable(bool $value = true): self
    {
        return $this->set('clearable', $value);
    }

    /**
     * 删除后设置此配置项给定的值。
     *
     * @param string $value
     * @return self
     */
    public function resetValue(string $value = ''): self
    {
        return $this->set('resetValue', $value);
    }

    /**
     * 允许添加的标签的最大数量
     *
     * @param int|float $value
     * @return self
     */
    public function max(int|float $value = 0): self
    {
        return $this->set('max', $value);
    }

    /**
     * 单个标签的最大文本长度
     *
     * @param int|float $value
     * @return self
     */
    public function maxTagLength(int|float $value = 0): self
    {
        return $this->set('maxTagLength', $value);
    }

    /**
     * 标签的最大展示数量，超出数量后以收纳浮层的方式展示，仅在多选模式开启后生效
     *
     * @param int|float $value
     * @return self
     */
    public function maxTagCount(int|float $value = 0): self
    {
        return $this->set('maxTagCount', $value);
    }

    /**
     * 收纳浮层的配置属性，详细配置参考[Tooltip](../tooltip#属性表)
     *
     * @param mixed $value
     * @return self
     */
    public function overflowTagPopover(mixed $value = null): self
    {
        return $this->set('overflowTagPopover', $value);
    }

    /**
     * 是否开启批量添加模式
     *
     * @param bool $value
     * @return self
     */
    public function enableBatchAdd(bool $value = true): self
    {
        return $this->set('enableBatchAdd', $value);
    }

    /**
     * 开启批量添加后，输入多个标签的分隔符，支持传入多个符号，默认为"-"
     *
     * @param string $value
     * @return self
     */
    public function separator(string $value = '-'): self
    {
        return $this->set('separator', $value);
    }
}
