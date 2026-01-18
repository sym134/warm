<?php

namespace warm\admin\renderer\expand;

use warm\admin\renderer\trait\NameAndLabel;
use warm\admin\renderer\BaseRenderer;

/**
 * 表格列，不指定类型时默认为文本类型。
 *
 * @author slowlyo
 * @version 6.13.0
 */
class TableColumn extends BaseRenderer
{
    use NameAndLabel;

    public function placeholder(mixed $value = ''): static
    {
        return $this->set('placeholder', $value);
    }

    /**
     * 列头单元格内容
     *
     * @param array $value
     * @return TableColumn
     */
    public function items(mixed $value = []): static
    {
        return $this->set('items', $value);
    }

    /**
     * 列对齐方式 可选值: left | right | center | justify
     *
     * @param string $value
     * @return TableColumn
     */
    public function align(string $value = ''): static
    {
        return $this->set('align', $value);
    }

    /**
     * 结合表格的 footable 一起使用。 填写 *、xs、sm、md、lg指定 footable 的触发条件，可以填写多个用空格隔开 可选值: * | xs | sm | md | lg
     *
     * @param string $value
     * @return TableColumn
     */
    public function breakpoint(string $value = ''): static
    {
        return $this->set('breakpoint', $value);
    }

    /**
     * 表格列单元格是否可以获取父级数据域值，默认为true，该配置对当前列内单元格生效
     *
     * @param bool $value
     * @return TableColumn
     */
    public function canAccessSuperData(bool $value = true): static
    {
        return $this->set('canAccessSuperData', $value);
    }

    /**
     * 设置组件样式
     *
     * @param mixed $value
     * @return static
     */
    public function className(mixed $value): static
    {
        return $this->set('className', $value);
    }

    /**
     * 单元格样式表达式
     *
     * @param string $value
     * @return TableColumn
     */
    public function classNameExpr(string $value = ''): static
    {
        return $this->set('classNameExpr', $value);
    }

    /**
     * 配置点击复制功能 (配置点击复制功能)
     *
     * @param bool $value
     * @return TableColumn
     */
    public function copyable(bool $value = true)
    {
        return $this->set('copyable', $value);
    }

    /**
     *  列是否可过滤
     *
     * @param array $value
     * @return TableColumn
     */
    public function filterable(array $value): static
    {
        return $this->set('filterable', $value);
    }

    /**
     * 配置是否固定当前列 可选值: left | right | none
     *
     * @param string $value
     * @return TableColumn
     */
    public function fixed(string $value = '')
    {
        return $this->set('fixed', $value);
    }

    /**
     * 标题左右对齐方式 可选值: left | right | center | justify
     *
     * @param string $value
     * @return TableColumn
     */
    public function headerAlign(string $value = ''): static
    {
        return $this->set('headerAlign', $value);
    }

    /**
     * 单元格内部组件自定义样式 style作为单元格自定义样式的配置
     *
     * @param array $value
     * @return TableColumn
     */
    public function innerStyle(array $value = [])
    {
        return $this->set('innerStyle', $value);
    }

    /**
     * 列标题
     *
     * @param mixed $value
     * @return $this
     */
    public function label(mixed $value = ''): static
    {
        return $this->set('label', $value);
    }

    /**
     * 列头样式表
     *
     * @param string $value
     * @return TableColumn
     */
    public function labelClassName(string $value = ''): static
    {
        return $this->set('labelClassName', $value);
    }

    /**
     * 用来控制从第几行开始懒渲染行，用来渲染大表格时有用
     *
     * @param int $value
     * @return TableColumn
     */
    public function lazyRenderAfter(int $value): static
    {
        return $this->set('lazyRenderAfter', $value);
    }

    /**
     * 绑定字段名
     *
     * @param string $value
     * @return $this
     */
    public function name(string $value = ''): static
    {
        return $this->set('name', $value);
    }

    /**
     * 配置查看详情功能 (配置查看详情功能)
     *
     * @param array|string $value
     * @return TableColumn
     */
    public function popOver(array|string $value = ''): static
    {
        return $this->set('popOver', $value);
    }

    /**
     * 配置快速编辑功能 (配置快速编辑功能)
     *
     * @param mixed $value
     * @return TableColumn
     */
    public function quickEdit(mixed $value): static
    {
        return $this->set('quickEdit', $value);
    }

    /**
     * 作为表单项时，可以单独配置编辑时的快速编辑面板。 (作为表单项时，可以单独配置编辑时的快速编辑面板。)
     *
     * @param bool|array $value
     * @return TableColumn
     */
    public function quickEditOnUpdate(bool|array $value): static
    {
        return $this->set('quickEditOnUpdate', $value);
    }

    /**
     * 提示信息 (提示信息)
     *
     * @param string $value
     * @return TableColumn
     */
    public function remark(string $value = ''): static
    {
        return $this->set('remark', $value);
    }

    /**
     * 是否可快速搜索
     *
     * @param bool $value
     * @return TableColumn
     */
    public function searchable(bool $value = true): static
    {
        return $this->set('searchable', $value);
    }

    /**
     * 配置是否可以排序
     *
     * @param bool $value
     * @return TableColumn
     */
    public function sortable(bool $value = true): static
    {
        return $this->set('sortable', $value);
    }

    /**
     * toggled
     *
     * @param bool $value
     * @return TableColumn
     */
    public function toggled(bool $value = true): static
    {
        return $this->set('toggled', $value);
    }

    /**
     * 设置 TableColumn 的类型
     *
     * @param string $value
     * @return TableColumn
     */
    public function type(string $value = ''): static
    {
        return $this->set('type', $value);
    }

    /**
     * 是否唯一, 只有在 inputTable 里面才有用
     *
     * @param bool $value
     * @return TableColumn
     */
    public function unique(bool $value = true): static
    {
        return $this->set('unique', $value);
    }

    /**
     * 列垂直对齐方式 可选值: top | middle | bottom
     *
     * @param string $value
     * @return TableColumn
     */
    public function vAlign(string $value = ''): static
    {
        return $this->set('vAlign', $value);
    }

    /**
     * 默认值, 只有在 inputTable 里面才有用
     *
     * @param mixed $value
     * @return TableColumn
     */
    public function value(mixed $value = ''): static
    {
        return $this->set('value', $value);
    }

    /**
     * 列宽度
     *
     * @param string|int $value
     * @return TableColumn
     */
    public function width(string|int $value = ''): static
    {
        return $this->set('width', $value);
    }

    /**
     * 文本超出时显示省略号 可选值: default | ellipsis | wrap
     *
     * @param string $value
     * @return static
     */
    public function textOverflow(string $value = 'default'): static
    {
        return $this->set('textOverflow', $value);
    }

    /**
     * 文本对齐方式 可选值: "start", "flex-start", "center", "end", "flex-end", "space-around", "space-between", "space-evenly"
     *
     * @param string $string
     * @return static
     */
    public function justify(string $string): static
    {
        return $this->set('justify', $string);
    }

}
