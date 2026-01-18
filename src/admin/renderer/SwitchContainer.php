<?php
namespace warm\admin\renderer;
use warm\admin\renderer\trait\OnEvent;

/**
 * SwitchContainer 开关容器
 *
 * @see https://aisuda.bce.baidu.com/amis/zh-CN/components/switch-container
 */
class SwitchContainer extends BaseRenderer
{
    use OnEvent;
    public string $type = 'switch-container';

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
    public function items(mixed $value = null): static
    {
        return $this->set('items', $value);
    }
}
