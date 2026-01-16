<?php
namespace warm\admin\renderer;
/**
 * Barcode
 *
 * @see https://aisuda.bce.baidu.com/amis/zh-CN/components/barcode
 */
class Barcode extends BaseRenderer
{
    public string $type = 'barcode';

    /**
     * 外层 CSS 类名
     *
     * @param string $value
     * @return self
     */
    public function className(string $value = ''): self
    {
        return $this->set('className', $value);
    }

    /**
     * 显示的颜色值
     *
     * @param string $value
     * @return self
     */
    public function value(string $value = ''): self
    {
        return $this->set('value', $value);
    }

    /**
     * 在其他组件中，时，用作变量映射
     *
     * @param string $value
     * @return self
     */
    public function name(string $value = ''): self
    {
        return $this->set('name', $value);
    }
}
