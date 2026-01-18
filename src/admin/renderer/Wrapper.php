<?php
namespace warm\admin\renderer;
/**
 * Wrapper 包装器
 *
 * @see https://aisuda.bce.baidu.com/amis/zh-CN/components/wrapper
 */
class Wrapper extends BaseRenderer
{
    public string $type = 'wrapper';

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
     * 支持: `xs`、`sm`、`md`和`lg`
     *
     * @param string $value
     * @return self
     */
    public function size(string $value = ''): static
    {
        return $this->set('size', $value);
    }

    /**
     * 
     *
     * @param mixed $value
     * @return self
     */
    public function style(mixed $value = null): static
    {
        return $this->set('style', $value);
    }

    /**
     * 内容容器
     *
     * @param mixed $value
     * @return self
     */
    public function body(mixed $value = null): static
    {
        return $this->set('body', $value);
    }
}
