<?php

namespace warm\admin\renderer;
/**
 * Date
 *
 * @see https://aisuda.bce.baidu.com/amis/zh-CN/components/date
 */
class Date extends BaseRenderer
{
    public string $type = 'date';

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
     * 显示的日期数值
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
     * 展示格式, 更多格式类型请参考 [文档](https://momentjs.com/docs/#/displaying/format/)
     *
     * @param string $value
     * @return self
     */
    public function displayFormat(string $value = 'YYYY-MM-DD'): static
    {
        return $this->set('displayFormat', $value);
    }

    /**
     * 数据格式，默认为时间戳。更多格式类型请参考 [文档](https://momentjs.com/docs/#/displaying/format/)
     *
     * @param string $value
     * @return self
     */
    public function valueFormat(string $value = 'X'): static
    {
        return $this->set('valueFormat', $value);
    }

    /**
     * 是否显示相对当前的时间描述，比如: 11 小时前、3 天前、1 年前等，fromNow 为 true 时，format 不生效。
     *
     * @param bool $value
     * @return self
     */
    public function fromNow(bool $value = true): static
    {
        return $this->set('fromNow', $value);
    }

    /**
     * 更新频率， 默认为 1 分钟
     *
     * @param int|float $value
     * @return self
     */
    public function updateFrequency(int|float $value = 60000): static
    {
        return $this->set('updateFrequency', $value);
    }

    /**
     * 设置日期展示时区，可设置清单参考：https://gist.github.com/diogocapela/12c6617fc87607d11fd62d2a4f42b02a
     *
     * @param string $value
     * @return self
     */
    public function displayTimeZone(string $value = ''): static
    {
        return $this->set('displayTimeZone', $value);
    }
}
 