<?php
namespace warm\admin\renderer;

use warm\admin\renderer\trait\OnEvent;

/**
 * Container 容器
 *
 * @see https://aisuda.bce.baidu.com/amis/zh-CN/components/container
 */
class Container extends BaseRenderer
{
    use OnEvent;
    public string $type = 'container';

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
     * 容器内容区的类名
     *
     * @param string $value
     * @return self
     */
    public function bodyClassName(string $value = ''): static
    {
        return $this->set('bodyClassName', $value);
    }

    /**
     * 容器标签名
     *
     * @param string $value
     * @return self
     */
    public function wrapperComponent(string $value = 'div'): static
    {
        return $this->set('wrapperComponent', $value);
    }

    /**
     * 自定义样式
     *
     * @param array $value
     * @return self
     */
    public function style(array $value = []): static
    {
        return $this->set('style', $value);
    }

    /**
     * 容器内容
     *
     * @param mixed $value
     * @return self
     */
    public function body(mixed $value = null): static
    {
        return $this->set('body', $value);
    }
}
