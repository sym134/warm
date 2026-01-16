<?php
namespace warm\admin\renderer\form;

use warm\admin\renderer\BaseRenderer;
use warm\admin\renderer\form\FormItemTrait;

/**
 * InputDatetimeRange
 *
 * @see https://aisuda.bce.baidu.com/amis/zh-CN/components/input-datetime-range
 */
class InputDatetimeRange extends BaseRenderer
{
    use FormItemTrait;

    public string $type = 'input-datetime-range';

    /**
     * [日期时间选择器值格式](./input-datetime#%E5%80%BC%E6%A0%BC%E5%BC%8F)
     *
     * @param string $value
     * @return self
     */
    public function valueFormat(string $value = 'X'): self
    {
        return $this->set('valueFormat', $value);
    }

    /**
     * [日期时间选择器显示格式](./input-datetime#%E6%98%BE%E7%A4%BA%E6%A0%BC%E5%BC%8F)
     *
     * @param string $value
     * @return self
     */
    public function displayFormat(string $value = 'YYYY-MM-DD'): self
    {
        return $this->set('displayFormat', $value);
    }

    /**
     * 占位文本
     *
     * @param string $value
     * @return self
     */
    public function placeholder(string $value = '请选择日期范围'): self
    {
        return $this->set('placeholder', $value);
    }

    /**
     * Array<{label: string; startDate: string; endDate: string}>`
     *
     * @param mixed $value
     * @return self
     */
    public function shortcuts(mixed $value = null): self
    {
        return $this->set('shortcuts', $value);
    }

    /**
     * 限制最小日期时间，用法同 [限制范围](./input-datetime#%E9%99%90%E5%88%B6%E8%8C%83%E5%9B%B4)
     *
     * @param string $value
     * @return self
     */
    public function minDate(string $value = ''): self
    {
        return $this->set('minDate', $value);
    }

    /**
     * 限制最大日期时间，用法同 [限制范围](./input-datetime#%E9%99%90%E5%88%B6%E8%8C%83%E5%9B%B4)
     *
     * @param string $value
     * @return self
     */
    public function maxDate(string $value = ''): self
    {
        return $this->set('maxDate', $value);
    }

    /**
     * [保存 UTC 值](./input-datetime#utc)
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
