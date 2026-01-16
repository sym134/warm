<?php
namespace warm\admin\renderer;
/**
 * Grid2d
 *
 * @see https://aisuda.bce.baidu.com/amis/zh-CN/components/grid-2d
 */
class Grid2d extends BaseRenderer
{
    public string $type = 'grid-2d';

    /**
     * 外层 Dom 的类名
     *
     * @param string $value
     * @return self
     */
    public function gridClassName(string $value = ''): self
    {
        return $this->set('gridClassName', $value);
    }

    /**
     * 格子间距，包括水平和垂直
     *
     * @param mixed $value
     * @return self
     */
    public function gap(mixed $value = null): self
    {
        return $this->set('gap', $value);
    }

    /**
     * 格子水平划分为几个区域
     *
     * @param int|float $value
     * @return self
     */
    public function cols(int|float $value = 12): self
    {
        return $this->set('cols', $value);
    }

    /**
     * 每个格子默认垂直高度
     *
     * @param int|float $value
     * @return self
     */
    public function rowHeight(int|float $value = 50): self
    {
        return $this->set('rowHeight', $value);
    }

    /**
     * 格子垂直间距
     *
     * @param mixed $value
     * @return self
     */
    public function rowGap(mixed $value = null): self
    {
        return $this->set('rowGap', $value);
    }

    /**
     * 格子可以是其他渲染器
     *
     * @param mixed $value
     * @return self
     */
    public function grids(mixed $value = null): self
    {
        return $this->set('grids', $value);
    }
}
