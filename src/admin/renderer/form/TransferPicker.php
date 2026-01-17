<?php
namespace warm\admin\renderer\form;

use warm\admin\renderer\BaseRenderer;
use warm\admin\renderer\trait\FormItem;

/**
 * TransferPicker
 *
 * @see https://aisuda.bce.baidu.com/amis/zh-CN/components/transfer-picker
 */
class TransferPicker extends Transfer
{
    public string $type = 'transfer-picker';

    /**
     * `'none'`
     *
     * @param mixed $value
     * @return self
     */
    public function borderMode(mixed $value = null): static
    {
        return $this->set('borderMode', $value);
    }

    /**
     * 弹窗大小，支持: xs、sm、md、lg、xl、full
     *
     * @param string $value
     * @return self
     */
    public function pickerSize(string $value = ''): static
    {
        return $this->set('pickerSize', $value);
    }
}
