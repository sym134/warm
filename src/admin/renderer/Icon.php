<?php
namespace warm\admin\renderer;
/**
 * Icon
 *
 * @see https://aisuda.bce.baidu.com/amis/zh-CN/components/icon
 */
class Icon extends BaseRenderer
{
    public string $type = 'icon';

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
     * icon 名称，支持 [fontawesome v4](https://fontawesome.com/v4/icons/) 或 通过 registerIcon 注册的 icon、或使用 url
     *
     * @param mixed $value
     * @return self
     */
    public function icon(mixed $value = null): self
    {
        return $this->set('icon', $value);
    }

    /**
     * icon 类型，默认为`fa`, 表示 fontawesome v4。也支持 iconfont, 如果是 fontawesome v5 以上版本或者其他框架可以设置为空字符串
     *
     * @param string $value
     * @return self
     */
    public function vendor(string $value = ''): self
    {
        return $this->set('vendor', $value);
    }
}
