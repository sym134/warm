<?php
namespace warm\admin\renderer\form;

use warm\admin\renderer\BaseRenderer;
use warm\admin\renderer\trait\FormItem;

/**
 * InputTree 树形选择器
 *
 * @see https://aisuda.bce.baidu.com/amis/zh-CN/components/input-tree
 */
class InputTree extends BaseRenderer
{
    use FormItem;

    public string $type = 'input-tree';

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
     * 是否多选
     *
     * @param bool $value
     * @return self
     */
    public function multiple(bool $value = true): static
    {
        return $this->set('multiple', $value);
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
     * 图标值字段
     *
     * @param string $value
     * @return self
     */
    public function iconField(string $value = 'icon'): static
    {
        return $this->set('iconField', $value);
    }

    /**
     * 懒加载字段
     *
     * @param string $value
     * @return self
     */
    public function deferField(string $value = 'defer'): static
    {
        return $this->set('deferField', $value);
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
     * 是否可检索
     *
     * @param bool $value
     * @return self
     */
    public function searchable(bool $value = true): static
    {
        return $this->set('searchable', $value);
    }

    /**
     * 如果想要显示个顶级节点，请设置为 `false`
     *
     * @param bool $value
     * @return self
     */
    public function hideRoot(bool $value = true): static
    {
        return $this->set('hideRoot', $value);
    }

    /**
     * 当 `hideRoot` 不为 `false` 时有用，用来设置顶级节点的文字。
     *
     * @param bool $value
     * @return self
     */
    public function rootLabel(bool $value = true): static
    {
        return $this->set('rootLabel', $value);
    }

    /**
     * 是否显示图标
     *
     * @param bool $value
     * @return self
     */
    public function showIcon(bool $value = true): static
    {
        return $this->set('showIcon', $value);
    }

    /**
     * 是否显示单选按钮，`multiple` 为 `false` 是有效。
     *
     * @param bool $value
     * @return self
     */
    public function showRadio(bool $value = true): static
    {
        return $this->set('showRadio', $value);
    }

    /**
     * 是否显示树层级展开线
     *
     * @param bool $value
     * @return self
     */
    public function showOutline(bool $value = true): static
    {
        return $this->set('showOutline', $value);
    }

    /**
     * 设置是否默认展开所有层级。
     *
     * @param bool $value
     * @return self
     */
    public function initiallyOpen(bool $value = true): static
    {
        return $this->set('initiallyOpen', $value);
    }

    /**
     * 设置默认展开的级数，只有`initiallyOpen`不是`true`时生效。
     *
     * @param int|float $value
     * @return self
     */
    public function unfoldedLevel(int|float $value = 1): static
    {
        return $this->set('unfoldedLevel', $value);
    }

    /**
     * 当选中父节点时级联选择子节点。
     *
     * @param bool $value
     * @return self
     */
    public function autoCheckChildren(bool $value = true): static
    {
        return $this->set('autoCheckChildren', $value);
    }

    /**
     * autoCheckChildren 为 true 时生效；默认行为：子节点禁用，值只包含父节点值；设置为 true 时，子节点可反选，值包含父子节点值。
     *
     * @param bool $value
     * @return self
     */
    public function cascade(bool $value = true): static
    {
        return $this->set('cascade', $value);
    }

    /**
     * cascade 为 false 时生效，选中父节点时，值里面将包含父子节点的值，否则只会保留父节点的值。
     *
     * @param bool $value
     * @return self
     */
    public function withChildren(bool $value = true): static
    {
        return $this->set('withChildren', $value);
    }

    /**
     * autoCheckChildren 为 true 时生效，不受 cascade 影响；onlyChildren 为 true，ui 行为级联选中子节点，子节点可反选，值只包含子节点的值。
     *
     * @param bool $value
     * @return self
     */
    public function onlyChildren(bool $value = true): static
    {
        return $this->set('onlyChildren', $value);
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
     * 是否可以创建顶级节点
     *
     * @param bool $value
     * @return self
     */
    public function rootCreatable(bool $value = true): static
    {
        return $this->set('rootCreatable', $value);
    }

    /**
     * 创建顶级节点的悬浮提示
     *
     * @param string $value
     * @return self
     */
    public function rootCreateTip(string $value = '添加一级节点'): static
    {
        return $this->set('rootCreateTip', $value);
    }

    /**
     * 最少选中的节点数
     *
     * @param int|float $value
     * @return self
     */
    public function minLength(int|float $value = 0): static
    {
        return $this->set('minLength', $value);
    }

    /**
     * 最多选中的节点数
     *
     * @param int|float $value
     * @return self
     */
    public function maxLength(int|float $value = 0): static
    {
        return $this->set('maxLength', $value);
    }

    /**
     * tree 控件最外层容器类名, 与 inputClassName 等价
     *
     * @param string $value
     * @return self
     */
    public function treeContainerClassName(string $value = ''): static
    {
        return $this->set('treeContainerClassName', $value);
    }

    /**
     * tree 组件层类名
     *
     * @param string $value
     * @return self
     */
    public function treeClassName(string $value = ''): static
    {
        return $this->set('treeClassName', $value);
    }

    /**
     * 是否开启节点路径模式
     *
     * @param bool $value
     * @return self
     */
    public function enableNodePath(bool $value = true): static
    {
        return $this->set('enableNodePath', $value);
    }

    /**
     * 节点路径的分隔符，`enableNodePath`为`true`时生效
     *
     * @param string $value
     * @return self
     */
    public function pathSeparator(string $value = '/'): static
    {
        return $this->set('pathSeparator', $value);
    }

    /**
     * 标签中需要高亮的字符，支持变量
     *
     * @param string $value
     * @return self
     */
    public function highlightTxt(string $value = ''): static
    {
        return $this->set('highlightTxt', $value);
    }

    /**
     * 每个选项的高度，用于虚拟渲染
     *
     * @param int|float $value
     * @return self
     */
    public function itemHeight(int|float $value = 32): static
    {
        return $this->set('itemHeight', $value);
    }

    /**
     * 在选项数量超过多少时开启虚拟渲染
     *
     * @param int|float $value
     * @return self
     */
    public function virtualThreshold(int|float $value = 100): static
    {
        return $this->set('virtualThreshold', $value);
    }

    /**
     * 选项自定义渲染 HTML 片段
     *
     * @param string $value
     * @return self
     */
    public function menuTpl(string $value = ''): static
    {
        return $this->set('menuTpl', $value);
    }

    /**
     * 是否为选项添加默认的前缀 Icon，父节点默认为`folder`，叶节点默认为`file`
     *
     * @param bool $value
     * @return self
     */
    public function enableDefaultIcon(bool $value = true): static
    {
        return $this->set('enableDefaultIcon', $value);
    }

    /**
     * 默认高度会有个 maxHeight，即超过一定高度就会内部滚动，如果希望自动增长请设置此属性
     *
     * @param bool $value
     * @return self
     */
    public function heightAuto(bool $value = true): static
    {
        return $this->set('heightAuto', $value);
    }

    /**
     * ''>`
     *
     * @param array $value
     * @return self
     */
    public function nodeBehavior(array $value = []): static
    {
        return $this->set('nodeBehavior', $value);
    }

    /**
     * 子节点取消时自动取消父节点的值，仅在多选且 cascade 为 true 时生效
     *
     * @param bool $value
     * @return self
     */
    public function autoCancelParent(bool $value = true): static
    {
        return $this->set('autoCancelParent', $value);
    }

    /**
     * 工具栏区域，仅开启检索时生效
     *
     * @param mixed $value
     * @return self
     */
    public function toolbar(mixed $value = null): static
    {
        return $this->set('toolbar', $value);
    }

    /**
     * 工具栏区域类名
     *
     * @param string $value
     * @return self
     */
    public function toolbarClassName(string $value = ''): static
    {
        return $this->set('toolbarClassName', $value);
    }

    /**
     * 节点操作栏区域
     *
     * @param mixed $value
     * @return self
     */
    public function itemActions(mixed $value = null): static
    {
        return $this->set('itemActions', $value);
    }
}
