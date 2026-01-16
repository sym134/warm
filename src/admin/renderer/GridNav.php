<?php
namespace warm\admin\renderer;
/**
 * GridNav
 *
 * @see https://aisuda.bce.baidu.com/amis/zh-CN/components/grid-nav
 */
class GridNav extends BaseRenderer
{
    public string $type = 'grid-nav';

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
     * 列表项 css 类名
     *
     * @param string $value
     * @return self
     */
    public function itemClassName(string $value = ''): self
    {
        return $this->set('itemClassName', $value);
    }

    /**
     * 列表项内容 css 类名
     *
     * @param string $value
     * @return self
     */
    public function contentClassName(string $value = ''): self
    {
        return $this->set('contentClassName', $value);
    }

    /**
     * 图片数组
     *
     * @param array $value
     * @return self
     */
    public function value(array $value = []): self
    {
        return $this->set('value', $value);
    }

    /**
     * 数据源
     *
     * @param string $value
     * @return self
     */
    public function source(string $value = ''): self
    {
        return $this->set('source', $value);
    }

    /**
     * 是否将列表项固定为正方形
     *
     * @param bool $value
     * @return self
     */
    public function square(bool $value = true): self
    {
        return $this->set('square', $value);
    }

    /**
     * 是否将列表项内容居中显示
     *
     * @param bool $value
     * @return self
     */
    public function center(bool $value = true): self
    {
        return $this->set('center', $value);
    }

    /**
     * 是否显示列表项边框
     *
     * @param bool $value
     * @return self
     */
    public function border(bool $value = true): self
    {
        return $this->set('border', $value);
    }

    /**
     * 列表项之间的间距，默认单位为`px`
     *
     * @param int|float $value
     * @return self
     */
    public function gutter(int|float $value = 0): self
    {
        return $this->set('gutter', $value);
    }

    /**
     * 是否调换图标和文本的位置
     *
     * @param bool $value
     * @return self
     */
    public function reverse(bool $value = true): self
    {
        return $this->set('reverse', $value);
    }

    /**
     * 图标宽度占比，单位%
     *
     * @param int|float $value
     * @return self
     */
    public function iconRatio(int|float $value = 60): self
    {
        return $this->set('iconRatio', $value);
    }

    /**
     * 列表项内容排列的方向，可选值为 `horizontal` 、`vertical`
     *
     * @param string $value
     * @return self
     */
    public function direction(string $value = 'vertical'): self
    {
        return $this->set('direction', $value);
    }

    /**
     * 列数
     *
     * @param int|float $value
     * @return self
     */
    public function columnNum(int|float $value = 4): self
    {
        return $this->set('columnNum', $value);
    }
}
