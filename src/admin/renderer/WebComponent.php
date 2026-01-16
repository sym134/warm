<?php
namespace warm\admin\renderer;
/**
 * WebComponent
 *
 * @see https://aisuda.bce.baidu.com/amis/zh-CN/components/web-component
 */
class WebComponent extends BaseRenderer
{
    public string $type = 'web-component';

    /**
     * 具体使用的 web-component 标签
     *
     * @param string $value
     * @return self
     */
    public function tag(string $value = ''): self
    {
        return $this->set('tag', $value);
    }

    /**
     * 标签上的属性
     *
     * @param array $value
     * @return self
     */
    public function props(array $value = []): self
    {
        return $this->set('props', $value);
    }

    /**
     * 子节点
     *
     * @param mixed $value
     * @return self
     */
    public function body(mixed $value = null): self
    {
        return $this->set('body', $value);
    }
}
