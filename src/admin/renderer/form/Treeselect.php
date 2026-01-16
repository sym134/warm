<?php
namespace warm\admin\renderer\form;

use warm\admin\renderer\BaseRenderer;
use warm\admin\renderer\form\FormItemTrait;

/**
 * Treeselect
 *
 * @see https://aisuda.bce.baidu.com/amis/zh-CN/components/treeselect
 */
class Treeselect extends BaseRenderer
{
    use FormItemTrait;

    public string $type = 'treeselect';

    /**
     * 是否隐藏选择框中已选择节点的路径 label 信息
     *
     * @param bool $value
     * @return self
     */
    public function hideNodePathLabel(bool $value = true): self
    {
        return $this->set('hideNodePathLabel', $value);
    }

    /**
     * 只允许选择叶子节点
     *
     * @param bool $value
     * @return self
     */
    public function onlyLeaf(bool $value = true): self
    {
        return $this->set('onlyLeaf', $value);
    }

    /**
     * 是否可检索，仅在 type 为 `tree-select` 的时候生效
     *
     * @param bool $value
     * @return self
     */
    public function searchable(bool $value = true): self
    {
        return $this->set('searchable', $value);
    }
}
