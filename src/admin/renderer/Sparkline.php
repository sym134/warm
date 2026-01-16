<?php
namespace warm\admin\renderer;
/**
 * Sparkline
 *
 * @see https://aisuda.bce.baidu.com/amis/zh-CN/components/sparkline
 */
class Sparkline extends BaseRenderer
{
    public string $type = 'sparkline';

    /**
     * 关联的变量
     *
     * @param string $value
     * @return self
     */
    public function name(string $value = ''): self
    {
        return $this->set('name', $value);
    }

    /**
     * 宽度
     *
     * @param int|float $value
     * @return self
     */
    public function width(int|float $value = 0): self
    {
        return $this->set('width', $value);
    }

    /**
     * 高度
     *
     * @param int|float $value
     * @return self
     */
    public function height(int|float $value = 0): self
    {
        return $this->set('height', $value);
    }

    /**
     * 数据为空时显示的内容
     *
     * @param string $value
     * @return self
     */
    public function placeholder(string $value = ''): self
    {
        return $this->set('placeholder', $value);
    }
}
