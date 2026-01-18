<?php
namespace warm\admin\renderer;
/**
 * Images 图片集
 *
 * @see https://aisuda.bce.baidu.com/amis/zh-CN/components/images
 */
class Images extends BaseRenderer
{
    public string $type = 'images';

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
     * 默认展示图片
     *
     * @param string $value
     * @return self
     */
    public function defaultImage(string $value = ''): static
    {
        return $this->set('defaultImage', $value);
    }

    /**
     * 图片数组
     *
     * @param array $value
     * @return self
     */
    public function value(array $value = []): static
    {
        return $this->set('value', $value);
    }

    /**
     * 数据源
     *
     * @param string $value
     * @return self
     */
    public function source(string $value = ''): static
    {
        return $this->set('source', $value);
    }

    /**
     * 分隔符，当 value 为字符串时，用该值进行分隔拆分
     *
     * @param string $value
     * @return self
     */
    public function delimiter(string $value = ','): static
    {
        return $this->set('delimiter', $value);
    }

    /**
     * 预览图地址，支持数据映射获取对象中图片变量
     *
     * @param string $value
     * @return self
     */
    public function src(string $value = ''): static
    {
        return $this->set('src', $value);
    }

    /**
     * 原图地址，支持数据映射获取对象中图片变量
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
     * 默认在放大功能展示图片集的所有图片信息；表格中使用时，设置为`true`将展示所有行的图片信息；设置为`false`将关闭放大模式下图片集列表的展示
     *
     * @param string $value
     * @return self
     */
    public function enlargeWithGallary(string $value = ''): static
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
     * 图集排列方式
     *
     * @param mixed $value
     * @return self
     */
    public function sortType(mixed $value = null): static
    {
        return $this->set('sortType', $value);
    }

    /**
     * 鼠标悬浮时的展示状态
     *
     * @param mixed $value
     * @return self
     */
    public function hoverMode(mixed $value = null): static
    {
        return $this->set('hoverMode', $value);
    }

    /**
     * 字体样式
     *
     * @param mixed $value
     * @return self
     */
    public function fontStyle(mixed $value = null): static
    {
        return $this->set('fontStyle', $value);
    }

    /**
     * 遮罩层颜色，可以是rgba值，可以是图片
     *
     * @param string $value
     * @return self
     */
    public function maskColor(string $value = ''): static
    {
        return $this->set('maskColor', $value);
    }

    /**
     * `'thumb'`
     *
     * @param mixed $value
     * @return self
     */
    public function displayMode(mixed $value = null): static
    {
        return $this->set('displayMode', $value);
    }

    /**
     * `'cover'`
     *
     * @param mixed $value
     * @return self
     */
    public function fullThumbMode(mixed $value = null): static
    {
        return $this->set('fullThumbMode', $value);
    }
}
