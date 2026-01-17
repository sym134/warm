<?php
namespace warm\admin\renderer\form;

use warm\admin\renderer\BaseRenderer;
use warm\admin\renderer\trait\FormItem;
use warm\admin\renderer\trait\OnEvent;

/**
 * InputQuarterRange
 *
 * @see https://aisuda.bce.baidu.com/amis/zh-CN/components/input-quarter-range
 */
class InputQuarterRange extends BaseRenderer
{
    use FormItem;
    use OnEvent;

    public string $type = 'input-quarter-range';

    /**
     * [日期选择器值格式](./input-date#%E5%80%BC%E6%A0%BC%E5%BC%8F)
     *
     * @param string $value
     * @return self
     */
    public function valueFormat(string $value = 'X'): static
    {
        return $this->set('valueFormat', $value);
    }

    /**
     * [日期选择器显示格式](./input-date#%E6%98%BE%E7%A4%BA%E6%A0%BC%E5%BC%8F)
     *
     * @param string $value
     * @return self
     */
    public function displayFormat(string $value = 'YYYY-DD'): static
    {
        return $this->set('displayFormat', $value);
    }

    /**
     * 占位文本
     *
     * @param string $value
     * @return self
     */
    public function placeholder(string $value = '请选择季度范围'): static
    {
        return $this->set('placeholder', $value);
    }

    /**
     * 限制最小日期，用法同 [限制范围](./input-date#%E9%99%90%E5%88%B6%E8%8C%83%E5%9B%B4)
     *
     * @param string $value
     * @return self
     */
    public function minDate(string $value = ''): static
    {
        return $this->set('minDate', $value);
    }

    /**
     * 限制最大日期，用法同 [限制范围](./input-date#%E9%99%90%E5%88%B6%E8%8C%83%E5%9B%B4)
     *
     * @param string $value
     * @return self
     */
    public function maxDate(string $value = ''): static
    {
        return $this->set('maxDate', $value);
    }

    /**
     * 限制最小跨度，如： 2quarter
     *
     * @param string $value
     * @return self
     */
    public function minDuration(string $value = ''): static
    {
        return $this->set('minDuration', $value);
    }

    /**
     * 限制最大跨度，如：4quarter
     *
     * @param string $value
     * @return self
     */
    public function maxDuration(string $value = ''): static
    {
        return $this->set('maxDuration', $value);
    }

    /**
     * [保存 UTC 值](./input-date#utc)
     *
     * @param bool $value
     * @return self
     */
    public function utc(bool $value = true): static
    {
        return $this->set('utc', $value);
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
     * 是否内联模式
     *
     * @param bool $value
     * @return self
     */
    public function embed(bool $value = true): static
    {
        return $this->set('embed', $value);
    }

    /**
     * 是否启用游标动画
     *
     * @param bool $value
     * @return self
     */
    public function animation(bool $value = true): static
    {
        return $this->set('animation', $value);
    }

    /**
     * 是否存成两个字段
     *
     * @param string $value
     * @return self
     */
    public function extraName(string $value = ''): static
    {
        return $this->set('extraName', $value);
    }

    /**
     * 弹层挂载位置选择器，会通过`querySelector`获取
     *
     * @param string $value
     * @return self
     */
    public function popOverContainerSelector(string $value = ''): static
    {
        return $this->set('popOverContainerSelector', $value);
    }
}
