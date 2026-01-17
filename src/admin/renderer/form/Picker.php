<?php

namespace warm\admin\renderer\form;

use warm\admin\renderer\BaseRenderer;
use warm\admin\renderer\trait\FormItem;
use warm\admin\renderer\trait\OnEvent;

/**
 * Picker
 *
 * @see https://aisuda.bce.baidu.com/amis/zh-CN/components/picker
 */
class Picker extends BaseRenderer
{
    use FormItem;
    use OnEvent;

    public string $type = 'picker';

    /**
     * [选项组](./options#%E9%9D%99%E6%80%81%E9%80%89%E9%A1%B9%E7%BB%84-options)
     *
     * @param array $value
     * @return self
     */
    public function options(array $value = []): static
    {
        return $this->set('options', $value);
    }

    /**
     * [动态选项组](./options#%E5%8A%A8%E6%80%81%E9%80%89%E9%A1%B9%E7%BB%84-source)
     *
     * @param mixed $value
     * @return self
     */
    public function source(mixed $value = null): static
    {
        return $this->set('source', $value);
    }

    /**
     * 是否为多选。
     *
     * @param bool $value
     * @return self
     */
    public function multiple(bool $value = true): static
    {
        return $this->set('multiple', $value);
    }

    /**
     * [拼接符](./options#%E6%8B%BC%E6%8E%A5%E7%AC%A6-delimiter)
     *
     * @param bool $value
     * @return self
     */
    public function delimiter(bool $value = true): static
    {
        return $this->set('delimiter', $value);
    }

    /**
     * [选项标签字段](./options#%E9%80%89%E9%A1%B9%E6%A0%87%E7%AD%BE%E5%AD%97%E6%AE%B5-labelfield)
     *
     * @param string $value
     * @return self
     */
    public function labelField(string $value = 'label'): static
    {
        return $this->set('labelField', $value);
    }

    /**
     * [选项值字段](./options#%E9%80%89%E9%A1%B9%E5%80%BC%E5%AD%97%E6%AE%B5-valuefield)
     *
     * @param string $value
     * @return self
     */
    public function valueField(string $value = 'value'): static
    {
        return $this->set('valueField', $value);
    }

    /**
     * [拼接值](./options#%E6%8B%BC%E6%8E%A5%E5%80%BC-joinvalues)
     *
     * @param bool $value
     * @return self
     */
    public function joinValues(bool $value = true): static
    {
        return $this->set('joinValues', $value);
    }

    /**
     * [提取值](./options#%E6%8F%90%E5%8F%96%E5%A4%9A%E9%80%89%E5%80%BC-extractvalue)
     *
     * @param bool $value
     * @return self
     */
    public function extractValue(bool $value = true): static
    {
        return $this->set('extractValue', $value);
    }

    /**
     * [自动填充](./options#%E8%87%AA%E5%8A%A8%E5%A1%AB%E5%85%85-autofill)
     *
     * @param array $value
     * @return self
     */
    public function autoFill(array $value = []): static
    {
        return $this->set('autoFill', $value);
    }

    /**
     * 设置模态框的标题
     *
     * @param string $value
     * @return self
     */
    public function modalTitle(string $value = '请选择'): static
    {
        return $this->set('modalTitle', $value);
    }

    /**
     * 设置 `dialog` 或者 `drawer`，用来配置弹出方式。
     *
     * @param string $value
     * @return self
     */
    public function modalMode(string $value = 'dialog'): static
    {
        return $this->set('modalMode', $value);
    }

    /**
     * 设置弹框大小
     *
     * @param string $value
     * @return self
     */
    public function modalSize(string $value = ''): static
    {
        return $this->set('modalSize', $value);
    }

    /**
     * 即用 List 类型的渲染，来展示列表信息。更多配置参考 [CRUD](../crud)
     *
     * @param mixed $value
     * @return self
     */
    public function pickerSchema(mixed $value = ''): static
    {
        return $this->set('pickerSchema', $value);
    }

    /**
     * 是否使用内嵌模式
     *
     * @param bool $value
     * @return self
     */
    public function embed(bool $value = true): static
    {
        return $this->set('embed', $value);
    }

    /**
     * 开启最大标签展示数量的相关配置 `3.4.0`
     *
     * @param mixed $value
     * @return self
     */
    public function overflowConfig(mixed $value = null): static
    {
        return $this->set('overflowConfig', $value);
    }

    /**
     * 用于控制是否显示选中项的删除图标，默认值为 `true`
     *
     * @param mixed $value
     * @return self
     */
    public function itemClearable(mixed $value = true): static
    {
        return $this->set('itemClearable', $value);
    }
}
