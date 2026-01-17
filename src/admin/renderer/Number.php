<?php
namespace warm\admin\renderer;
/**
 * Number
 *
 * @see https://aisuda.bce.baidu.com/amis/zh-CN/components/number
 */
class Number extends BaseRenderer
{
    public string $type = 'number';

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
     * 数值
     *
     * @param string $value
     * @return self
     */
    public function value(string $value = ''): static
    {
        return $this->set('value', $value);
    }

    /**
     * 在其他组件中，时，用作变量映射
     *
     * @param string $value
     * @return self
     */
    public function name(string $value = ''): static
    {
        return $this->set('name', $value);
    }

    /**
     * 占位内容
     *
     * @param string $value
     * @return self
     */
    public function placeholder(string $value = '-'): static
    {
        return $this->set('placeholder', $value);
    }

    /**
     * 是否千分位展示
     *
     * @param bool $value
     * @return self
     */
    public function kilobitSeparator(bool $value = true): static
    {
        return $this->set('kilobitSeparator', $value);
    }

    /**
     * 用来控制小数点位数
     *
     * @param int|float $value
     * @return self
     */
    public function precision(int|float $value = 0): static
    {
        return $this->set('precision', $value);
    }

    /**
     * 
     *
     * @param mixed $value
     * @return self
     */
    public function percent(mixed $value = null): static
    {
        return $this->set('percent', $value);
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
    public function affix(string $value = ''): static
    {
        return $this->set('affix', $value);
    }
}
