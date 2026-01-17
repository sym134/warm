<?php

namespace warm\admin\renderer\form;

use warm\admin\renderer\BaseRenderer;
use warm\admin\renderer\trait\FormItem;
use warm\admin\renderer\trait\OnEvent;

/**
 * InputText
 *
 * @see https://aisuda.bce.baidu.com/amis/zh-CN/components/input-text
 */
class InputText extends BaseRenderer
{
    use FormItem;
    use OnEvent;

    public string $type = 'input-text';

    /**
     * 输入框尺寸 可选值: 'xs' | 'sm' | 'md' | 'lg' | 'full'
     * @param string $value
     * @return self
     */
    public function size(string $value = 'md'): static
    {
        return $this->set('size', $value);
    }

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
     * [自动补全](./options#%E8%87%AA%E5%8A%A8%E8%A1%A5%E5%85%A8-autocomplete)
     *
     * @param mixed $value
     * @return self
     */
    public function autoComplete(mixed $value = null): static
    {
        return $this->set('autoComplete', $value);
    }

    /**
     * [是否多选](./options#%E5%A4%9A%E9%80%89-multiple)
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
     * 输入框附加组件，比如附带一个提示文字，或者附带一个提交按钮。
     *
     * @param mixed $value
     * @return self
     */
    public function addOn(mixed $value = null): static
    {
        return $this->set('addOn', $value);
    }

    /**
     * 是否去除首尾空白文本。
     *
     * @param bool $value
     * @return self
     */
    public function trimContents(bool $value = true): static
    {
        return $this->set('trimContents', $value);
    }

    /**
     * 文本内容为空时去掉这个值
     *
     * @param bool $value
     * @return self
     */
    public function clearValueOnEmpty(bool $value = true): static
    {
        return $this->set('clearValueOnEmpty', $value);
    }

    /**
     * 是否可以创建，默认为可以，除非设置为 false 即只能选择选项中的值
     *
     * @param bool $value
     * @return self
     */
    public function creatable(bool $value = true): static
    {
        return $this->set('creatable', $value);
    }

    /**
     * 是否可清除
     *
     * @param bool $value
     * @return self
     */
    public function clearable(bool $value = true): static
    {
        return $this->set('clearable', $value);
    }

    /**
     * 清除后设置此配置项给定的值。
     *
     * @param string $value
     * @return self
     */
    public function resetValue(string $value = ''): static
    {
        return $this->set('resetValue', $value);
    }

    /**
     * 前缀
     *
     * @param string $value
     * @return self
     */
    public function prefix(string $value = ''): static
    {
        return $this->set('prefix', $value);
    }

    /**
     * 后缀
     *
     * @param string $value
     * @return self
     */
    public function suffix(string $value = ''): static
    {
        return $this->set('suffix', $value);
    }

    /**
     * 是否显示计数器
     *
     * @param bool $value
     * @return self
     */
    public function showCounter(bool $value = true): static
    {
        return $this->set('showCounter', $value);
    }

    /**
     * 限制最小字数
     *
     * @param int|float $value
     * @return self
     */
    public function minLength(int|float $value = 0): static
    {
        return $this->set('minLength', $value);
    }

    /**
     * 限制最大字数
     *
     * @param int|float $value
     * @return self
     */
    public function maxLength(int|float $value = 0): static
    {
        return $this->set('maxLength', $value);
    }

    /**
     * 自动转换值，可选 `transform: { lowerCase: true, upperCase: true }`
     *
     * @param array $value
     * @return self
     */
    public function transform(array $value = []): static
    {
        return $this->set('transform', $value);
    }

    /**
     * "none"`
     *
     * @param mixed $value
     * @return self
     */
    public function borderMode(mixed $value = null): static
    {
        return $this->set('borderMode', $value);
    }

    /**
     * control 节点的 CSS 类名
     *
     * @param string $value
     * @return self
     */
    public function inputControlClassName(string $value = ''): static
    {
        return $this->set('inputControlClassName', $value);
    }

    /**
     * 原生 input 标签的 CSS 类名
     *
     * @param string $value
     * @return self
     */
    public function nativeInputClassName(string $value = ''): static
    {
        return $this->set('nativeInputClassName', $value);
    }

    /**
     * 原生 input 标签的 `autoComplete` 属性，比如配置集成 `new-password`
     *
     * @param string $value
     * @return self
     */
    public function nativeAutoComplete(string $value = 'off'): static
    {
        return $this->set('nativeAutoComplete', $value);
    }
}
