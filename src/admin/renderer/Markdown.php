<?php
namespace warm\admin\renderer;
/**
 * Markdown
 *
 * @see https://aisuda.bce.baidu.com/amis/zh-CN/components/markdown
 */
class Markdown extends BaseRenderer
{
    public string $type = 'markdown';

    /**
     * 名称
     *
     * @param string $value
     * @return self
     */
    public function name(string $value = ''): self
    {
        return $this->set('name', $value);
    }

    /**
     * 静态值
     *
     * @param string $value
     * @return self
     */
    public function value(string $value = ''): self
    {
        return $this->set('value', $value);
    }

    /**
     * 类名
     *
     * @param string $value
     * @return self
     */
    public function className(string $value = ''): self
    {
        return $this->set('className', $value);
    }

    /**
     * 外部地址
     *
     * @param mixed $value
     * @return self
     */
    public function src(mixed $value = null): self
    {
        return $this->set('src', $value);
    }
}
