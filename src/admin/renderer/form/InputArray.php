<?php
namespace warm\admin\renderer\form;

use warm\admin\renderer\BaseRenderer;
use warm\admin\renderer\trait\DataDomain;
use warm\admin\renderer\trait\FormItem;

/**
 * InputArray
 *
 * @see https://aisuda.bce.baidu.com/amis/zh-CN/components/input-array
 */
class InputArray extends BaseRenderer
{
    use FormItem;
    use DataDomain;

    public string $type = 'input-array';

    /**
     * 配置单项表单类型
     *
     * @param mixed $value
     * @return self
     */
    public function items(mixed $value = null): static
    {
        return $this->set('items', $value);
    }

    /**
     * 是否可新增。
     *
     * @param bool $value
     * @return self
     */
    public function addable(bool $value = true): static
    {
        return $this->set('addable', $value);
    }

    /**
     * 是否可删除
     *
     * @param bool $value
     * @return self
     */
    public function removable(bool $value = true): static
    {
        return $this->set('removable', $value);
    }

    /**
     * 是否可以拖动排序, 需要注意的是当启用拖动排序的时候，会多一个\$id 字段
     *
     * @param bool $value
     * @return self
     */
    public function draggable(bool $value = true): static
    {
        return $this->set('draggable', $value);
    }

    /**
     * 可拖拽的提示文字，默认为：`"可通过拖动每行中的【交换】按钮进行顺序调整"`
     *
     * @param string $value
     * @return self
     */
    public function draggableTip(string $value = ''): static
    {
        return $this->set('draggableTip', $value);
    }

    /**
     * 新增按钮文字
     *
     * @param string $value
     * @return self
     */
    public function addButtonText(string $value = '新增'): static
    {
        return $this->set('addButtonText', $value);
    }

    /**
     * 限制最小长度
     *
     * @param int|float $value
     * @return self
     */
    public function minLength(int|float $value = 0): static
    {
        return $this->set('minLength', $value);
    }

    /**
     * 限制最大长度
     *
     * @param int|float $value
     * @return self
     */
    public function maxLength(int|float $value = 0): static
    {
        return $this->set('maxLength', $value);
    }

    /**
     * 新增成员时的默认值，一般根据`items`的数据类型指定需要的默认值
     *
     * @param mixed $value
     * @return self
     */
    public function scaffold(mixed $value = null): static
    {
        return $this->set('scaffold', $value);
    }
}
