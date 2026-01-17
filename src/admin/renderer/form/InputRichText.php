<?php
namespace warm\admin\renderer\form;

use warm\admin\renderer\BaseRenderer;
use warm\admin\renderer\trait\FormItem;

/**
 * InputRichText
 *
 * @see https://aisuda.bce.baidu.com/amis/zh-CN/components/input-rich-text
 */
class InputRichText extends BaseRenderer
{
    use FormItem;

    public string $type = 'input-rich-text';

    /**
     * 是否保存为 ubb 格式
     *
     * @param bool $value
     * @return self
     */
    public function saveAsUbb(bool $value = true): static
    {
        return $this->set('saveAsUbb', $value);
    }

    /**
     * 默认的图片保存 API
     *
     * @param mixed $value
     * @return self
     */
    public function receiver(mixed $value = null): static
    {
        return $this->set('receiver', $value);
    }

    /**
     * 默认的视频保存 API `仅支持 froala 编辑器`
     *
     * @param mixed $value
     * @return self
     */
    public function videoReceiver(mixed $value = null): static
    {
        return $this->set('videoReceiver', $value);
    }

    /**
     * 上传文件时的字段名
     *
     * @param string $value
     * @return self
     */
    public function fileField(string $value = ''): static
    {
        return $this->set('fileField', $value);
    }

    /**
     * 框的大小，可设置为 `md` 或者 `lg`
     *
     * @param string $value
     * @return self
     */
    public function size(string $value = ''): static
    {
        return $this->set('size', $value);
    }

    /**
     * 需要参考 [tinymce](https://www.tiny.cloud/docs/configure/integration-and-setup/) 或 [froala](https://www.froala.com/wysiwyg-editor/docs/options) 的文档
     *
     * @param array $value
     * @return self
     */
    public function options(array $value = []): static
    {
        return $this->set('options', $value);
    }

    /**
     * froala 专用，配置显示的按钮，tinymce 可以通过前面的 options 设置 [toolbar](https://www.tiny.cloud/docs/demo/custom-toolbar-button/) 字符串
     *
     * @param array $value
     * @return self
     */
    public function buttons(array $value = []): static
    {
        return $this->set('buttons', $value);
    }
}
