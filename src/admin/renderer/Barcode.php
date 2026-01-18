<?php
namespace warm\admin\renderer;
/**
 * Barcode 条形码
 *
 * @see https://aisuda.bce.baidu.com/amis/zh-CN/components/barcode
 */
class Barcode extends BaseRenderer
{
    public string $type = 'barcode';

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
     * 显示的颜色值
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
}
