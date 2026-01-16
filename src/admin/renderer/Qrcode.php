<?php
namespace warm\admin\renderer;
/**
 * Qrcode
 *
 * @see https://aisuda.bce.baidu.com/amis/zh-CN/components/qrcode
 */
class Qrcode extends BaseRenderer
{
    public string $type = 'qrcode';

    /**
     * 渲染模式，有`canvas`和`svg`两种
     *
     * @param string $value
     * @return self
     */
    public function mode(string $value = 'canvas'): self
    {
        return $this->set('mode', $value);
    }

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
     * 二维码的类名
     *
     * @param string $value
     * @return self
     */
    public function qrcodeClassName(string $value = ''): self
    {
        return $this->set('qrcodeClassName', $value);
    }

    /**
     * 二维码的宽高大小
     *
     * @param int|float $value
     * @return self
     */
    public function codeSize(int|float $value = 128): self
    {
        return $this->set('codeSize', $value);
    }

    /**
     * 二维码背景色
     *
     * @param string $value
     * @return self
     */
    public function backgroundColor(string $value = '#fff'): self
    {
        return $this->set('backgroundColor', $value);
    }

    /**
     * 二维码前景色
     *
     * @param string $value
     * @return self
     */
    public function foregroundColor(string $value = '#000'): self
    {
        return $this->set('foregroundColor', $value);
    }

    /**
     * 二维码复杂级别，有（'L' 'M' 'Q' 'H'）四种
     *
     * @param string $value
     * @return self
     */
    public function level(string $value = 'L'): self
    {
        return $this->set('level', $value);
    }

    /**
     * 扫描二维码后显示的文本，如果要显示某个页面请输入完整 url（`"http://..."`或`"https://..."`开头），支持使用 [模板](./concepts/template)
     *
     * @param mixed $value
     * @return self
     */
    public function value(mixed $value = null): self
    {
        return $this->set('value', $value);
    }

    /**
     * QRCode 图片配置
     *
     * @param array $value
     * @return self
     */
    public function imageSettings(array $value = []): self
    {
        return $this->set('imageSettings', $value);
    }

    /**
     * 码眼类型，有`default`、`circle`、`rounded`三种
     *
     * @param string $value
     * @return self
     */
    public function eyeType(string $value = 'default'): self
    {
        return $this->set('eyeType', $value);
    }

    /**
     * 码眼边框颜色
     *
     * @param string $value
     * @return self
     */
    public function eyeBorderColor(string $value = '#000000'): self
    {
        return $this->set('eyeBorderColor', $value);
    }

    /**
     * 码眼边框大小，有`default`、`sm`、`xs`三种
     *
     * @param string $value
     * @return self
     */
    public function eyeBorderSize(string $value = 'default'): self
    {
        return $this->set('eyeBorderSize', $value);
    }

    /**
     * 码眼内部颜色
     *
     * @param string $value
     * @return self
     */
    public function eyeInnerColor(string $value = '#000000'): self
    {
        return $this->set('eyeInnerColor', $value);
    }

    /**
     * 码点类型，有`default`、`circle`两种
     *
     * @param string $value
     * @return self
     */
    public function pointType(string $value = 'default'): self
    {
        return $this->set('pointType', $value);
    }

    /**
     * 码点大小，有`default`、`sm`、`xs`三种
     *
     * @param string $value
     * @return self
     */
    public function pointSize(string $value = 'default'): self
    {
        return $this->set('pointSize', $value);
    }

    /**
     * 码点大小随机
     *
     * @param bool $value
     * @return self
     */
    public function pointSizeRandom(bool $value = true): self
    {
        return $this->set('pointSizeRandom', $value);
    }
}
