<?php
namespace warm\admin\renderer\form;

use warm\admin\renderer\BaseRenderer;
use warm\admin\renderer\trait\FormItem;
use warm\admin\renderer\trait\OnEvent;

/**
 * InputRange 范围输入框
 *
 * @see https://aisuda.bce.baidu.com/amis/zh-CN/components/input-range
 */
class InputRange extends BaseRenderer
{
    use FormItem;
    use OnEvent;

    public string $type = 'input-range';

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
     * 
     *
     * @param mixed $value
     * @return self
     */
    public function value(mixed $value = null): static
    {
        return $this->set('value', $value);
    }

    /**
     * `0`
     *
     * @param mixed $value
     * @return self
     */
    public function min(mixed $value = null): static
    {
        return $this->set('min', $value);
    }

    /**
     * `100`
     *
     * @param mixed $value
     * @return self
     */
    public function max(mixed $value = null): static
    {
        return $this->set('max', $value);
    }

    /**
     * 是否禁用
     *
     * @param bool $value
     * @return self
     */
    public function disabled(bool $value = true): static
    {
        return $this->set('disabled', $value);
    }

    /**
     * `1`
     *
     * @param mixed $value
     * @return self
     */
    public function step(mixed $value = null): static
    {
        return $this->set('step', $value);
    }

    /**
     * 是否显示步长
     *
     * @param bool $value
     * @return self
     */
    public function showSteps(bool $value = true): static
    {
        return $this->set('showSteps', $value);
    }

    /**
     * 分割的块数<br/>主持数组传入分块的节点
     *
     * @param array $value
     * @return self
     */
    public function parts(array $value = []): static
    {
        return $this->set('parts', $value);
    }

    /**
     * 刻度标记<br/>- 支持自定义样式<br/>- 设置百分比
     *
     * @param mixed $value
     * @return self
     */
    public function marks(mixed $value = null): static
    {
        return $this->set('marks', $value);
    }

    /**
     * 是否显示滑块标签
     *
     * @param bool $value
     * @return self
     */
    public function tooltipVisible(bool $value = true): static
    {
        return $this->set('tooltipVisible', $value);
    }

    /**
     * 滑块标签的位置，默认`auto`，方向自适应<br/>前置条件：tooltipVisible 不为 false 时有效
     *
     * @param mixed $value
     * @return self
     */
    public function tooltipPlacement(mixed $value = null): static
    {
        return $this->set('tooltipPlacement', $value);
    }

    /**
     * 控制滑块标签显隐函数<br/>前置条件：tooltipVisible 不为 false 时有效
     *
     * @param mixed $value
     * @return self
     */
    public function tipFormatter(mixed $value = null): static
    {
        return $this->set('tipFormatter', $value);
    }

    /**
     * 支持选择范围
     *
     * @param bool $value
     * @return self
     */
    public function multiple(bool $value = true): static
    {
        return $this->set('multiple', $value);
    }

    /**
     * 默认为 `true`，选择的 `value` 会通过 `delimiter` 连接起来，否则直接将以`{min: 1, max: 100}`的形式提交<br/>前置条件：开启`multiple`时有效
     *
     * @param bool $value
     * @return self
     */
    public function joinValues(bool $value = true): static
    {
        return $this->set('joinValues', $value);
    }

    /**
     * 分隔符
     *
     * @param string $value
     * @return self
     */
    public function delimiter(string $value = ','): static
    {
        return $this->set('delimiter', $value);
    }

    /**
     * 单位
     *
     * @param string $value
     * @return self
     */
    public function unit(string $value = ''): static
    {
        return $this->set('unit', $value);
    }

    /**
     * 是否可清除<br/>前置条件：开启`showInput`时有效
     *
     * @param bool $value
     * @return self
     */
    public function clearable(bool $value = true): static
    {
        return $this->set('clearable', $value);
    }

    /**
     * 是否显示输入框
     *
     * @param bool $value
     * @return self
     */
    public function showInput(bool $value = true): static
    {
        return $this->set('showInput', $value);
    }

    /**
     * 是否显示输入框单位<br/>前置条件：开启`showInput`且配置了`unit`单位时有效
     *
     * @param bool $value
     * @return self
     */
    public function showInputUnit(bool $value = true): static
    {
        return $this->set('showInputUnit', $value);
    }

    /**
     * 当 组件 的值发生改变时，会触发 onChange 事件，并把改变后的值作为参数传入
     *
     * @param mixed $value
     * @return self
     */
    public function onChange(mixed $value = null): static
    {
        return $this->set('onChange', $value);
    }

    /**
     * 与 `onmouseup` 触发时机一致，把当前值作为参数传入
     *
     * @param mixed $value
     * @return self
     */
    public function onAfterChange(mixed $value = null): static
    {
        return $this->set('onAfterChange', $value);
    }
}
