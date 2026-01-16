<?php

namespace warm\admin\renderer\form;

/**
 * Trait FormItemTrait
 * 
 * 包含普通表单项的通用属性和方法
 */
trait FormItemTrait
{
    /**
     * 表单最外层类名
     *
     * @param string $value
     * @return self
     */
    public function className(string $value = ''): self
    {
        return $this->set('className', $value);
    }

    /**
     * 表单控制器类名
     *
     * @param string $value
     * @return self
     */
    public function inputClassName(string $value = ''): self
    {
        return $this->set('inputClassName', $value);
    }

    /**
     * label 的类名
     *
     * @param string $value
     * @return self
     */
    public function labelClassName(string $value = ''): self
    {
        return $this->set('labelClassName', $value);
    }

    /**
     * 字段名，指定该表单项提交时的 key
     *
     * @param string $value
     * @return self
     */
    public function name(string $value = ''): self
    {
        return $this->set('name', $value);
    }

    /**
     * 表单默认值
     *
     * @param string $value
     * @return self
     */
    public function value(string $value = ''): self
    {
        return $this->set('value', $value);
    }

    /**
     * 表单项标签
     *
     * @param mixed $value
     * @return self
     */
    public function label(mixed $value = null): self
    {
        return $this->set('label', $value);
    }

    /**
     * `"right"`
     *
     * @param mixed $value
     * @return self
     */
    public function labelAlign(mixed $value = null): self
    {
        return $this->set('labelAlign', $value);
    }

    /**
     * 表单项标签描述
     *
     * @param mixed $value
     * @return self
     */
    public function labelRemark(mixed $value = null): self
    {
        return $this->set('labelRemark', $value);
    }

    /**
     * 表单项描述
     *
     * @param mixed $value
     * @return self
     */
    public function description(mixed $value = null): self
    {
        return $this->set('description', $value);
    }

    /**
     * 表单项描述
     *
     * @param string $value
     * @return self
     */
    public function placeholder(string $value = ''): self
    {
        return $this->set('placeholder', $value);
    }

    /**
     * 是否为 内联 模式
     *
     * @param bool $value
     * @return self
     */
    public function inline(bool $value = true): self
    {
        return $this->set('inline', $value);
    }

    /**
     * 通过配置 false 可以及时获取所有表单里面的数据，否则可能会有不同步
     *
     * @param bool $value
     * @return self
     */
    public function strictMode(bool $value = true): self
    {
        return $this->set('strictMode', $value);
    }

    /**
     * 是否该表单项值发生变化时就提交当前表单。
     *
     * @param bool $value
     * @return self
     */
    public function submitOnChange(bool $value = true): self
    {
        return $this->set('submitOnChange', $value);
    }

    /**
     * 当前表单项是否是禁用状态
     *
     * @param bool $value
     * @return self
     */
    public function disabled(bool $value = true): self
    {
        return $this->set('disabled', $value);
    }

    /**
     * 当前表单项是否禁用的条件
     *
     * @param mixed $value
     * @return self
     */
    public function disabledOn(mixed $value = null): self
    {
        return $this->set('disabledOn', $value);
    }

    /**
     * 当前表单项是否禁用的条件
     *
     * @param mixed $value
     * @return self
     */
    public function visible(mixed $value = null): self
    {
        return $this->set('visible', $value);
    }

    /**
     * 当前表单项是否禁用的条件
     *
     * @param mixed $value
     * @return self
     */
    public function visibleOn(mixed $value = null): self
    {
        return $this->set('visibleOn', $value);
    }

    /**
     * 是否为必填。
     *
     * @param bool $value
     * @return self
     */
    public function required(bool $value = true): self
    {
        return $this->set('required', $value);
    }

    /**
     * 通过[表达式](../Types.md#表达式)来配置当前表单项是否为必填。
     *
     * @param mixed $value
     * @return self
     */
    public function requiredOn(mixed $value = null): self
    {
        return $this->set('requiredOn', $value);
    }

    /**
     * 表单项值格式验证，支持设置多个，多个规则用英文逗号隔开。
     *
     * @param mixed $value
     * @return self
     */
    public function validations(mixed $value = null): self
    {
        return $this->set('validations', $value);
    }

    /**
     * 表单校验接口
     *
     * @param mixed $value
     * @return self
     */
    public function validateApi(mixed $value = null): self
    {
        return $this->set('validateApi', $value);
    }

    /**
     * 数据录入配置，自动填充或者参照录入
     *
     * @param mixed $value
     * @return self
     */
    public function autoFill(mixed $value = null): self
    {
        return $this->set('autoFill', $value);
    }

    /**
     * `2.4.0` 当前表单项是否是静态展示，目前支持静[支持静态展示的表单项](#支持静态展示的表单项)
     *
     * @param bool $value
     * @return self
     */
    public function static(bool $value = true): self
    {
        return $this->set('static', $value);
    }

    /**
     * `2.4.0` 静态展示时的类名
     *
     * @param string $value
     * @return self
     */
    public function staticClassName(string $value = ''): self
    {
        return $this->set('staticClassName', $value);
    }

    /**
     * `2.4.0` 静态展示时的 Label 的类名
     *
     * @param string $value
     * @return self
     */
    public function staticLabelClassName(string $value = ''): self
    {
        return $this->set('staticLabelClassName', $value);
    }

    /**
     * `2.4.0` 静态展示时的 value 的类名
     *
     * @param string $value
     * @return self
     */
    public function staticInputClassName(string $value = ''): self
    {
        return $this->set('staticInputClassName', $value);
    }

    /**
     * `2.4.0` 自定义静态展示方式
     *
     * @param mixed $value
     * @return self
     */
    public function staticSchema(mixed $value = null): self
    {
        return $this->set('staticSchema', $value);
    }
}
