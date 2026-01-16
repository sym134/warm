<?php
namespace warm\admin\renderer;
/**
 * Tpl
 *
 * @see https://aisuda.bce.baidu.com/amis/zh-CN/components/tpl
 */
class Tpl extends BaseRenderer
{
    public string $type = 'tpl';

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
     * 配置模板
     *
     * @param mixed $value
     * @return self
     */
    public function tpl(mixed $value = null): self
    {
        return $this->set('tpl', $value);
    }

    /**
     * 是否设置外层 DOM 节点的 title 属性为文本内容
     *
     * @param bool $value
     * @return self
     */
    public function showNativeTitle(bool $value = true): self
    {
        return $this->set('showNativeTitle', $value);
    }
}
