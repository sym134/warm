<?php
namespace warm\admin\renderer\form;

use warm\admin\renderer\BaseRenderer;
use warm\admin\renderer\form\FormItemTrait;

/**
 * MatrixCheckboxes
 *
 * @see https://aisuda.bce.baidu.com/amis/zh-CN/components/matrix-checkboxes
 */
class MatrixCheckboxes extends BaseRenderer
{
    use FormItemTrait;

    public string $type = 'matrix-checkboxes';

    /**
     * 列信息，数组中 `label` 字段是必须给出的
     *
     * @param array $value
     * @return self
     */
    public function columns(array $value = []): self
    {
        return $this->set('columns', $value);
    }

    /**
     * 行信息， 数组中 `label` 字段是必须给出的
     *
     * @param array $value
     * @return self
     */
    public function rows(array $value = []): self
    {
        return $this->set('rows', $value);
    }

    /**
     * 行标题说明
     *
     * @param string $value
     * @return self
     */
    public function rowLabel(string $value = ''): self
    {
        return $this->set('rowLabel', $value);
    }

    /**
     * Api 地址，如果选项组不固定，可以通过配置 `source` 动态拉取。
     *
     * @param mixed $value
     * @return self
     */
    public function source(mixed $value = null): self
    {
        return $this->set('source', $value);
    }

    /**
     * 是否多选
     *
     * @param bool $value
     * @return self
     */
    public function multiple(bool $value = true): self
    {
        return $this->set('multiple', $value);
    }

    /**
     * 设置单选模式，`multiple`为`false`时有效，可设置为`cell`, `row`, `column` 分别为全部选项中只能单选某个单元格、每行只能单选某个单元格，每列只能单选某个单元格
     *
     * @param string $value
     * @return self
     */
    public function singleSelectMode(string $value = 'column'): self
    {
        return $this->set('singleSelectMode', $value);
    }

    /**
     * 当开启多选+全选时，默认为'left'
     *
     * @param string $value
     * @return self
     */
    public function textAlign(string $value = 'center'): self
    {
        return $this->set('textAlign', $value);
    }

    /**
     * 列上的全选
     *
     * @param bool $value
     * @return self
     */
    public function yCheckAll(bool $value = true): self
    {
        return $this->set('yCheckAll', $value);
    }

    /**
     * 行上的全选
     *
     * @param bool $value
     * @return self
     */
    public function xCheckAll(bool $value = true): self
    {
        return $this->set('xCheckAll', $value);
    }
}
