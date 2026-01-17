<?php
namespace warm\admin\renderer\form;

use warm\admin\renderer\BaseRenderer;
use warm\admin\renderer\trait\FormItem;
use warm\admin\renderer\trait\OnEvent;

/**
 * InputNumber
 *
 * @see https://aisuda.bce.baidu.com/amis/zh-CN/components/input-number
 */
class InputNumber extends BaseRenderer
{
    use FormItem;
    use OnEvent;

    public string $type = 'input-number';

    /**
     * 最小值
     *
     * @param mixed $value
     * @return self
     */
    public function min(mixed $value = null): static
    {
        return $this->set('min', $value);
    }

    /**
     * 最大值
     *
     * @param mixed $value
     * @return self
     */
    public function max(mixed $value = null): static
    {
        return $this->set('max', $value);
    }

    /**
     * 步长
     *
     * @param int|float $value
     * @return self
     */
    public function step(int|float $value = 0): static
    {
        return $this->set('step', $value);
    }

    /**
     * 精度，即小数点后几位，支持 0 和正整数
     *
     * @param int|float $value
     * @return self
     */
    public function precision(int|float $value = 0): static
    {
        return $this->set('precision', $value);
    }

    /**
     * 是否显示上下点击按钮
     *
     * @param bool $value
     * @return self
     */
    public function showSteps(bool $value = true): static
    {
        return $this->set('showSteps', $value);
    }

    /**
     * 只读
     *
     * @param bool $value
     * @return self
     */
    public function readOnly(bool $value = true): static
    {
        return $this->set('readOnly', $value);
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
     * 单位选项
     *
     * @param array $value
     * @return self
     */
    public function unitOptions(array $value = []): static
    {
        return $this->set('unitOptions', $value);
    }

    /**
     * 千分分隔
     *
     * @param bool $value
     * @return self
     */
    public function kilobitSeparator(bool $value = true): static
    {
        return $this->set('kilobitSeparator', $value);
    }

    /**
     * 键盘事件（方向上下）
     *
     * @param bool $value
     * @return self
     */
    public function keyboard(bool $value = true): static
    {
        return $this->set('keyboard', $value);
    }

    /**
     * 是否使用大数
     *
     * @param bool $value
     * @return self
     */
    public function big(bool $value = true): static
    {
        return $this->set('big', $value);
    }

    /**
     * `"base"`
     *
     * @param mixed $value
     * @return self
     */
    public function displayMode(mixed $value = null): static
    {
        return $this->set('displayMode', $value);
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
     * `""`
     *
     * @param mixed $value
     * @return self
     */
    public function resetValue(mixed $value = null): static
    {
        return $this->set('resetValue', $value);
    }

    /**
     * 内容为空时从数据域中删除该表单项对应的值
     *
     * @param bool $value
     * @return self
     */
    public function clearValueOnEmpty(bool $value = true): static
    {
        return $this->set('clearValueOnEmpty', $value);
    }
}
