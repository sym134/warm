<?php
namespace warm\admin\renderer;
/**
 * Iframe
 *
 * @see https://aisuda.bce.baidu.com/amis/zh-CN/components/iframe
 */
class Iframe extends BaseRenderer
{
    public string $type = 'iframe';

    /**
     * iFrame 的类名
     *
     * @param string $value
     * @return self
     */
    public function className(string $value = ''): self
    {
        return $this->set('className', $value);
    }

    /**
     * frameBorder
     *
     * @param array $value
     * @return self
     */
    public function frameBorder(array $value = []): self
    {
        return $this->set('frameBorder', $value);
    }

    /**
     * 样式对象
     *
     * @param array $value
     * @return self
     */
    public function style(array $value = []): self
    {
        return $this->set('style', $value);
    }

    /**
     * iframe 地址
     *
     * @param string $value
     * @return self
     */
    public function src(string $value = ''): self
    {
        return $this->set('src', $value);
    }

    /**
     * allow 配置
     *
     * @param string $value
     * @return self
     */
    public function allow(string $value = ''): self
    {
        return $this->set('allow', $value);
    }

    /**
     * sandbox 配置
     *
     * @param string $value
     * @return self
     */
    public function sandbox(string $value = ''): self
    {
        return $this->set('sandbox', $value);
    }

    /**
     * referrerpolicy 配置
     *
     * @param string $value
     * @return self
     */
    public function referrerpolicy(string $value = ''): self
    {
        return $this->set('referrerpolicy', $value);
    }

    /**
     * iframe 高度
     *
     * @param mixed $value
     * @return self
     */
    public function height(mixed $value = null): self
    {
        return $this->set('height', $value);
    }

    /**
     * iframe 宽度
     *
     * @param mixed $value
     * @return self
     */
    public function width(mixed $value = null): self
    {
        return $this->set('width', $value);
    }
}
