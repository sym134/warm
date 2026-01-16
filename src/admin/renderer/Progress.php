<?php
namespace warm\admin\renderer;
/**
 * Progress
 *
 * @see https://aisuda.bce.baidu.com/amis/zh-CN/components/progress
 */
class Progress extends BaseRenderer
{
    public string $type = 'progress';

    /**
     * 进度「条」的类型，可选`line circle dashboard`
     *
     * @param string $value
     * @return self
     */
    public function mode(string $value = 'line'): self
    {
        return $this->set('mode', $value);
    }

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
     * 进度值
     *
     * @param mixed $value
     * @return self
     */
    public function value(mixed $value = null): self
    {
        return $this->set('value', $value);
    }

    /**
     * 占位文本
     *
     * @param string $value
     * @return self
     */
    public function placeholder(string $value = '-'): self
    {
        return $this->set('placeholder', $value);
    }

    /**
     * 是否展示进度文本
     *
     * @param bool $value
     * @return self
     */
    public function showLabel(bool $value = true): self
    {
        return $this->set('showLabel', $value);
    }

    /**
     * 背景是否显示条纹
     *
     * @param bool $value
     * @return self
     */
    public function stripe(bool $value = true): self
    {
        return $this->set('stripe', $value);
    }

    /**
     * type 为 line，可支持动画
     *
     * @param bool $value
     * @return self
     */
    public function animate(bool $value = true): self
    {
        return $this->set('animate', $value);
    }

    /**
     * Array<{value:number, color:string}>`
     *
     * @param mixed $value
     * @return self
     */
    public function map(mixed $value = null): self
    {
        return $this->set('map', $value);
    }

    /**
     * `-`
     *
     * @param mixed $value
     * @return self
     */
    public function threshold(mixed $value = null): self
    {
        return $this->set('threshold', $value);
    }

    /**
     * 是否显示阈值（刻度）数值
     *
     * @param bool $value
     * @return self
     */
    public function showThresholdText(bool $value = true): self
    {
        return $this->set('showThresholdText', $value);
    }

    /**
     * 自定义格式化内容
     *
     * @param string $value
     * @return self
     */
    public function valueTpl(string $value = '${value}%'): self
    {
        return $this->set('valueTpl', $value);
    }

    /**
     * 进度条线宽度
     *
     * @param int|float $value
     * @return self
     */
    public function strokeWidth(int|float $value = 0): self
    {
        return $this->set('strokeWidth', $value);
    }

    /**
     * 仪表盘缺角角度，可取值 0 ~ 295
     *
     * @param int|float $value
     * @return self
     */
    public function gapDegree(int|float $value = 75): self
    {
        return $this->set('gapDegree', $value);
    }

    /**
     * 仪表盘进度条缺口位置，可选`top bottom left right`
     *
     * @param string $value
     * @return self
     */
    public function gapPosition(string $value = 'bottom'): self
    {
        return $this->set('gapPosition', $value);
    }
}
