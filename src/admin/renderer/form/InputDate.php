<?php
namespace warm\admin\renderer\form;

use warm\admin\renderer\BaseRenderer;
use warm\admin\renderer\form\FormItemTrait;

/**
 * InputDate
 *
 * @see https://aisuda.bce.baidu.com/amis/zh-CN/components/input-date
 */
class InputDate extends BaseRenderer
{
    use FormItemTrait;

    public string $type = 'input-date';

    /**
     * [默认值](./date#%E9%BB%98%E8%AE%A4%E5%80%BC)
     *
     * @param string $value
     * @return self
     */
    public function value(string $value = ''): self
    {
        return $this->set('value', $value);
    }

    /**
     * 日期选择器值格式，更多格式类型请参考 [文档](https://momentjs.com/docs/#/displaying/format/)
     *
     * @param string $value
     * @return self
     */
    public function valueFormat(string $value = 'X'): self
    {
        return $this->set('valueFormat', $value);
    }

    /**
     * 日期选择器显示格式，即时间戳格式，更多格式类型请参考 [文档](https://momentjs.com/docs/#/displaying/format/)
     *
     * @param string $value
     * @return self
     */
    public function displayFormat(string $value = 'YYYY-MM-DD'): self
    {
        return $this->set('displayFormat', $value);
    }

    /**
     * 点选日期后，是否马上关闭选择框
     *
     * @param bool $value
     * @return self
     */
    public function closeOnSelect(bool $value = true): self
    {
        return $this->set('closeOnSelect', $value);
    }

    /**
     * 占位文本
     *
     * @param string $value
     * @return self
     */
    public function placeholder(string $value = '请选择日期'): self
    {
        return $this->set('placeholder', $value);
    }

    /**
     * Array<{"label": string; date: string}>`
     *
     * @param mixed $value
     * @return self
     */
    public function shortcuts(mixed $value = null): self
    {
        return $this->set('shortcuts', $value);
    }

    /**
     * 限制最小日期
     *
     * @param string $value
     * @return self
     */
    public function minDate(string $value = ''): self
    {
        return $this->set('minDate', $value);
    }

    /**
     * 限制最大日期
     *
     * @param string $value
     * @return self
     */
    public function maxDate(string $value = ''): self
    {
        return $this->set('maxDate', $value);
    }

    /**
     * 保存 utc 值
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
     * 用字符函数来控制哪些天不可以被点选
     *
     * @param string $value
     * @return self
     */
    public function disabledDate(string $value = ''): self
    {
        return $this->set('disabledDate', $value);
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
