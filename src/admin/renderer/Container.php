<?php
namespace warm\admin\renderer;
/**
 * Container
 *
 * @see https://aisuda.bce.baidu.com/amis/zh-CN/components/container
 */
class Container extends BaseRenderer
{
    public string $type = 'container';

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
     * 容器内容区的类名
     *
     * @param string $value
     * @return self
     */
    public function bodyClassName(string $value = ''): self
    {
        return $this->set('bodyClassName', $value);
    }

    /**
     * 容器标签名
     *
     * @param string $value
     * @return self
     */
    public function wrapperComponent(string $value = 'div'): self
    {
        return $this->set('wrapperComponent', $value);
    }

    /**
     * 自定义样式
     *
     * @param array $value
     * @return self
     */
    public function style(array $value = []): self
    {
        return $this->set('style', $value);
    }

    /**
     * 容器内容
     *
     * @param mixed $value
     * @return self
     */
    public function body(mixed $value = null): self
    {
        return $this->set('body', $value);
    }
}
