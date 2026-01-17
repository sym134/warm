<?php
namespace warm\admin\renderer;
/**
 * Icon
 *
 * @see https://aisuda.bce.baidu.com/amis/zh-CN/components/icon
 */
class Icon extends BaseRenderer
{/**
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
     * icon 名称，支持 [fontawesome v4](https://fontawesome.com/v4/icons/) 或 通过 registerIcon 注册的 icon、或使用 url
     *
     * @param mixed $value
     * @return self
     */
    public function icon(mixed $value = null): static
    {
        return $this->set('icon', $value);
    }

    /**
     * icon 类型，默认为`fa`, 表示 fontawesome v4。也支持 iconfont, 如果是 fontawesome v5 以上版本或者其他框架可以设置为空字符串
     *
     * @param string $value
     * @return self
     */
    public function vendor(string $value = ''): static
    {
        return $this->set('vendor', $value);
    }
}
