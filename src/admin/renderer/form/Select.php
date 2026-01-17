<?php
namespace warm\admin\renderer\form;

use warm\admin\renderer\BaseRenderer;
use warm\admin\renderer\trait\OnEvent;
use warm\admin\renderer\trait\Options;

/**
 * Select
 *
 * @see https://aisuda.bce.baidu.com/amis/zh-CN/components/select
 */
class Select extends BaseRenderer
{
    use Options;
    use OnEvent;

    public string $type = 'select';

    /**
     * [选项组](./options#%E9%9D%99%E6%80%81%E9%80%89%E9%A1%B9%E7%BB%84-options)
     *
     * @param mixed $value
     * @return self
     */
    public function options(mixed $value = []): static
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
     * [自动提示补全](./options#%E8%87%AA%E5%8A%A8%E8%A1%A5%E5%85%A8-autocomplete)
     *
     * @param mixed $value
     * @return self
     */
    public function autoComplete(mixed $value = null): static
    {
        return $this->set('autoComplete', $value);
    }

    /**
     * [拼接符](./options#%E6%8B%BC%E6%8E%A5%E7%AC%A6-delimiter)
     *
     * @param string $value
     * @return self
     */
    public function delimiter(string $value = 'false'): static
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
     * 是否支持全选
     *
     * @param bool $value
     * @return self
     */
    public function checkAll(bool $value = true): static
    {
        return $this->set('checkAll', $value);
    }

    /**
     * 全选的文字
     *
     * @param string $value
     * @return self
     */
    public function checkAllLabel(string $value = '全选'): static
    {
        return $this->set('checkAllLabel', $value);
    }

    /**
     * 有检索时只全选检索命中的项
     *
     * @param bool $value
     * @return self
     */
    public function checkAllBySearch(bool $value = true): static
    {
        return $this->set('checkAllBySearch', $value);
    }

    /**
     * 默认是否全选
     *
     * @param bool $value
     * @return self
     */
    public function defaultCheckAll(bool $value = true): static
    {
        return $this->set('defaultCheckAll', $value);
    }

    /**
     * [新增选项](./options#%E5%89%8D%E7%AB%AF%E6%96%B0%E5%A2%9E-creatable)
     *
     * @param bool $value
     * @return self
     */
    public function creatable(bool $value = true): static
    {
        return $this->set('creatable', $value);
    }

    /**
     * [多选](./options#多选-multiple)
     *
     * @param bool $value
     * @return self
     */
    public function multiple(bool $value = true): static
    {
        return $this->set('multiple', $value);
    }

    /**
     * [检索](./options#检索-searchable)
     *
     * @param bool $value
     * @return self
     */
    public function searchable(bool $value = true): static
    {
        return $this->set('searchable', $value);
    }

    /**
     * 
     *
     * @param string $value
     * @return self
     */
    public function filterOption(string $value = '(options: Option[], inputValue: string, option: {keys: string[]}) => Option[]'): static
    {
        return $this->set('filterOption', $value);
    }

    /**
     * [新增选项](./options#%E6%96%B0%E5%A2%9E%E9%80%89%E9%A1%B9)
     *
     * @param string $value
     * @return self
     */
    public function createBtnLabel(string $value = '新增选项'): static
    {
        return $this->set('createBtnLabel', $value);
    }

    /**
     * [自定义新增表单项](./options#%E8%87%AA%E5%AE%9A%E4%B9%89%E6%96%B0%E5%A2%9E%E8%A1%A8%E5%8D%95%E9%A1%B9-addcontrols)
     *
     * @param mixed $value
     * @return self
     */
    public function addControls(mixed $value = null): static
    {
        return $this->set('addControls', $value);
    }

    /**
     * [配置新增选项接口](./options#%E9%85%8D%E7%BD%AE%E6%96%B0%E5%A2%9E%E6%8E%A5%E5%8F%A3-addapi)
     *
     * @param mixed $value
     * @return self
     */
    public function addApi(mixed $value = null): static
    {
        return $this->set('addApi', $value);
    }

    /**
     * [编辑选项](./options#%E5%89%8D%E7%AB%AF%E7%BC%96%E8%BE%91-editable)
     *
     * @param bool $value
     * @return self
     */
    public function editable(bool $value = true): static
    {
        return $this->set('editable', $value);
    }

    /**
     * [自定义编辑表单项](./options#%E8%87%AA%E5%AE%9A%E4%B9%89%E7%BC%96%E8%BE%91%E8%A1%A8%E5%8D%95%E9%A1%B9-editcontrols)
     *
     * @param mixed $value
     * @return self
     */
    public function editControls(mixed $value = null): static
    {
        return $this->set('editControls', $value);
    }

    /**
     * [配置编辑选项接口](./options#%E9%85%8D%E7%BD%AE%E7%BC%96%E8%BE%91%E6%8E%A5%E5%8F%A3-editapi)
     *
     * @param mixed $value
     * @return self
     */
    public function editApi(mixed $value = null): static
    {
        return $this->set('editApi', $value);
    }

    /**
     * [删除选项](./options#%E5%88%A0%E9%99%A4%E9%80%89%E9%A1%B9)
     *
     * @param bool $value
     * @return self
     */
    public function removable(bool $value = true): static
    {
        return $this->set('removable', $value);
    }

    /**
     * [配置删除选项接口](./options#%E9%85%8D%E7%BD%AE%E5%88%A0%E9%99%A4%E6%8E%A5%E5%8F%A3-deleteapi)
     *
     * @param mixed $value
     * @return self
     */
    public function deleteApi(mixed $value = null): static
    {
        return $this->set('deleteApi', $value);
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
     * 支持配置自定义菜单
     *
     * @param string $value
     * @return self
     */
    public function menuTpl(string $value = ''): static
    {
        return $this->set('menuTpl', $value);
    }

    /**
     * 隐藏已选选项
     *
     * @param bool $value
     * @return self
     */
    public function hideSelected(bool $value = true): static
    {
        return $this->set('hideSelected', $value);
    }

    /**
     * 移动端浮层类名
     *
     * @param string $value
     * @return self
     */
    public function mobileClassName(string $value = ''): static
    {
        return $this->set('mobileClassName', $value);
    }

    /**
     * 可选：`group`、`table`、`tree`、`chained`、`associated`。分别为：列表形式、表格形式、树形选择形式、级联选择形式，关联选择形式（与级联选择的区别在于，级联是无限极，而关联只有一级，关联左边可以是个 tree）。
     *
     * @param string $value
     * @return self
     */
    public function selectMode(string $value = ''): static
    {
        return $this->set('selectMode', $value);
    }

    /**
     * 如果不设置将采用 `selectMode` 的值，可以单独配置，参考 `selectMode`，决定搜索结果的展示形式。
     *
     * @param string $value
     * @return self
     */
    public function searchResultMode(string $value = ''): static
    {
        return $this->set('searchResultMode', $value);
    }

    /**
     * 当展示形式为 `table` 可以用来配置展示哪些列，跟 table 中的 columns 配置相似，只是只有展示功能。
     *
     * @param array $value
     * @return self
     */
    public function columns(array $value = []): static
    {
        return $this->set('columns', $value);
    }

    /**
     * 当展示形式为 `associated` 时用来配置左边的选项集。
     *
     * @param array $value
     * @return self
     */
    public function leftOptions(array $value = []): static
    {
        return $this->set('leftOptions', $value);
    }

    /**
     * 当展示形式为 `associated` 时用来配置左边的选择形式，支持 `list` 或者 `tree`。默认为 `list`。
     *
     * @param string $value
     * @return self
     */
    public function leftMode(string $value = ''): static
    {
        return $this->set('leftMode', $value);
    }

    /**
     * 当展示形式为 `associated` 时用来配置右边的选择形式，可选：`list`、`table`、`tree`、`chained`。
     *
     * @param string $value
     * @return self
     */
    public function rightMode(string $value = ''): static
    {
        return $this->set('rightMode', $value);
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

    /**
     * 选项 CSS 类名
     *
     * @param string $value
     * @return self
     */
    public function optionClassName(string $value = ''): static
    {
        return $this->set('optionClassName', $value);
    }

    /**
     * 弹层挂载位置选择器，会通过`querySelector`获取
     *
     * @param string $value
     * @return self
     */
    public function popOverContainerSelector(string $value = ''): static
    {
        return $this->set('popOverContainerSelector', $value);
    }

    /**
     * 是否展示清空图标
     *
     * @param bool $value
     * @return self
     */
    public function clearable(bool $value = true): static
    {
        return $this->set('clearable', $value);
    }

    /**
     * "center" \
     *
     * @param mixed $value
     * @return self
     */
    public function overlay(mixed $value = null): static
    {
        return $this->set('overlay', $value);
    }

    /**
     * 选项值与选项组不匹配时选项值是否飘红
     *
     * @param bool $value
     * @return self
     */
    public function showInvalidMatch(bool $value = true): static
    {
        return $this->set('showInvalidMatch', $value);
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
}
