<?php
namespace warm\admin\renderer\form;

use warm\admin\renderer\BaseRenderer;
use warm\admin\renderer\form\FormItemTrait;

/**
 * Combo
 *
 * @see https://aisuda.bce.baidu.com/amis/zh-CN/components/combo
 */
class Combo extends BaseRenderer
{
    use FormItemTrait;

    public string $type = 'combo';

    /**
     * 单组表单项的类名
     *
     * @param string $value
     * @return self
     */
    public function formClassName(string $value = ''): self
    {
        return $this->set('formClassName', $value);
    }

    /**
     * 组合展示的表单项
     *
     * @param mixed $value
     * @return self
     */
    public function items(mixed $value = null): self
    {
        return $this->set('items', $value);
    }

    /**
     * 单组表单项是否显示边框
     *
     * @param bool $value
     * @return self
     */
    public function noBorder(bool $value = true): self
    {
        return $this->set('noBorder', $value);
    }

    /**
     * 单组表单项初始值
     *
     * @param array $value
     * @return self
     */
    public function scaffold(array $value = []): self
    {
        return $this->set('scaffold', $value);
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
     * 配置正整数后支持分页展示
     *
     * @param int|float $value
     * @return self
     */
    public function perPage(int|float $value = 0): self
    {
        return $this->set('perPage', $value);
    }

    /**
     * 默认是横着展示一排，设置以后竖着展示
     *
     * @param bool $value
     * @return self
     */
    public function multiLine(bool $value = true): self
    {
        return $this->set('multiLine', $value);
    }

    /**
     * 最少添加的条数，`2.4.1` 版本后支持变量
     *
     * @param int|float $value
     * @return self
     */
    public function minLength(int|float $value = 0): self
    {
        return $this->set('minLength', $value);
    }

    /**
     * 最多添加的条数，`2.4.1` 版本后支持变量
     *
     * @param int|float $value
     * @return self
     */
    public function maxLength(int|float $value = 0): self
    {
        return $this->set('maxLength', $value);
    }

    /**
     * 是否将结果扁平化(去掉 name),只有当 items 的 length 为 1 且 multiple 为 true 的时候才有效。
     *
     * @param bool $value
     * @return self
     */
    public function flat(bool $value = true): self
    {
        return $this->set('flat', $value);
    }

    /**
     * 默认为 `true` 当扁平化开启的时候，是否用分隔符的形式发送给后端，否则采用 array 的方式。
     *
     * @param bool $value
     * @return self
     */
    public function joinValues(bool $value = true): self
    {
        return $this->set('joinValues', $value);
    }

    /**
     * 当扁平化开启并且 joinValues 为 true 时，用什么分隔符。
     *
     * @param string $value
     * @return self
     */
    public function delimiter(string $value = 'false'): self
    {
        return $this->set('delimiter', $value);
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
     * 在顶部添加
     *
     * @param bool $value
     * @return self
     */
    public function addattop(bool $value = true): self
    {
        return $this->set('addattop', $value);
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
     * 如果配置了，则删除前会发送一个 api，请求成功才完成删除
     *
     * @param mixed $value
     * @return self
     */
    public function deleteApi(mixed $value = null): self
    {
        return $this->set('deleteApi', $value);
    }

    /**
     * 当配置 `deleteApi` 才生效！删除时用来做用户确认
     *
     * @param string $value
     * @return self
     */
    public function deleteConfirmText(string $value = '确认要删除？'): self
    {
        return $this->set('deleteConfirmText', $value);
    }

    /**
     * 是否可以拖动排序, 需要注意的是当启用拖动排序的时候，会多一个\$id 字段
     *
     * @param bool $value
     * @return self
     */
    public function draggable(bool $value = true): self
    {
        return $this->set('draggable', $value);
    }

    /**
     * 可拖拽的提示文字
     *
     * @param string $value
     * @return self
     */
    public function draggableTip(string $value = ''): self
    {
        return $this->set('draggableTip', $value);
    }

    /**
     * 可选`normal`、`horizontal`、`inline`
     *
     * @param string $value
     * @return self
     */
    public function subFormMode(string $value = 'normal'): self
    {
        return $this->set('subFormMode', $value);
    }

    /**
     * 当 subFormMode 为 `horizontal` 时有用，用来控制 label 的展示占比
     *
     * @param array $value
     * @return self
     */
    public function subFormHorizontal(array $value = []): self
    {
        return $this->set('subFormHorizontal', $value);
    }

    /**
     * 没有成员时显示。
     *
     * @param string $value
     * @return self
     */
    public function placeholder(string $value = ''): self
    {
        return $this->set('placeholder', $value);
    }

    /**
     * 指定是否可以自动获取上层的数据并映射到表单项上
     *
     * @param bool $value
     * @return self
     */
    public function canAccessSuperData(bool $value = true): self
    {
        return $this->set('canAccessSuperData', $value);
    }

    /**
     * 数组的形式包含所有条件的渲染类型，单个数组内的`test` 为判断条件，数组内的`items`为符合该条件后渲染的`schema`
     *
     * @param array $value
     * @return self
     */
    public function conditions(array $value = []): self
    {
        return $this->set('conditions', $value);
    }

    /**
     * 是否可切换条件，配合`conditions`使用
     *
     * @param bool $value
     * @return self
     */
    public function typeSwitchable(bool $value = true): self
    {
        return $this->set('typeSwitchable', $value);
    }

    /**
     * 默认为严格模式，设置为 false 时，当其他表单项更新是，里面的表单项也可以及时获取，否则不会。
     *
     * @param bool $value
     * @return self
     */
    public function strictMode(bool $value = true): self
    {
        return $this->set('strictMode', $value);
    }

    /**
     * 配置同步字段。只有 `strictMode` 为 `false` 时有效。如果 Combo 层级比较深，底层的获取外层的数据可能不同步。但是给 combo 配置这个属性就能同步下来。输入格式：`["os"]`
     *
     * @param array $value
     * @return self
     */
    public function syncFields(array $value = []): self
    {
        return $this->set('syncFields', $value);
    }

    /**
     * 允许为空，如果子表单项里面配置验证器，且又是单条模式。可以允许用户选择清空（不填）。
     *
     * @param bool $value
     * @return self
     */
    public function nullable(bool $value = true): self
    {
        return $this->set('nullable', $value);
    }

    /**
     * 单组 CSS 类
     *
     * @param string $value
     * @return self
     */
    public function itemClassName(string $value = ''): self
    {
        return $this->set('itemClassName', $value);
    }

    /**
     * 组合区域 CSS 类
     *
     * @param string $value
     * @return self
     */
    public function itemsWrapperClassName(string $value = ''): self
    {
        return $this->set('itemsWrapperClassName', $value);
    }

    /**
     * 只有当`removable`为 `true` 时有效; 如果为`string`则为按钮的文本；如果为`Button`则根据配置渲染删除按钮。
     *
     * @param mixed $value
     * @return self
     */
    public function deleteBtn(mixed $value = null): self
    {
        return $this->set('deleteBtn', $value);
    }

    /**
     * 可新增自定义配置渲染新增按钮，在`tabsMode: true`下不生效。
     *
     * @param mixed $value
     * @return self
     */
    public function addBtn(mixed $value = null): self
    {
        return $this->set('addBtn', $value);
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
     * 新增按钮文字
     *
     * @param string $value
     * @return self
     */
    public function addButtonText(string $value = '新增'): self
    {
        return $this->set('addButtonText', $value);
    }
}
