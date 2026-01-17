<?php
namespace warm\admin\renderer;
/**
 * PdfViewer
 *
 * @see https://aisuda.bce.baidu.com/amis/zh-CN/components/pdf-viewer
 */
class PdfViewer extends BaseRenderer
{
    public string $type = 'pdf-viewer';

    /**
     * 文档地址
     *
     * @param mixed $value
     * @return self
     */
    public function src(mixed $value = null): static
    {
        return $this->set('src', $value);
    }

    /**
     * 宽度
     *
     * @param int|float $value
     * @return self
     */
    public function width(int|float $value = 0): static
    {
        return $this->set('width', $value);
    }

    /**
     * 高度
     *
     * @param int|float $value
     * @return self
     */
    public function height(int|float $value = 0): static
    {
        return $this->set('height', $value);
    }

    /**
     * PDF 背景色
     *
     * @param string $value
     * @return self
     */
    public function background(string $value = '#fff'): static
    {
        return $this->set('background', $value);
    }
}
