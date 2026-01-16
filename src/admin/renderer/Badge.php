<?php
namespace warm\admin\renderer;
/**
 * Badge
 *
 * @see https://aisuda.bce.baidu.com/amis/zh-CN/components/badge
 */
class Badge extends BaseRenderer
{
    public string $type = 'badge';

    /**
     * 角标类型，可以是 dot/text/ribbon
     *
     * @param string $value
     * @return self
     */
    public function mode(string $value = 'dot'): self
    {
        return $this->set('mode', $value);
    }

    /**
     * 
     *
     * @param mixed $value
     * @return self
     */
    public function text(mixed $value = null): self
    {
        return $this->set('text', $value);
    }

    /**
     * 角标大小
     *
     * @param int|float $value
     * @return self
     */
    public function size(int|float $value = 0): self
    {
        return $this->set('size', $value);
    }

    /**
     * 角标级别, 可以是 info/success/warning/danger, 设置之后角标背景颜色不同
     *
     * @param string $value
     * @return self
     */
    public function level(string $value = ''): self
    {
        return $this->set('level', $value);
    }

    /**
     * 设置封顶的数字值
     *
     * @param int|float $value
     * @return self
     */
    public function overflowCount(int|float $value = 99): self
    {
        return $this->set('overflowCount', $value);
    }

    /**
     * 角标位置， 可以是 top-right/top-left/bottom-right/bottom-left
     *
     * @param string $value
     * @return self
     */
    public function position(string $value = 'top-right'): self
    {
        return $this->set('position', $value);
    }

    /**
     * 角标位置，offset 相对于 position 位置进行水平、垂直偏移
     *
     * @param mixed $value
     * @return self
     */
    public function offset(mixed $value = null): self
    {
        return $this->set('offset', $value);
    }

    /**
     * 外层 dom 的类名
     *
     * @param string $value
     * @return self
     */
    public function className(string $value = ''): self
    {
        return $this->set('className', $value);
    }

    /**
     * 角标是否显示动画
     *
     * @param bool $value
     * @return self
     */
    public function animation(bool $value = true): self
    {
        return $this->set('animation', $value);
    }

    /**
     * 角标的自定义样式
     *
     * @param array $value
     * @return self
     */
    public function style(array $value = []): self
    {
        return $this->set('style', $value);
    }

    /**
     * 控制角标的显示隐藏
     *
     * @param mixed $value
     * @return self
     */
    public function visibleOn(mixed $value = null): self
    {
        return $this->set('visibleOn', $value);
    }
}
