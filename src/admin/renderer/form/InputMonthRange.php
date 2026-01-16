<?php
namespace warm\admin\renderer\form;

use warm\admin\renderer\BaseRenderer;
use warm\admin\renderer\form\FormItemTrait;

/**
 * InputMonthRange
 *
 * @see https://aisuda.bce.baidu.com/amis/zh-CN/components/input-month-range
 */
class InputMonthRange extends BaseRenderer
{
    use FormItemTrait;

    public string $type = 'input-month-range';

    /**
     * [日期选择器值格式](./input-date#%E5%80%BC%E6%A0%BC%E5%BC%8F)
     *
     * @param string $value
     * @return self
     */
    public function format(string $value = 'X'): self
    {
        return $this->set('format', $value);
    }

    /**
     * [日期选择器显示格式](./input-date#%E6%98%BE%E7%A4%BA%E6%A0%BC%E5%BC%8F)
     *
     * @param string $value
     * @return self
     */
    public function inputFormat(string $value = 'YYYY-DD'): self
    {
        return $this->set('inputFormat', $value);
    }

    /**
     * 占位文本
     *
     * @param string $value
     * @return self
     */
    public function placeholder(string $value = '请选择月份范围'): self
    {
        return $this->set('placeholder', $value);
    }

    /**
     * 限制最小日期，用法同 [限制范围](./input-date#%E9%99%90%E5%88%B6%E8%8C%83%E5%9B%B4)
     *
     * @param string $value
     * @return self
     */
    public function minDate(string $value = ''): self
    {
        return $this->set('minDate', $value);
    }

    /**
     * 限制最大日期，用法同 [限制范围](./input-date#%E9%99%90%E5%88%B6%E8%8C%83%E5%9B%B4)
     *
     * @param string $value
     * @return self
     */
    public function maxDate(string $value = ''): self
    {
        return $this->set('maxDate', $value);
    }

    /**
     * 限制最小跨度，如： 2days
     *
     * @param string $value
     * @return self
     */
    public function minDuration(string $value = ''): self
    {
        return $this->set('minDuration', $value);
    }

    /**
     * 限制最大跨度，如：1year
     *
     * @param string $value
     * @return self
     */
    public function maxDuration(string $value = ''): self
    {
        return $this->set('maxDuration', $value);
    }

    /**
     * [保存 UTC 值](./input-date#utc)
     *
     * @param bool $value
     * @return self
     */
    public function utc(bool $value = true): self
    {
        return $this->set('utc', $value);
    }

    /**
     * 是否可清除
     *
     * @param bool $value
     * @return self
     */
    public function clearable(bool $value = true): self
    {
        return $this->set('clearable', $value);
    }

    /**
     * 是否内联模式
     *
     * @param bool $value
     * @return self
     */
    public function embed(bool $value = true): self
    {
        return $this->set('embed', $value);
    }

    /**
     * 是否启用游标动画
     *
     * @param bool $value
     * @return self
     */
    public function animation(bool $value = true): self
    {
        return $this->set('animation', $value);
    }

    /**
     * 是否存成两个字段
     *
     * @param string $value
     * @return self
     */
    public function extraName(string $value = ''): self
    {
        return $this->set('extraName', $value);
    }

    /**
     * 弹层挂载位置选择器，会通过`querySelector`获取
     *
     * @param string $value
     * @return self
     */
    public function popOverContainerSelector(string $value = ''): self
    {
        return $this->set('popOverContainerSelector', $value);
    }
}
