<?php
namespace warm\admin\renderer\trait;

use warm\admin\renderer\BaseRenderer;

/**
 * Options
 *
 * @see https://aisuda.bce.baidu.com/amis/zh-CN/components/options
 */
trait Options
{
    use FormItem;

    /**
     * 是否自动选择第一个选项
     *
     * @param bool $value
     * @return static
     */
    public function selectFirst(bool $value = true): static
    {
        return $this->set('selectFirst', $value);
    }
    /**
     * 选项组，供用户选择
     *
     * @param array $value
     * @return self
     */
    public function options(array $value = []): static
    {
        return $this->set('options', $value);
    }

    /**
     * 选项组源，可通过数据映射获取当前数据域变量、或者配置 API 对象
     *
     * @param mixed $value
     * @return self
     */
    public function source(mixed $value = null): static
    {
        return $this->set('source', $value);
    }

    /**
     * 是否支持多选
     *
     * @param bool $value
     * @return self
     */
    public function multiple(bool $value = true): static
    {
        return $this->set('multiple', $value);
    }

    /**
     * 标识选项中哪个字段是`label`值
     *
     * @param bool $value
     * @return self
     */
    public function labelField(bool $value = true): static
    {
        return $this->set('labelField', $value);
    }

    /**
     * 标识选项中哪个字段是`value`值
     *
     * @param bool $value
     * @return self
     */
    public function valueField(bool $value = true): static
    {
        return $this->set('valueField', $value);
    }

    /**
     * 标识选项中哪个字段是`defer`值
     *
     * @param string $value
     * @return self
     */
    public function deferField(string $value = 'defer'): static
    {
        return $this->set('deferField', $value);
    }

    /**
     * 是否拼接`value`值
     *
     * @param bool $value
     * @return self
     */
    public function joinValues(bool $value = true): static
    {
        return $this->set('joinValues', $value);
    }

    /**
     * 是否将`value`值抽取出来组成新的数组，只有在`joinValues`是`false`是生效
     *
     * @param bool $value
     * @return self
     */
    public function extractValue(bool $value = true): static
    {
        return $this->set('extractValue', $value);
    }

    /**
     * 每个选项的高度，用于虚拟渲染
     *
     * @param int|float $value
     * @return self
     */
    public function itemHeight(int|float $value = 32): static
    {
        return $this->set('itemHeight', $value);
    }

    /**
     * 在选项数量超过多少时开启虚拟渲染
     *
     * @param int|float $value
     * @return self
     */
    public function virtualThreshold(int|float $value = 100): static
    {
        return $this->set('virtualThreshold', $value);
    }

    /**
     * 默认情况下多选所有选项都会显示，通过这个可以最多显示一行，超出的部分变成 ...
     *
     * @param bool $value
     * @return self
     */
    public function valuesNoWrap(bool $value = true): static
    {
        return $this->set('valuesNoWrap', $value);
    }

    /**
     * `source`从数据域取值时，数据域值变化后是否自动清空
     *
     * @param bool $value
     * @return self
     */
    public function clearValueOnSourceChange(bool $value = true): static
    {
        return $this->set('clearValueOnSourceChange', $value);
    }
}
