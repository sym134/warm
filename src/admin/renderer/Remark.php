<?php
namespace warm\admin\renderer;
/**
 * Remark
 *
 * @see https://aisuda.bce.baidu.com/amis/zh-CN/components/remark
 */
class Remark extends BaseRenderer
{
    public string $type = 'remark';

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
     * 提示文本
     *
     * @param string $value
     * @return self
     */
    public function content(string $value = ''): static
    {
        return $this->set('content', $value);
    }

    /**
     * 弹出位置
     *
     * @param string $value
     * @return self
     */
    public function placement(string $value = ''): static
    {
        return $this->set('placement', $value);
    }

    /**
     * 触发条件
     *
     * @param string $value
     * @return self
     */
    public function trigger(string $value = 'hover'): static
    {
        return $this->set('trigger', $value);
    }

    /**
     * 图标
     *
     * @param string $value
     * @return self
     */
    public function icon(string $value = 'fa fa-question-circle'): static
    {
        return $this->set('icon', $value);
    }

    /**
     * 图标形状
     *
     * @param mixed $value
     * @return self
     */
    public function shape(mixed $value = null): static
    {
        return $this->set('shape', $value);
    }
}
