<?php

namespace warm\admin\renderer\form;

use warm\admin\renderer\BaseRenderer;
use warm\admin\renderer\trait\OnEvent;
use warm\admin\renderer\trait\FormItem;

/**
 * ButtonGroupSelect 按钮组选择器
 *
 * @see https://aisuda.bce.baidu.com/amis/zh-CN/components/button-group-select
 */
class ButtonGroupSelect extends BaseRenderer
{
    use FormItem;
    use OnEvent;
    public string $type = 'button-group-select';

    /**
     * 是否使用垂直模式
     *
     * @param bool $value
     * @return self
     */
    public function vertical(bool $value = true): static
    {
        return $this->set('vertical', $value);
    }

    /**
     * 是否使用平铺模式
     *
     * @param bool $value
     * @return self
     */
    public function tiled(bool $value = true): static
    {
        return $this->set('tiled', $value);
    }

    /**
     * 'secondary' \
     *
     * @param mixed $value
     * @return self
     */
    public function btnLevel(mixed $value = null): static
    {
        return $this->set('btnLevel', $value);
    }

    /**
     * 'secondary' \
     *
     * @param mixed $value
     * @return self
     */
    public function btnActiveLevel(mixed $value = null): static
    {
        return $this->set('btnActiveLevel', $value);
    }

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
     * [多选](./options#%E5%A4%9A%E9%80%89-multiple)
     *
     * @param bool $value
     * @return self
     */
    public function multiple(bool $value = true): static
    {
        return $this->set('multiple', $value);
    }

    /**
     * [选项标签字段](./options#%E9%80%89%E9%A1%B9%E6%A0%87%E7%AD%BE%E5%AD%97%E6%AE%B5-labelfield)
     *
     * @param bool $value
     * @return self
     */
    public function labelField(bool $value = true): static
    {
        return $this->set('labelField', $value);
    }

    /**
     * [选项值字段](./options#%E9%80%89%E9%A1%B9%E5%80%BC%E5%AD%97%E6%AE%B5-valuefield)
     *
     * @param bool $value
     * @return self
     */
    public function valueField(bool $value = true): static
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
}
