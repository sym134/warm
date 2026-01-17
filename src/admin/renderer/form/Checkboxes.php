<?php
namespace warm\admin\renderer\form;

use warm\admin\renderer\BaseRenderer;
use warm\admin\renderer\trait\FormItem;
use warm\admin\renderer\trait\OnEvent;

/**
 * Checkboxes
 *
 * @see https://aisuda.bce.baidu.com/amis/zh-CN/components/checkboxes
 */
class Checkboxes extends BaseRenderer
{

    use FormItem;
    use OnEvent;

    public string $type = 'checkboxes';

    /**
     * [选项组](./#%E9%9D%99%E6%80%81%E9%80%89%E9%A1%B9%E7%BB%84-options)
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
     * @param string $value
     * @return self
     */
    public function delimiter(string $value = ','): static
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
     * 选项按几列显示，默认为一列
     *
     * @param int|float $value
     * @return self
     */
    public function columnsCount(int|float $value = 1): static
    {
        return $this->set('columnsCount', $value);
    }

    /**
     * 支持自定义选项渲染
     *
     * @param string $value
     * @return self
     */
    public function menuTpl(string $value = ''): static
    {
        return $this->set('menuTpl', $value);
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
     * 是否显示为一行
     *
     * @param bool $value
     * @return self
     */
    public function inline(bool $value = true): static
    {
        return $this->set('inline', $value);
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
     * `default`
     *
     * @param mixed $value
     * @return self
     */
    public function optionType(mixed $value = null): static
    {
        return $this->set('optionType', $value);
    }

    /**
     * 选项样式类名
     *
     * @param string $value
     * @return self
     */
    public function itemClassName(string $value = ''): static
    {
        return $this->set('itemClassName', $value);
    }

    /**
     * 选项标签样式类名
     *
     * @param string $value
     * @return self
     */
    public function labelClassName(string $value = ''): static
    {
        return $this->set('labelClassName', $value);
    }
}
