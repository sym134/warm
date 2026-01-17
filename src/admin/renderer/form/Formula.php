<?php
namespace warm\admin\renderer\form;

use warm\admin\renderer\BaseRenderer;
use warm\admin\renderer\trait\FormItem;

/**
 * Formula
 *
 * @see https://aisuda.bce.baidu.com/amis/zh-CN/components/formula
 */
class Formula extends BaseRenderer
{
    use FormItem;

    public string $type = 'formula';

    /**
     * 需要应用的表单项`name`值，公式结果将作用到此处指定的变量中去。
     *
     * @param string $value
     * @return self
     */
    public function name(string $value = ''): static
    {
        return $this->set('name', $value);
    }

    /**
     * 应用的公式
     *
     * @param mixed $value
     * @return self
     */
    public function formula(mixed $value = null): static
    {
        return $this->set('formula', $value);
    }

    /**
     * 公式作用条件
     *
     * @param mixed $value
     * @return self
     */
    public function condition(mixed $value = null): static
    {
        return $this->set('condition', $value);
    }

    /**
     * 初始化时是否设置
     *
     * @param bool $value
     * @return self
     */
    public function initSet(bool $value = true): static
    {
        return $this->set('initSet', $value);
    }

    /**
     * 观察公式结果，如果计算结果有变化，则自动应用到变量上
     *
     * @param bool $value
     * @return self
     */
    public function autoSet(bool $value = true): static
    {
        return $this->set('autoSet', $value);
    }

    /**
     * 定义个名字，当某个按钮的目标指定为此值后，会触发一次公式应用。这个机制可以在 `autoSet` 为 false 时用来手动触发
     *
     * @param string|int $value
     * @return self
     */
    public function id(string|int $value = ''): static
    {
        return $this->set('id', $value);
    }
}
