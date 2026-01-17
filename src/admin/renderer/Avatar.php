<?php
namespace warm\admin\renderer;

use warm\admin\renderer\trait\OnEvent;

/**
 * Avatar
 *
 * @see https://aisuda.bce.baidu.com/amis/zh-CN/components/avatar
 */
class Avatar extends BaseRenderer
{
    use OnEvent;
    public string $type = 'avatar';

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
     * 外层 dom 的样式
     *
     * @param array $value
     * @return self
     */
    public function style(array $value = []): static
    {
        return $this->set('style', $value);
    }

    /**
     * `'fill'` \
     *
     * @param mixed $value
     * @return self
     */
    public function fit(mixed $value = null): static
    {
        return $this->set('fit', $value);
    }

    /**
     * 图片地址
     *
     * @param string $value
     * @return self
     */
    public function src(string $value = ''): static
    {
        return $this->set('src', $value);
    }

    /**
     * 占位图
     *
     * @param string $value
     * @return self
     */
    public function defaultAvatar(string $value = ''): static
    {
        return $this->set('defaultAvatar', $value);
    }

    /**
     * 文字
     *
     * @param string $value
     * @return self
     */
    public function text(string $value = ''): static
    {
        return $this->set('text', $value);
    }

    /**
     * 图标
     *
     * @param string $value
     * @return self
     */
    public function icon(string $value = 'fa fa-user'): static
    {
        return $this->set('icon', $value);
    }

    /**
     * `'rounded'`
     *
     * @param mixed $value
     * @return self
     */
    public function shape(mixed $value = null): static
    {
        return $this->set('shape', $value);
    }

    /**
     * `'normal'` \
     *
     * @param mixed $value
     * @return self
     */
    public function size(mixed $value = null): static
    {
        return $this->set('size', $value);
    }

    /**
     * 控制字符类型距离左右两侧边界单位像素
     *
     * @param int|float $value
     * @return self
     */
    public function gap(int|float $value = 4): static
    {
        return $this->set('gap', $value);
    }

    /**
     * 图像无法显示时的替代文本
     *
     * @param int|float $value
     * @return self
     */
    public function alt(int|float $value = 0): static
    {
        return $this->set('alt', $value);
    }

    /**
     * 图片是否允许拖动
     *
     * @param bool $value
     * @return self
     */
    public function draggable(bool $value = true): static
    {
        return $this->set('draggable', $value);
    }

    /**
     * `''`
     *
     * @param mixed $value
     * @return self
     */
    public function crossOrigin(mixed $value = null): static
    {
        return $this->set('crossOrigin', $value);
    }

    /**
     * 图片加载失败的字符串，这个字符串是一个 New Function 内部执行的字符串，参数是 event（使用 event.nativeEvent 获取原生 dom 事件），这个字符串需要返回 boolean 值。设置 `"return ture;"` 会在图片加载失败后，使用 `text` 或者 `icon` 代表的信息来进行替换。目前图片加载失败默认是不进行置换。注意：图片加载失败，不包括$获取数据为空情况
     *
     * @param string $value
     * @return self
     */
    public function onError(string $value = ''): static
    {
        return $this->set('onError', $value);
    }
}
