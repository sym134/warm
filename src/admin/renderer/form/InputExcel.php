<?php
namespace warm\admin\renderer\form;

use warm\admin\renderer\BaseRenderer;

/**
 * InputExcel
 *
 * @see https://aisuda.bce.baidu.com/amis/zh-CN/components/input-excel
 */
class InputExcel extends BaseRenderer
{

    /**
     * 是否解析所有 sheet
     *
     * @param bool $value
     * @return self
     */
    public function allSheets(bool $value = true): static
    {
        return $this->set('allSheets', $value);
    }

    /**
     * 解析模式
     *
     * @param array $value
     * @return self
     */
    public function parseMode(array $value = []): static
    {
        return $this->set('parseMode', $value);
    }

    /**
     * 是否包含空值
     *
     * @param bool $value
     * @return self
     */
    public function includeEmpty(bool $value = true): static
    {
        return $this->set('includeEmpty', $value);
    }

    /**
     * 是否解析为纯文本
     *
     * @param bool $value
     * @return self
     */
    public function plainText(bool $value = true): static
    {
        return $this->set('plainText', $value);
    }

    /**
     * 占位文本提示
     *
     * @param string $value
     * @return self
     */
    public function placeholder(string $value = '拖拽 Excel 到这，或点击上传'): static
    {
        return $this->set('placeholder', $value);
    }

    /**
     * 自动填充
     *
     * @param mixed $value
     * @return self
     */
    public function autoFill(mixed $value = null): static
    {
        return $this->set('autoFill', $value);
    }

    /**
     * 解析多个文件
     *
     * @param bool $value
     * @return self
     */
    public function multiple(bool $value = true): static
    {
        return $this->set('multiple', $value);
    }

    /**
     * 解析文件最大数
     *
     * @param int|float $value
     * @return self
     */
    public function maxLength(int|float $value = 0): static
    {
        return $this->set('maxLength', $value);
    }
}
