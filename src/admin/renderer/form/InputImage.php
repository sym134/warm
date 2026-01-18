<?php
namespace warm\admin\renderer\form;

use warm\admin\renderer\BaseRenderer;
use warm\admin\renderer\trait\FormItem;
use warm\admin\renderer\trait\OnEvent;

/**
 * InputImage 图片上传
 *
 * @see https://aisuda.bce.baidu.com/amis/zh-CN/components/input-image
 */
class InputImage extends BaseRenderer
{
    use FormItem;
    use OnEvent;

    public string $type = 'input-image';

    /**
     * 上传文件接口
     *
     * @param mixed $value
     * @return self
     */
    public function receiver(mixed $value = null): static
    {
        return $this->set('receiver', $value);
    }

    /**
     * 支持的图片类型格式，请配置此属性为图片后缀，例如`.jpg,.png`
     *
     * @param string $value
     * @return self
     */
    public function accept(string $value = '.jpeg,.jpg,.png,.gif'): static
    {
        return $this->set('accept', $value);
    }

    /**
     * 用于控制 input[type=file] 标签的 capture 属性，在移动端可控制输入来源
     *
     * @param string $value
     * @return self
     */
    public function capture(string $value = 'undefined'): static
    {
        return $this->set('capture', $value);
    }

    /**
     * 默认没有限制，当设置后，文件大小大于此值将不允许上传。单位为`B`
     *
     * @param int|float $value
     * @return self
     */
    public function maxSize(int|float $value = 0): static
    {
        return $this->set('maxSize', $value);
    }

    /**
     * 默认没有限制，当设置后，一次只允许上传指定数量文件。
     *
     * @param int|float $value
     * @return self
     */
    public function maxLength(int|float $value = 0): static
    {
        return $this->set('maxLength', $value);
    }

    /**
     * 是否多选。
     *
     * @param bool $value
     * @return self
     */
    public function multiple(bool $value = true): static
    {
        return $this->set('multiple', $value);
    }

    /**
     * [拼接值](./options#%E6%8B%BC%E6%8E%A5%E5%80%BC-joinvalues)
     *
     * @param bool $value
     * @return self
     */
    public function joinValues(bool $value = true): static
    {
        return $this->set('joinValues', $value);
    }

    /**
     * [提取值](./options#%E6%8F%90%E5%8F%96%E5%A4%9A%E9%80%89%E5%80%BC-extractvalue)
     *
     * @param bool $value
     * @return self
     */
    public function extractValue(bool $value = true): static
    {
        return $this->set('extractValue', $value);
    }

    /**
     * [拼接符](./options#%E6%8B%BC%E6%8E%A5%E7%AC%A6-delimiter)
     *
     * @param string $value
     * @return self
     */
    public function delimiter(string $value = ','): static
    {
        return $this->set('delimiter', $value);
    }

    /**
     * 否选择完就自动开始上传
     *
     * @param bool $value
     * @return self
     */
    public function autoUpload(bool $value = true): static
    {
        return $this->set('autoUpload', $value);
    }

    /**
     * 隐藏上传按钮
     *
     * @param bool $value
     * @return self
     */
    public function hideUploadButton(bool $value = true): static
    {
        return $this->set('hideUploadButton', $value);
    }

    /**
     * 如果你不想自己存储，则可以忽略此属性。
     *
     * @param string $value
     * @return self
     */
    public function fileField(string $value = 'file'): static
    {
        return $this->set('fileField', $value);
    }

    /**
     * 用来设置是否支持裁剪。
     *
     * @param mixed $value
     * @return self
     */
    public function crop(mixed $value = null): static
    {
        return $this->set('crop', $value);
    }

    /**
     * 裁剪文件格式
     *
     * @param string $value
     * @return self
     */
    public function cropFormat(string $value = 'image/png'): static
    {
        return $this->set('cropFormat', $value);
    }

    /**
     * 裁剪文件格式的质量，用于 jpeg/webp，取值在 0 和 1 之间
     *
     * @param int|float $value
     * @return self
     */
    public function cropQuality(int|float $value = 1): static
    {
        return $this->set('cropQuality', $value);
    }

    /**
     * 限制图片大小，超出不让上传。
     *
     * @param mixed $value
     * @return self
     */
    public function limit(mixed $value = null): static
    {
        return $this->set('limit', $value);
    }

    /**
     * 默认占位图地址
     *
     * @param string $value
     * @return self
     */
    public function frameImage(string $value = ''): static
    {
        return $this->set('frameImage', $value);
    }

    /**
     * 是否开启固定尺寸,若开启，需同时设置 fixedSizeClassName
     *
     * @param bool $value
     * @return self
     */
    public function fixedSize(bool $value = true): static
    {
        return $this->set('fixedSize', $value);
    }

    /**
     * 开启固定尺寸时，根据此值控制展示尺寸。例如`h-30`,即图片框高为 h-30,AMIS 将自动缩放比率设置默认图所占位置的宽度，最终上传图片根据此尺寸对应缩放。
     *
     * @param string $value
     * @return self
     */
    public function fixedSizeClassName(string $value = ''): static
    {
        return $this->set('fixedSizeClassName', $value);
    }

    /**
     * 表单反显时是否执行 autoFill
     *
     * @param bool $value
     * @return self
     */
    public function initAutoFill(bool $value = true): static
    {
        return $this->set('initAutoFill', $value);
    }

    /**
     * 
     *
     * @param mixed $value
     * @return self
     */
    public function uploadBtnText(mixed $value = null): static
    {
        return $this->set('uploadBtnText', $value);
    }

    /**
     * 图片上传后是否进入裁剪模式
     *
     * @param bool $value
     * @return self
     */
    public function dropCrop(bool $value = true): static
    {
        return $this->set('dropCrop', $value);
    }

    /**
     * 图片选择器初始化后是否立即进入裁剪模式
     *
     * @param bool $value
     * @return self
     */
    public function initCrop(bool $value = true): static
    {
        return $this->set('initCrop', $value);
    }

    /**
     * 开启后支持拖拽排序改变图片值顺序
     *
     * @param bool $value
     * @return self
     */
    public function draggable(bool $value = true): static
    {
        return $this->set('draggable', $value);
    }

    /**
     * 拖拽提示文案
     *
     * @param string $value
     * @return self
     */
    public function draggableTip(string $value = '拖拽排序'): static
    {
        return $this->set('draggableTip', $value);
    }

    /**
     * 校验失败后是否弹窗提醒
     *
     * @param bool $value
     * @return self
     */
    public function showErrorModal(bool $value = true): static
    {
        return $this->set('showErrorModal', $value);
    }

    /**
     * 校验格式失败后的提示信息
     *
     * @param string $value
     * @return self
     */
    public function invalidTypeMessage(string $value = '文件格式不正确'): static
    {
        return $this->set('invalidTypeMessage', $value);
    }

    /**
     * 校验文件大小失败时显示的文字信息
     *
     * @param string $value
     * @return self
     */
    public function invalidSizeMessage(string $value = '文件大小超出限制'): static
    {
        return $this->set('invalidSizeMessage', $value);
    }
}
