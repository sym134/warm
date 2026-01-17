<?php

namespace warm\admin\renderer\trait;

/**
 * Trait FormItemTrait
 * 
 * 包含普通表单项的通用属性和方法
 */
trait FormItem
{
    /**
     * 是否禁用
     *
     * @param mixed $value
     * @return static
     */
    public function hiddenOn(mixed $value = null):static
    {
        return $this->set('hiddenOn', $value);
    }

    /**
     * 表单校验错误信息 ["isNumeric"=>"同学，请输入数字哈"]
     *
     * @param array $errors
     * @return static
     */
    public function validationErrors(array $errors=[]): static
    {
        return $this->set('validationErrors', $errors);
    }
    /**
     * 是否实时校验表单
     *
     * @param bool $value
     * @return static
     */
    public function validateOnChange(bool $value = true): static
    {
        return $this->set('validateOnChange', $value);
    }
    /**
     * 是否立即保存 -TableColumn中使用
     *
     * @param bool|array $value
     * @return static
     */
    public function saveImmediately(bool|array $value = true): static
    {
        return $this->set('saveImmediately', $value);
    }
    /**
     * 配置当前表单项展示模式 可选值: normal | inline | horizontal
     *
     * @param string $value
     * @return static
     *
     */
    public function mode(string $value = 'inline'):static
    {
        return $this->set('mode', $value);
    }

    /**
     * 设置组件样式
     *
     * @param mixed $value
     * @return $this
     */
    public function className(mixed $value): static
    {
        return $this->set('className', $value);
    }

    /**
     * 表单控制器类名
     *
     * @param string $value
     * @return static
     */
    public function inputClassName(string $value = ''): static
    {
        return $this->set('inputClassName', $value);
    }

    /**
     * label 的类名
     *
     * @param string $value
     * @return static
     */
    public function labelClassName(string $value = ''): static
    {
        return $this->set('labelClassName', $value);
    }

    /**
     * 字段名，指定该表单项提交时的 key
     *
     * @param string $value
     * @return static
     */
    public function name(string $value = ''): static
    {
        return $this->set('name', $value);
    }

    /**
     * 表单默认值
     *
     * @param string $value
     * @return static
     */
    public function value(string $value = ''): static
    {
        return $this->set('value', $value);
    }

    /**
     * 表单项标签
     *
     * @param mixed $value
     * @return static
     */
    public function label(mixed $value = null): static
    {
        return $this->set('label', $value);
    }

    /**
     * `"right"`
     *
     * @param mixed $value
     * @return static
     */
    public function labelAlign(mixed $value = null): static
    {
        return $this->set('labelAlign', $value);
    }

    /**
     * 表单项标签描述
     *
     * @param mixed $value
     * @return static
     */
    public function labelRemark(mixed $value = null): static
    {
        return $this->set('labelRemark', $value);
    }

    /**
     * 表单项描述
     *
     * @param mixed $value
     * @return static
     */
    public function description(mixed $value = null): static
    {
        return $this->set('description', $value);
    }

    /**
     * 表单项描述
     *
     * @param string $value
     * @return static
     */
    public function placeholder(string $value = ''): static
    {
        return $this->set('placeholder', $value);
    }

    /**
     * 是否为 内联 模式
     *
     * @param bool $value
     * @return static
     */
    public function inline(bool $value = true): static
    {
        return $this->set('inline', $value);
    }

    /**
     * 通过配置 false 可以及时获取所有表单里面的数据，否则可能会有不同步
     *
     * @param bool $value
     * @return static
     */
    public function strictMode(bool $value = true): static
    {
        return $this->set('strictMode', $value);
    }

    /**
     * 是否该表单项值发生变化时就提交当前表单。
     *
     * @param bool $value
     * @return static
     */
    public function submitOnChange(bool $value = true): static
    {
        return $this->set('submitOnChange', $value);
    }

    /**
     * 当前表单项是否是禁用状态
     *
     * @param bool $value
     * @return static
     */
    public function disabled(bool $value = true): static
    {
        return $this->set('disabled', $value);
    }

    /**
     * 当前表单项是否禁用的条件
     *
     * @param mixed $value
     * @return static
     */
    public function disabledOn(mixed $value = null): static
    {
        return $this->set('disabledOn', $value);
    }

    /**
     * 当前表单项是否禁用的条件
     *
     * @param mixed $value
     * @return static
     */
    public function visible(mixed $value = true): static
    {
        return $this->set('visible', $value);
    }

    /**
     * 当前表单项是否隐藏
     *
     * @param bool $value
     * @return static
     */
    public function hidden(bool $value = true):static
    {
        return $this->set('hidden', $value);
    }

    /**
     * 当前表单项是否禁用的条件
     *
     * @param mixed $value
     * @return static
     */
    public function visibleOn(mixed $value = null): static
    {
        return $this->set('visibleOn', $value);
    }

    /**
     * 是否为必填。
     *
     * @param bool $value
     * @return static
     */
    public function required(bool $value = true): static
    {
        return $this->set('required', $value);
    }

    /**
     * 通过[表达式](../Types.md#表达式)来配置当前表单项是否为必填。
     *
     * @param mixed $value
     * @return static
     */
    public function requiredOn(mixed $value = null): static
    {
        return $this->set('requiredOn', $value);
    }

    /**
     * 表单项值格式验证，支持设置多个，多个规则用英文逗号隔开。
     *
     * @param mixed $value
     * @return static
     */
    public function validations(mixed $value = null): static
    {
        return $this->set('validations', $value);
    }

    /**
     * 表单校验接口
     *
     * @param mixed $value
     * @return static
     */
    public function validateApi(mixed $value = null): static
    {
        return $this->set('validateApi', $value);
    }

    /**
     * 数据录入配置，自动填充或者参照录入
     *
     * @param mixed $value
     * @return static
     */
    public function autoFill(mixed $value = null): static
    {
        return $this->set('autoFill', $value);
    }

    /**
     * `2.4.0` 当前表单项是否是静态展示，目前支持静[支持静态展示的表单项](#支持静态展示的表单项)
     *
     * @param bool $value
     * @return static
     */
    public function static(bool $value = true): static
    {
        return $this->set('static', $value);
    }

    /**
     * `2.4.0` 静态展示时的类名
     *
     * @param string $value
     * @return static
     */
    public function staticClassName(string $value = ''): static
    {
        return $this->set('staticClassName', $value);
    }

    /**
     * `2.4.0` 静态展示时的 Label 的类名
     *
     * @param string $value
     * @return static
     */
    public function staticLabelClassName(string $value = ''): static
    {
        return $this->set('staticLabelClassName', $value);
    }

    /**
     * `2.4.0` 静态展示时的 value 的类名
     *
     * @param string $value
     * @return static
     */
    public function staticInputClassName(string $value = ''): static
    {
        return $this->set('staticInputClassName', $value);
    }

    /**
     * `2.4.0` 自定义静态展示方式
     *
     * @param mixed $value
     * @return static
     */
    public function staticSchema(mixed $value = null): static
    {
        return $this->set('staticSchema', $value);
    }
}
