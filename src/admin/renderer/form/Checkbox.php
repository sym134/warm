<?php
namespace warm\admin\renderer\form;

use warm\admin\renderer\BaseRenderer;
use warm\admin\renderer\trait\FormItem;
use warm\admin\renderer\trait\OnEvent;

/**
 * Checkbox
 *
 * @see https://aisuda.bce.baidu.com/amis/zh-CN/components/checkbox
 */
class Checkbox extends BaseRenderer
{
    use OnEvent;
    use FormItem;

    public string $type = 'checkbox';

    /**
     * 选项说明
     *
     * @param string $value
     * @return self
     */
    public function option(string $value = ''): static
    {
        return $this->set('option', $value);
    }

    /**
     * 标识真值
     *
     * @param mixed $value
     * @return self
     */
    public function trueValue(mixed $value = true): static
    {
        return $this->set('trueValue', $value);
    }

    /**
     * 标识假值
     *
     * @param mixed $value
     * @return self
     */
    public function falseValue(mixed $value = false): static
    {
        return $this->set('falseValue', $value);
    }

    /**
     * 设置 option 类型
     *
     * @param mixed $value
     * @return self
     */
    public function optionType(mixed $value = null): static
    {
        return $this->set('optionType', $value);
    }
}
