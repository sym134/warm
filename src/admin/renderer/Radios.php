<?php

declare(strict_types=1);

namespace warm\admin\renderer;

use warm\admin\renderer\trait\FormItem;
use warm\admin\renderer\trait\OnEvent;
use warm\admin\renderer\trait\Options;


/**
 * Radios 单选框组
 *
 * @see https://aisuda.bce.baidu.com/amis/zh-CN/components/radios
 */
class Radios extends BaseRenderer
{
    use FormItem;
    use OnEvent;
    use Options;

    public string $type = 'radios';

    /**
     * [选项组](./options#%E9%9D%99%E6%80%81%E9%80%89%E9%A1%B9%E7%BB%84-options)
     *
     * @param array $value
     * @return self
     */
    public function options(array $value = []): static
    {
        return $this->set('options', $value);
    }

    /**
     * [动态选项组](./options#%E5%8A%A8%E6%80%81%E9%80%89%E9%A1%B9%E7%BB%84-source)
     *
     * @param mixed $value
     * @return self
     */
    public function source(mixed $value = null): static
    {
        return $this->set('source', $value);
    }

    /**
     * [选项标签字段](./options#%E9%80%89%E9%A1%B9%E6%A0%87%E7%AD%BE%E5%AD%97%E6%AE%B5-labelfield)
     *
     * @param bool $value
     * @return self
     */
    public function labelField(bool $value = true): static
    {
        return $this->set('labelField', $value);
    }

    /**
     * [选项值字段](./options#%E9%80%89%E9%A1%B9%E5%80%BC%E5%AD%97%E6%AE%B5-valuefield)
     *
     * @param bool $value
     * @return self
     */
    public function valueField(bool $value = true): static
    {
        return $this->set('valueField', $value);
    }

    /**
     * 选项按几列显示，默认为一列
     *
     * @param int|float $value
     * @return self
     */
    public function columnsCount(int|float $value = 1): static
    {
        return $this->set('columnsCount', $value);
    }

    /**
     * [自动填充](./options#%E8%87%AA%E5%8A%A8%E5%A1%AB%E5%85%85-autofill)
     *
     * @param array $value
     * @return self
     */
    public function autoFill(array $value = []): static
    {
        return $this->set('autoFill', $value);
    }

    /**
     * 选项 CSS 类名
     *
     * @param string $value
     * @return self
     */
    public function optionClassName(string $value = ''): static
    {
        return $this->set('optionClassName', $value);
    }
}
