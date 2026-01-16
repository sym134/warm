<?php
namespace warm\admin\renderer\form;

use warm\admin\renderer\BaseRenderer;
use warm\admin\renderer\form\FormItemTrait;

/**
 * InputFormula
 *
 * @see https://aisuda.bce.baidu.com/amis/zh-CN/components/input-formula
 */
class InputFormula extends BaseRenderer
{
    use FormItemTrait;

    public string $type = 'input-formula';

    /**
     * 弹框标题
     *
     * @param string $value
     * @return self
     */
    public function title(string $value = '公式编辑器'): self
    {
        return $this->set('title', $value);
    }

    /**
     * 编辑器 header 标题，如果不设置，默认使用表单项`label`字段
     *
     * @param string $value
     * @return self
     */
    public function header(string $value = '-'): self
    {
        return $this->set('header', $value);
    }

    /**
     * 表达式模式 或者 模板模式，模板模式则需要将表达式写在 `${` 和 `}` 中间。
     *
     * @param bool $value
     * @return self
     */
    public function evalMode(bool $value = true): self
    {
        return $this->set('evalMode', $value);
    }

    /**
     * 可用变量
     *
     * @param array $value
     * @return self
     */
    public function variables(array $value = []): self
    {
        return $this->set('variables', $value);
    }

    /**
     * 可配置成 `tabs` 或者 `tree` 默认为列表，支持分组。
     *
     * @param string $value
     * @return self
     */
    public function variableMode(string $value = 'list'): self
    {
        return $this->set('variableMode', $value);
    }

    /**
     * 可以不设置，默认就是 amis-formula 里面定义的函数，如果扩充了新的函数则需要指定
     *
     * @param array $value
     * @return self
     */
    public function functions(array $value = []): self
    {
        return $this->set('functions', $value);
    }

    /**
     * 'input-group'`
     *
     * @param mixed $value
     * @return self
     */
    public function inputMode(mixed $value = null): self
    {
        return $this->set('inputMode', $value);
    }

    /**
     * 按钮图标，例如`fa fa-list`
     *
     * @param string $value
     * @return self
     */
    public function icon(string $value = '-'): self
    {
        return $this->set('icon', $value);
    }

    /**
     * 按钮文本，`inputMode`为`button`时生效
     *
     * @param string $value
     * @return self
     */
    public function btnLabel(string $value = '公示编辑'): self
    {
        return $this->set('btnLabel', $value);
    }

    /**
     * 'warning' \
     *
     * @param mixed $value
     * @return self
     */
    public function level(mixed $value = null): self
    {
        return $this->set('level', $value);
    }

    /**
     * 输入框是否可输入
     *
     * @param bool $value
     * @return self
     */
    public function allowInput(bool $value = true): self
    {
        return $this->set('allowInput', $value);
    }

    /**
     * 'md' \
     *
     * @param mixed $value
     * @return self
     */
    public function btnSize(mixed $value = null): self
    {
        return $this->set('btnSize', $value);
    }

    /**
     * 'none'`
     *
     * @param mixed $value
     * @return self
     */
    public function borderMode(mixed $value = null): self
    {
        return $this->set('borderMode', $value);
    }

    /**
     * 输入框占位符
     *
     * @param string $value
     * @return self
     */
    public function placeholder(string $value = '暂无数据'): self
    {
        return $this->set('placeholder', $value);
    }

    /**
     * 控件外层 CSS 样式类名
     *
     * @param string $value
     * @return self
     */
    public function className(string $value = '-'): self
    {
        return $this->set('className', $value);
    }

    /**
     * 变量面板 CSS 样式类名
     *
     * @param string $value
     * @return self
     */
    public function variableClassName(string $value = '-'): self
    {
        return $this->set('variableClassName', $value);
    }

    /**
     * 函数面板 CSS 样式类名
     *
     * @param string $value
     * @return self
     */
    public function functionClassName(string $value = '-'): self
    {
        return $this->set('functionClassName', $value);
    }
}
