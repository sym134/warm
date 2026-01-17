<?php
namespace warm\admin\renderer\form;

use warm\admin\renderer\BaseRenderer;
use warm\admin\renderer\trait\FormItem;
use warm\admin\renderer\trait\OnEvent;

/**
 * InputDatetime
 *
 * @see https://aisuda.bce.baidu.com/amis/zh-CN/components/input-datetime
 */
class InputDatetime extends BaseRenderer
{
    use FormItem;
    use OnEvent;

    public string $type = 'input-datetime';

    /**
     * [默认值](./datetime#%E9%BB%98%E8%AE%A4%E5%80%BC)
     *
     * @param string $value
     * @return self
     */
    public function value(string $value = ''): static
    {
        return $this->set('value', $value);
    }

    /**
     * 日期时间选择器值格式，更多格式类型请参考 [文档](https://momentjs.com/docs/#/displaying/format/)
     *
     * @param string $value
     * @return self
     */
    public function valueFormat(string $value = 'X'): static
    {
        return $this->set('valueFormat', $value);
    }

    /**
     * 日期时间选择器显示格式，即时间戳格式，更多格式类型请参考 [文档](https://momentjs.com/docs/#/displaying/format/)
     *
     * @param string $value
     * @return self
     */
    public function displayFormat(string $value = 'YYYY-MM-DD HH:mm:ss'): static
    {
        return $this->set('displayFormat', $value);
    }

    /**
     * 占位文本
     *
     * @param string $value
     * @return self
     */
    public function placeholder(string $value = '请选择日期以及时间'): static
    {
        return $this->set('placeholder', $value);
    }

    /**
     * Array<{"label": string; date: string}>`
     *
     * @param mixed $value
     * @return self
     */
    public function shortcuts(mixed $value = null): static
    {
        return $this->set('shortcuts', $value);
    }

    /**
     * 限制最小日期时间
     *
     * @param string $value
     * @return self
     */
    public function minDate(string $value = ''): static
    {
        return $this->set('minDate', $value);
    }

    /**
     * 限制最大日期时间
     *
     * @param string $value
     * @return self
     */
    public function maxDate(string $value = ''): static
    {
        return $this->set('maxDate', $value);
    }

    /**
     * 保存 utc 值
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
     * 是否内联
     *
     * @param bool $value
     * @return self
     */
    public function embed(bool $value = true): static
    {
        return $this->set('embed', $value);
    }

    /**
     * 请参考 [input-time](./input-time#控制输入范围) 里的说明
     *
     * @param array $value
     * @return self
     */
    public function timeConstraints(array $value = []): static
    {
        return $this->set('timeConstraints', $value);
    }

    /**
     * 如果配置为 true，会自动默认为 23:59:59 秒
     *
     * @param bool $value
     * @return self
     */
    public function isEndDate(bool $value = true): static
    {
        return $this->set('isEndDate', $value);
    }

    /**
     * 用字符函数来控制哪些天不可以被点选
     *
     * @param string $value
     * @return self
     */
    public function disabledDate(string $value = ''): static
    {
        return $this->set('disabledDate', $value);
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
