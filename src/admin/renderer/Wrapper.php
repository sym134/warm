<?php
namespace warm\admin\renderer;
/**
 * Wrapper
 *
 * @see https://aisuda.bce.baidu.com/amis/zh-CN/components/wrapper
 */
class Wrapper extends BaseRenderer
{
    public string $type = 'wrapper';

    /**
     * 外层 Dom 的类名
     *
     * @param string $value
     * @return self
     */
    public function className(string $value = ''): self
    {
        return $this->set('className', $value);
    }

    /**
     * 支持: `xs`、`sm`、`md`和`lg`
     *
     * @param string $value
     * @return self
     */
    public function size(string $value = ''): self
    {
        return $this->set('size', $value);
    }

    /**
     * 
     *
     * @param mixed $value
     * @return self
     */
    public function style(mixed $value = null): self
    {
        return $this->set('style', $value);
    }

    /**
     * 内容容器
     *
     * @param mixed $value
     * @return self
     */
    public function body(mixed $value = null): self
    {
        return $this->set('body', $value);
    }
}
