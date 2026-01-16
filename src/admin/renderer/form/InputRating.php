<?php
namespace warm\admin\renderer\form;

use warm\admin\renderer\BaseRenderer;
use warm\admin\renderer\form\FormItemTrait;

/**
 * InputRating
 *
 * @see https://aisuda.bce.baidu.com/amis/zh-CN/components/input-rating
 */
class InputRating extends BaseRenderer
{
    use FormItemTrait;

    public string $type = 'input-rating';

    /**
     * 当前值
     *
     * @param int|float $value
     * @return self
     */
    public function value(int|float $value = 0): self
    {
        return $this->set('value', $value);
    }

    /**
     * 是否使用半星选择
     *
     * @param bool $value
     * @return self
     */
    public function half(bool $value = true): self
    {
        return $this->set('half', $value);
    }

    /**
     * 总星数
     *
     * @param int|float $value
     * @return self
     */
    public function count(int|float $value = 5): self
    {
        return $this->set('count', $value);
    }

    /**
     * 只读
     *
     * @param bool $value
     * @return self
     */
    public function readOnly(bool $value = true): self
    {
        return $this->set('readOnly', $value);
    }

    /**
     * 是否允许再次点击后清除
     *
     * @param bool $value
     * @return self
     */
    public function allowClear(bool $value = true): self
    {
        return $this->set('allowClear', $value);
    }

    /**
     * 星星被选中的颜色。 若传入字符串，则只有一种颜色。若传入对象，可自定义分段，键名为分段的界限值，键值为对应的类名
     *
     * @param mixed $value
     * @return self
     */
    public function colors(mixed $value = null): self
    {
        return $this->set('colors', $value);
    }

    /**
     * 未被选中的星星的颜色
     *
     * @param string $value
     * @return self
     */
    public function inactiveColor(string $value = '#e7e7e8'): self
    {
        return $this->set('inactiveColor', $value);
    }

    /**
     * 星星被选中时的提示文字。可自定义分段，键名为分段的界限值，键值为对应的类名
     *
     * @param array $value
     * @return self
     */
    public function texts(array $value = []): self
    {
        return $this->set('texts', $value);
    }

    /**
     * 文字的位置
     *
     * @param mixed $value
     * @return self
     */
    public function textPosition(mixed $value = null): self
    {
        return $this->set('textPosition', $value);
    }

    /**
     * 自定义字符
     *
     * @param string $value
     * @return self
     */
    public function char(string $value = '★'): self
    {
        return $this->set('char', $value);
    }

    /**
     * 自定义样式类名
     *
     * @param string $value
     * @return self
     */
    public function className(string $value = '-'): self
    {
        return $this->set('className', $value);
    }

    /**
     * 自定义字符类名
     *
     * @param string $value
     * @return self
     */
    public function charClassName(string $value = '-'): self
    {
        return $this->set('charClassName', $value);
    }

    /**
     * 自定义文字类名
     *
     * @param string $value
     * @return self
     */
    public function textClassName(string $value = '-'): self
    {
        return $this->set('textClassName', $value);
    }
}
