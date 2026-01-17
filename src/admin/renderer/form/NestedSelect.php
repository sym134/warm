<?php
namespace warm\admin\renderer\form;

use warm\admin\renderer\BaseRenderer;
use warm\admin\renderer\trait\FormItem;
use warm\admin\renderer\trait\OnEvent;

/**
 * NestedSelect
 *
 * @see https://aisuda.bce.baidu.com/amis/zh-CN/components/nestedselect
 */
class NestedSelect extends BaseRenderer
{
    use FormItem;
    use OnEvent;

    public string $type = 'nested-select';

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

    /**
     * 设置 `true`时，当选中父节点时不自动选择子节点。
     *
     * @param bool $value
     * @return self
     */
    public function cascade(bool $value = true): static
    {
        return $this->set('cascade', $value);
    }

    /**
     * 设置 `true`时，选中父节点时，值里面将包含子节点的值，否则只会保留父节点的值。
     *
     * @param bool $value
     * @return self
     */
    public function withChildren(bool $value = true): static
    {
        return $this->set('withChildren', $value);
    }

    /**
     * 多选时，选中父节点时，是否只将其子节点加入到值中。
     *
     * @param bool $value
     * @return self
     */
    public function onlyChildren(bool $value = true): static
    {
        return $this->set('onlyChildren', $value);
    }

    /**
     * 可否搜索
     *
     * @param bool $value
     * @return self
     */
    public function searchable(bool $value = true): static
    {
        return $this->set('searchable', $value);
    }

    /**
     * 搜索框占位文本
     *
     * @param string $value
     * @return self
     */
    public function searchPromptText(string $value = '输入内容进行检索'): static
    {
        return $this->set('searchPromptText', $value);
    }

    /**
     * 无结果时的文本
     *
     * @param string $value
     * @return self
     */
    public function noResultsText(string $value = '未找到任何结果'): static
    {
        return $this->set('noResultsText', $value);
    }

    /**
     * 可否多选
     *
     * @param bool $value
     * @return self
     */
    public function multiple(bool $value = true): static
    {
        return $this->set('multiple', $value);
    }

    /**
     * 是否隐藏选择框中已选择节点的路径 label 信息
     *
     * @param bool $value
     * @return self
     */
    public function hideNodePathLabel(bool $value = true): static
    {
        return $this->set('hideNodePathLabel', $value);
    }

    /**
     * 只允许选择叶子节点
     *
     * @param bool $value
     * @return self
     */
    public function onlyLeaf(bool $value = true): static
    {
        return $this->set('onlyLeaf', $value);
    }

    /**
     * 标签的最大展示数量，超出数量后以收纳浮层的方式展示，仅在多选模式开启后生效
     *
     * @param int|float $value
     * @return self
     */
    public function maxTagCount(int|float $value = 0): static
    {
        return $this->set('maxTagCount', $value);
    }

    /**
     * 收纳浮层的配置属性，详细配置参考[Tooltip](../tooltip#属性表)
     *
     * @param mixed $value
     * @return self
     */
    public function overflowTagPopover(mixed $value = null): static
    {
        return $this->set('overflowTagPopover', $value);
    }
}
