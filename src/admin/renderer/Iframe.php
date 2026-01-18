<?php
namespace warm\admin\renderer;

use warm\admin\renderer\trait\OnEvent;

/**
 * Iframe iFrame
 *
 * @see https://aisuda.bce.baidu.com/amis/zh-CN/components/iframe
 */
class Iframe extends BaseRenderer
{
    use OnEvent;
    public string $type = 'iframe';

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
     * frameBorder
     *
     * @param array $value
     * @return self
     */
    public function frameBorder(array $value = []): static
    {
        return $this->set('frameBorder', $value);
    }

    /**
     * 样式对象
     *
     * @param array $value
     * @return self
     */
    public function style(array $value = []): static
    {
        return $this->set('style', $value);
    }

    /**
     * iframe 地址
     *
     * @param string $value
     * @return self
     */
    public function src(string $value = ''): static
    {
        return $this->set('src', $value);
    }

    /**
     * allow 配置
     *
     * @param string $value
     * @return self
     */
    public function allow(string $value = ''): static
    {
        return $this->set('allow', $value);
    }

    /**
     * sandbox 配置
     *
     * @param string $value
     * @return self
     */
    public function sandbox(string $value = ''): static
    {
        return $this->set('sandbox', $value);
    }

    /**
     * referrerpolicy 配置
     *
     * @param string $value
     * @return self
     */
    public function referrerpolicy(string $value = ''): static
    {
        return $this->set('referrerpolicy', $value);
    }

    /**
     * iframe 高度
     *
     * @param mixed $value
     * @return self
     */
    public function height(mixed $value = null): static
    {
        return $this->set('height', $value);
    }

    /**
     * iframe 宽度
     *
     * @param mixed $value
     * @return self
     */
    public function width(mixed $value = null): static
    {
        return $this->set('width', $value);
    }
}
