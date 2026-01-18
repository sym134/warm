<?php
namespace warm\admin\renderer\form;

use warm\admin\renderer\BaseRenderer;
use warm\admin\renderer\trait\NameAndLabel;
use warm\admin\renderer\trait\FormItem;

/**
 * InputSignature 签名
 *
 * @see https://aisuda.bce.baidu.com/amis/zh-CN/components/input-signature
 */
class InputSignature extends BaseRenderer
{
    use NameAndLabel;
    use FormItem;

    public string $type = 'input-signature';

    /**
     * 组件宽度，最小 300
     *
     * @param int|float $value
     * @return self
     */
    public function width(int|float $value = 0): static
    {
        return $this->set('width', $value);
    }

    /**
     * 组件高度，最小 160
     *
     * @param int|float $value
     * @return self
     */
    public function height(int|float $value = 0): static
    {
        return $this->set('height', $value);
    }

    /**
     * 手写字体颜色
     *
     * @param string $value
     * @return self
     */
    public function color(string $value = '#000'): static
    {
        return $this->set('color', $value);
    }

    /**
     * 面板背景颜色
     *
     * @param string $value
     * @return self
     */
    public function bgColor(string $value = '#EFEFEF'): static
    {
        return $this->set('bgColor', $value);
    }

    /**
     * 清空按钮名称
     *
     * @param string $value
     * @return self
     */
    public function clearBtnLabel(string $value = '清空'): static
    {
        return $this->set('clearBtnLabel', $value);
    }

    /**
     * 撤销按钮名称
     *
     * @param string $value
     * @return self
     */
    public function undoBtnLabel(string $value = '撤销'): static
    {
        return $this->set('undoBtnLabel', $value);
    }

    /**
     * 确认按钮名称
     *
     * @param string $value
     * @return self
     */
    public function confirmBtnLabel(string $value = '确认'): static
    {
        return $this->set('confirmBtnLabel', $value);
    }

    /**
     * 是否内嵌
     *
     * @param bool $value
     * @return self
     */
    public function embed(bool $value = true): static
    {
        return $this->set('embed', $value);
    }

    /**
     * 内嵌容器确认按钮名称
     *
     * @param string $value
     * @return self
     */
    public function embedConfirmLabel(string $value = '确认'): static
    {
        return $this->set('embedConfirmLabel', $value);
    }

    /**
     * 内嵌容器取消按钮名称
     *
     * @param string $value
     * @return self
     */
    public function ebmedCancelLabel(string $value = '取消'): static
    {
        return $this->set('ebmedCancelLabel', $value);
    }

    /**
     * 内嵌按钮图标
     *
     * @param string $value
     * @return self
     */
    public function embedBtnIcon(string $value = ''): static
    {
        return $this->set('embedBtnIcon', $value);
    }

    /**
     * 内嵌按钮文案
     *
     * @param string $value
     * @return self
     */
    public function embedBtnLabel(string $value = '点击签名'): static
    {
        return $this->set('embedBtnLabel', $value);
    }

    /**
     * 上传签名图片接口，仅在内嵌模式下生效
     *
     * @param mixed $value
     * @return self
     */
    public function uploadApi(mixed $value = null): static
    {
        return $this->set('uploadApi', $value);
    }
}