<?php
namespace warm\admin\renderer\form;

use warm\admin\renderer\trait\FormItem;
use warm\admin\renderer\trait\OnEvent;

/**
 * TreeSelect 树形选择器
 *
 * @see https://aisuda.bce.baidu.com/amis/zh-CN/components/treeselect
 */
class TreeSelect extends InputTree
{
    use FormItem;
    use OnEvent;

    public string $type = 'tree-select';

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
     * 是否可检索，仅在 type 为 `tree-select` 的时候生效
     *
     * @param bool $value
     * @return self
     */
    public function searchable(bool $value = true): static
    {
        return $this->set('searchable', $value);
    }
}
