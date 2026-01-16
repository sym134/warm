<?php
namespace warm\admin\renderer;
/**
 * Carousel
 *
 * @see https://aisuda.bce.baidu.com/amis/zh-CN/components/carousel
 */
class Carousel extends BaseRenderer
{
    public string $type = 'carousel';

    /**
     * 外层 Dom 的类名
     *
     * @param string $value
     * @return self
     */
    public function className(string $value = 'panel-default'): self
    {
        return $this->set('className', $value);
    }

    /**
     * 轮播面板数据
     *
     * @param array $value
     * @return self
     */
    public function options(array $value = []): self
    {
        return $this->set('options', $value);
    }

    /**
     * 自定义`schema`来展示数据
     *
     * @param array $value
     * @return self
     */
    public function itemSchema(array $value = []): self
    {
        return $this->set('itemSchema', $value);
    }

    /**
     * 是否自动轮播
     *
     * @param bool $value
     * @return self
     */
    public function auto(bool $value = true): self
    {
        return $this->set('auto', $value);
    }

    /**
     * 切换动画间隔
     *
     * @param string $value
     * @return self
     */
    public function interval(string $value = '5s'): self
    {
        return $this->set('interval', $value);
    }

    /**
     * 切换动画时长（ms）
     *
     * @param int|float $value
     * @return self
     */
    public function duration(int|float $value = 500): self
    {
        return $this->set('duration', $value);
    }

    /**
     * 宽度
     *
     * @param string $value
     * @return self
     */
    public function width(string $value = 'auto'): self
    {
        return $this->set('width', $value);
    }

    /**
     * 高度
     *
     * @param string $value
     * @return self
     */
    public function height(string $value = '200px'): self
    {
        return $this->set('height', $value);
    }

    /**
     * 显示左右箭头、底部圆点索引
     *
     * @param array $value
     * @return self
     */
    public function controls(array $value = []): self
    {
        return $this->set('controls', $value);
    }

    /**
     * 左右箭头、底部圆点索引颜色，默认`light`，另有`dark`模式
     *
     * @param string $value
     * @return self
     */
    public function controlsTheme(string $value = 'light'): self
    {
        return $this->set('controlsTheme', $value);
    }

    /**
     * 切换动画效果，默认`fade`，另有`slide`模式，`marquee`跑马灯模式
     *
     * @param string $value
     * @return self
     */
    public function animation(string $value = 'fade'): self
    {
        return $this->set('animation', $value);
    }

    /**
     * "contain"`
     *
     * @param string $value
     * @return self
     */
    public function thumbMode(string $value = 'cover'): self
    {
        return $this->set('thumbMode', $value);
    }

    /**
     * 多图展示，count 表示展示的数量
     *
     * @param array $value
     * @return self
     */
    public function multiple(array $value = []): self
    {
        return $this->set('multiple', $value);
    }

    /**
     * 是否一直显示箭头，为 false 时鼠标 hover 才会显示
     *
     * @param bool $value
     * @return self
     */
    public function alwaysShowArrow(bool $value = true): self
    {
        return $this->set('alwaysShowArrow', $value);
    }

    /**
     * 自定义箭头图标
     *
     * @param mixed $value
     * @return self
     */
    public function icons(mixed $value = null): self
    {
        return $this->set('icons', $value);
    }
}
