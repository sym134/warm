<?php

namespace warm\admin\renderer\expand;

use warm\admin\renderer\BaseRenderer;

/**
 * 自定义组件-操作栏渲染器
 *
 * @author slowlyo
 * @version 6.13.0
 */
class Operation extends BaseRenderer
{
    public string $type = 'operation';

    /**
     * 按钮集合
     */
    public function buttons($value = '')
    {
        return $this->set('buttons', $value);
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
     * 是否禁用
     */
    public function disabled($value = true): static
    {
        return $this->set('disabled', $value);
    }

    /**
     * 是否禁用表达式 (表达式，语法 `${xxx > 5}`。)
     */
    public function disabledOn($value = ''): static
    {
        return $this->set('disabledOn', $value);
    }

    /**
     * 编辑器配置，运行时可以忽略
     */
    public function editorSetting($value = ''): static
    {
        return $this->set('editorSetting', $value);
    }

    /**
     * 固定列 可选值: left | right | none
     */
    public function fixed($value = ''): static
    {
        return $this->set('fixed', $value);
    }

    /**
     * 是否隐藏
     */
    public function hidden($value = true): static
    {
        return $this->set('hidden', $value);
    }

    /**
     * 是否隐藏表达式 (表达式，语法 `${xxx > 5}`。)
     */
    public function hiddenOn($value = ''): static
    {
        return $this->set('hiddenOn', $value);
    }

    /**
     * 设置label
     */
    public function label(mixed $value = ''): static
    {
        return $this->set('label', $value);
    }

    /**
     * 事件动作配置
     */
    public function onEvent($value = ''): static
    {
        return $this->set('onEvent', $value);
    }

    /**
     * 占位符
     */
    public function placeholder($value = ''): static
    {
        return $this->set('placeholder', $value);
    }

    /**
     * 是否静态展示
     */
    public function static($value = true): static
    {
        return $this->set('static', $value);
    }

    /**
     * 静态展示表单项类名 (css类名，配置字符串，或者对象。    className: "red"用对象配置时意味着你能跟表达式一起搭配使用，如：    className: {         "red": "data.progress > 80",         "blue": "data.progress > 60"     })
     */
    public function staticClassName($value = ''): static
    {
        return $this->set('staticClassName', $value);
    }

    /**
     * 静态展示表单项Value类名 (css类名，配置字符串，或者对象。    className: "red"用对象配置时意味着你能跟表达式一起搭配使用，如：    className: {         "red": "data.progress > 80",         "blue": "data.progress > 60"     })
     */
    public function staticInputClassName($value = ''): static
    {
        return $this->set('staticInputClassName', $value);
    }

    /**
     * 静态展示表单项Label类名 (css类名，配置字符串，或者对象。    className: "red"用对象配置时意味着你能跟表达式一起搭配使用，如：    className: {         "red": "data.progress > 80",         "blue": "data.progress > 60"     })
     */
    public function staticLabelClassName($value = ''): static
    {
        return $this->set('staticLabelClassName', $value);
    }

    /**
     * 是否静态展示表达式 (表达式，语法 `${xxx > 5}`。)
     */
    public function staticOn($value = ''): static
    {
        return $this->set('staticOn', $value);
    }

    /**
     * 静态展示空值占位
     */
    public function staticPlaceholder($value = ''): static
    {
        return $this->set('staticPlaceholder', $value);
    }

    /**
     *
     */
    public function staticSchema($value = ''): static
    {
        return $this->set('staticSchema', $value);
    }

    /**
     * 组件样式
     */
    public function style($value = ''): static
    {
        return $this->set('style', $value);
    }

    /**
     *
     */
    public function testid($value = ''): static
    {
        return $this->set('testid', $value);
    }

    /**
     * 指定为操作栏
     */
    public function type($value = 'operation'): static
    {
        return $this->set('type', $value);
    }

    /**
     * 可以组件级别用来关闭移动端样式
     */
    public function useMobileUI($value = true): static
    {
        return $this->set('useMobileUI', $value);
    }

    /**
     * 是否显示
     */
    public function visible($value = true): static
    {
        return $this->set('visible', $value);
    }

    /**
     * 是否显示表达式 (表达式，语法 `${xxx > 5}`。)
     */
    public function visibleOn($value = ''): static
    {
        return $this->set('visibleOn', $value);
    }


}
