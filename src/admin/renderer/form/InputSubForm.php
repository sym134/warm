<?php
namespace warm\admin\renderer\form;

use warm\admin\renderer\BaseRenderer;
use warm\admin\renderer\form\FormItemTrait;

/**
 * InputSubForm
 *
 * @see https://aisuda.bce.baidu.com/amis/zh-CN/components/input-sub-form
 */
class InputSubForm extends BaseRenderer
{
    use FormItemTrait;

    public string $type = 'input-sub-form';

    /**
     * 是否为多选模式
     *
     * @param bool $value
     * @return self
     */
    public function multiple(bool $value = true): self
    {
        return $this->set('multiple', $value);
    }

    /**
     * 当值中存在这个字段，则按钮名称将使用此字段的值来展示。
     *
     * @param string $value
     * @return self
     */
    public function labelField(string $value = ''): self
    {
        return $this->set('labelField', $value);
    }

    /**
     * 按钮默认名称
     *
     * @param string $value
     * @return self
     */
    public function btnLabel(string $value = '设置'): self
    {
        return $this->set('btnLabel', $value);
    }

    /**
     * 限制最小个数。
     *
     * @param int|float $value
     * @return self
     */
    public function minLength(int|float $value = 0): self
    {
        return $this->set('minLength', $value);
    }

    /**
     * 限制最大个数。
     *
     * @param int|float $value
     * @return self
     */
    public function maxLength(int|float $value = 0): self
    {
        return $this->set('maxLength', $value);
    }

    /**
     * 是否可拖拽排序
     *
     * @param bool $value
     * @return self
     */
    public function draggable(bool $value = true): self
    {
        return $this->set('draggable', $value);
    }

    /**
     * 是否可新增
     *
     * @param bool $value
     * @return self
     */
    public function addable(bool $value = true): self
    {
        return $this->set('addable', $value);
    }

    /**
     * 是否可删除
     *
     * @param bool $value
     * @return self
     */
    public function removable(bool $value = true): self
    {
        return $this->set('removable', $value);
    }

    /**
     * 新增按钮 CSS 类名
     *
     * @param string $value
     * @return self
     */
    public function addButtonClassName(string $value = ''): self
    {
        return $this->set('addButtonClassName', $value);
    }

    /**
     * 值元素 CSS 类名
     *
     * @param string $value
     * @return self
     */
    public function itemClassName(string $value = ''): self
    {
        return $this->set('itemClassName', $value);
    }

    /**
     * 值包裹元素 CSS 类名
     *
     * @param string $value
     * @return self
     */
    public function itemsClassName(string $value = ''): self
    {
        return $this->set('itemsClassName', $value);
    }

    /**
     * 子表单配置，同 [Form](./index)
     *
     * @param mixed $value
     * @return self
     */
    public function form(mixed $value = null): self
    {
        return $this->set('form', $value);
    }

    /**
     * 自定义新增一项的文本
     *
     * @param string $value
     * @return self
     */
    public function addButtonText(string $value = ''): self
    {
        return $this->set('addButtonText', $value);
    }

    /**
     * 是否在左下角显示报错信息
     *
     * @param bool $value
     * @return self
     */
    public function showErrorMsg(bool $value = true): self
    {
        return $this->set('showErrorMsg', $value);
    }
}
