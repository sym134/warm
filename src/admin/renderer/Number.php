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
     * 外层 CSS 类名
     *
     * @param string $value
     * @return self
     */
    public function className(string $value = ''): self
    {
        return $this->set('className', $value);
    }

    /**
     * 数值
     *
     * @param string $value
     * @return self
     */
    public function value(string $value = ''): self
    {
        return $this->set('value', $value);
    }

    /**
     * 在其他组件中，时，用作变量映射
     *
     * @param string $value
     * @return self
     */
    public function name(string $value = ''): self
    {
        return $this->set('name', $value);
    }

    /**
     * 占位内容
     *
     * @param string $value
     * @return self
     */
    public function placeholder(string $value = '-'): self
    {
        return $this->set('placeholder', $value);
    }

    /**
     * 是否千分位展示
     *
     * @param bool $value
     * @return self
     */
    public function kilobitSeparator(bool $value = true): self
    {
        return $this->set('kilobitSeparator', $value);
    }

    /**
     * 用来控制小数点位数
     *
     * @param int|float $value
     * @return self
     */
    public function precision(int|float $value = 0): self
    {
        return $this->set('precision', $value);
    }

    /**
     * 
     *
     * @param mixed $value
     * @return self
     */
    public function percent(mixed $value = null): self
    {
        return $this->set('percent', $value);
    }

    /**
     * 前缀
     *
     * @param string $value
     * @return self
     */
    public function prefix(string $value = ''): self
    {
        return $this->set('prefix', $value);
    }

    /**
     * 后缀
     *
     * @param string $value
     * @return self
     */
    public function affix(string $value = ''): self
    {
        return $this->set('affix', $value);
    }
}
