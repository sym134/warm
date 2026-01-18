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
    public function name(string $value = ''): static
    {
        return $this->set('name', $value);
    }

    /**
     * 静态值
     *
     * @param string $value
     * @return self
     */
    public function value(string $value = ''): static
    {
        return $this->set('value', $value);
    }

    /**
     * 设置组件样式
     *
     * @param mixed $value
     * @return $this
     */
    public function className(mixed $value=''): static
    {
        return $this->set('className', $value);
    }

    /**
     * 外部地址
     *
     * @param mixed $value
     * @return self
     */
    public function src(mixed $value = null): static
    {
        return $this->set('src', $value);
    }

    /**
     * 配置选项
     *
     * @param array $array
     * @return self
     */
    public function options(array $array): Markdown
    {
        return $this->set('options', $array);
    }
}
