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
     * 外层 CSS 类名
     *
     * @param string $value
     * @return self
     */
    public function className(string $value = ''): self
    {
        return $this->set('className', $value);
    }

    /**
     * 提示文本
     *
     * @param string $value
     * @return self
     */
    public function content(string $value = ''): self
    {
        return $this->set('content', $value);
    }

    /**
     * 弹出位置
     *
     * @param string $value
     * @return self
     */
    public function placement(string $value = ''): self
    {
        return $this->set('placement', $value);
    }

    /**
     * 触发条件
     *
     * @param string $value
     * @return self
     */
    public function trigger(string $value = 'hover'): self
    {
        return $this->set('trigger', $value);
    }

    /**
     * 图标
     *
     * @param string $value
     * @return self
     */
    public function icon(string $value = 'fa fa-question-circle'): self
    {
        return $this->set('icon', $value);
    }

    /**
     * 图标形状
     *
     * @param mixed $value
     * @return self
     */
    public function shape(mixed $value = null): self
    {
        return $this->set('shape', $value);
    }
}
