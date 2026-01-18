<?php
namespace warm\admin\renderer\form;

use warm\admin\renderer\BaseRenderer;
use warm\admin\renderer\trait\FormItem;

/**
 * InputColor 颜色选择器
 *
 * @see https://aisuda.bce.baidu.com/amis/zh-CN/components/input-color
 */
class InputColor extends BaseRenderer
{
    use FormItem;

    public string $type = 'input-color';

    /**
     * 请选择 `hex`、`hexa`、`hls`、`rgb`或者`rgba`。
     *
     * @param string $value
     * @return self
     */
    public function format(string $value = 'hex'): static
    {
        return $this->set('format', $value);
    }

    /**
     * 选择器底部的默认颜色，数组内为空则不显示默认颜色
     *
     * @param array $value
     * @return self
     */
    public function presetColors(array $value = []): static
    {
        return $this->set('presetColors', $value);
    }

    /**
     * 为`false`时只能选择颜色，使用 `presetColors` 设定颜色选择范围
     *
     * @param bool $value
     * @return self
     */
    public function allowCustomColor(bool $value = true): static
    {
        return $this->set('allowCustomColor', $value);
    }

    /**
     * 是否显示清除按钮
     *
     * @param bool $value
     * @return self
     */
    public function clearable(bool $value = true): static
    {
        return $this->set('clearable', $value);
    }

    /**
     * 清除后，表单项值调整成该值
     *
     * @param string $value
     * @return self
     */
    public function resetValue(string $value = ''): static
    {
        return $this->set('resetValue', $value);
    }
}
