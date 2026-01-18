<?php
namespace warm\admin\renderer;
use warm\admin\renderer\trait\OnEvent;

/**
 * Image 图片
 *
 * @see https://aisuda.bce.baidu.com/amis/zh-CN/components/image
 */
class Image extends BaseRenderer
{
    use OnEvent;

    public string $type = 'image';

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
     * 组件内层 CSS 类名
     *
     * @param string $value
     * @return self
     */
    public function innerClassName(string $value = ''): static
    {
        return $this->set('innerClassName', $value);
    }

    /**
     * 图片 CSS 类名
     *
     * @param string $value
     * @return self
     */
    public function imageClassName(string $value = ''): static
    {
        return $this->set('imageClassName', $value);
    }

    /**
     * 图片缩率图 CSS 类名
     *
     * @param string $value
     * @return self
     */
    public function thumbClassName(string $value = ''): static
    {
        return $this->set('thumbClassName', $value);
    }

    /**
     * 图片缩率高度
     *
     * @param string $value
     * @return self
     */
    public function height(string $value = ''): static
    {
        return $this->set('height', $value);
    }

    /**
     * 图片缩率宽度
     *
     * @param string $value
     * @return self
     */
    public function width(string $value = ''): static
    {
        return $this->set('width', $value);
    }

    /**
     * 标题
     *
     * @param string $value
     * @return self
     */
    public function title(string $value = ''): static
    {
        return $this->set('title', $value);
    }

    /**
     * 描述
     *
     * @param string $value
     * @return self
     */
    public function imageCaption(string $value = ''): static
    {
        return $this->set('imageCaption', $value);
    }

    /**
     * 占位文本
     *
     * @param string $value
     * @return self
     */
    public function placeholder(string $value = ''): static
    {
        return $this->set('placeholder', $value);
    }

    /**
     * 无数据时显示的图片
     *
     * @param string $value
     * @return self
     */
    public function defaultImage(string $value = ''): static
    {
        return $this->set('defaultImage', $value);
    }

    /**
     * 缩略图地址
     *
     * @param string $value
     * @return self
     */
    public function src(string $value = ''): static
    {
        return $this->set('src', $value);
    }

    /**
     * 外部链接地址
     *
     * @param mixed $value
     * @return self
     */
    public function href(mixed $value = null): static
    {
        return $this->set('href', $value);
    }

    /**
     * 原图地址
     *
     * @param string $value
     * @return self
     */
    public function originalSrc(string $value = ''): static
    {
        return $this->set('originalSrc', $value);
    }

    /**
     * 支持放大预览
     *
     * @param bool $value
     * @return self
     */
    public function enlargeAble(bool $value = true): static
    {
        return $this->set('enlargeAble', $value);
    }

    /**
     * 放大预览的标题
     *
     * @param string $value
     * @return self
     */
    public function enlargeTitle(string $value = ''): static
    {
        return $this->set('enlargeTitle', $value);
    }

    /**
     * 放大预览的描述
     *
     * @param string $value
     * @return self
     */
    public function enlargeCaption(string $value = ''): static
    {
        return $this->set('enlargeCaption', $value);
    }

    /**
     * 在表格中，图片的放大功能会默认展示所有图片信息，设置为`false`将关闭放大模式下图片集列表的展示
     *
     * @param string $value
     * @return self
     */
    public function enlargeWithGallary(string $value = 'true'): static
    {
        return $this->set('enlargeWithGallary', $value);
    }

    /**
     * 预览图模式，可选：`'w-full'`, `'h-full'`, `'contain'`, `'cover'`
     *
     * @param string $value
     * @return self
     */
    public function thumbMode(string $value = 'contain'): static
    {
        return $this->set('thumbMode', $value);
    }

    /**
     * 预览图比例，可选：`'1:1'`, `'4:3'`, `'16:9'`
     *
     * @param string $value
     * @return self
     */
    public function thumbRatio(string $value = '1:1'): static
    {
        return $this->set('thumbRatio', $value);
    }

    /**
     * 图片展示模式，可选：`'thumb'`, `'original'` 即：缩略图模式 或者 原图模式
     *
     * @param string $value
     * @return self
     */
    public function imageMode(string $value = 'thumb'): static
    {
        return $this->set('imageMode', $value);
    }

    /**
     * 放大模式下是否展示图片的工具栏
     *
     * @param bool $value
     * @return self
     */
    public function showToolbar(bool $value = true): static
    {
        return $this->set('showToolbar', $value);
    }

    /**
     * 图片工具栏，支持旋转，缩放，默认操作全部开启
     *
     * @param array $value
     * @return self
     */
    public function toolbarActions(array $value = []): static
    {
        return $this->set('toolbarActions', $value);
    }

    /**
     * 执行调整图片比例动作时的最大百分比
     *
     * @param mixed $value
     * @return self
     */
    public function maxScale(mixed $value = null): static
    {
        return $this->set('maxScale', $value);
    }

    /**
     * 执行调整图片比例动作时的最小百分比
     *
     * @param mixed $value
     * @return self
     */
    public function minScale(mixed $value = null): static
    {
        return $this->set('minScale', $value);
    }
}
