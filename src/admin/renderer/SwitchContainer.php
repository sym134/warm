<?php
namespace warm\admin\renderer;
/**
 * SwitchContainer
 *
 * @see https://aisuda.bce.baidu.com/amis/zh-CN/components/switch-container
 */
class SwitchContainer extends BaseRenderer
{
    public string $type = 'switch-container';

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
    public function items(mixed $value = null): self
    {
        return $this->set('items', $value);
    }
}
