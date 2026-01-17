<?php
namespace warm\admin\renderer;
use warm\admin\renderer\trait\OnEvent;

/**
 * Slider
 *
 * @see https://aisuda.bce.baidu.com/amis/zh-CN/components/slider
 */
class Slider extends BaseRenderer
{
    use OnEvent;

    public string $type = 'slider';

    /**
     * 容器主要内容
     *
     * @param mixed $value
     * @return self
     */
    public function body(mixed $value = null): static
    {
        return $this->set('body', $value);
    }

    /**
     * 容器右侧内容，在 pc 下展示在右侧
     *
     * @param mixed $value
     * @return self
     */
    public function right(mixed $value = null): static
    {
        return $this->set('right', $value);
    }

    /**
     * 容器左侧内容，在 pc 下展示在右侧
     *
     * @param mixed $value
     * @return self
     */
    public function left(mixed $value = null): static
    {
        return $this->set('left', $value);
    }

    /**
     * pc 下 body 即移动端默认宽度占比，默认 60%
     *
     * @param string $value
     * @return self
     */
    public function bodyWidth(string $value = '60%'): static
    {
        return $this->set('bodyWidth', $value);
    }
}
