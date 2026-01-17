<?php
namespace warm\admin\renderer;
use warm\admin\renderer\trait\OnEvent;

/**
 * Tpl
 *
 * @see https://aisuda.bce.baidu.com/amis/zh-CN/components/tpl
 */
class Tpl extends BaseRenderer
{
    use OnEvent;

    public string $type = 'tpl';

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
     * 配置模板
     *
     * @param mixed $value
     * @return self
     */
    public function tpl(mixed $value = null): static
    {
        return $this->set('tpl', $value);
    }

    /**
     * 是否设置外层 DOM 节点的 title 属性为文本内容
     *
     * @param bool $value
     * @return self
     */
    public function showNativeTitle(bool $value = true): static
    {
        return $this->set('showNativeTitle', $value);
    }
}
