<?php

namespace warm\admin\renderer;
/**
 * Breadcrumb 面包屑
 *
 * @see https://aisuda.bce.baidu.com/amis/zh-CN/components/breadcrumb
 */
class Breadcrumb extends BaseRenderer
{
    public string $type = 'breadcrumb';

    /**
     * 设置组件样式
     *
     * @param mixed $value
     * @return $this
     */
    public function className(mixed $value): static
    {
        return $this->set('className', $value);
    }

    /**
     * 导航项类名
     *
     * @param string $value
     * @return self
     */
    public function itemClassName(string $value = ''): static
    {
        return $this->set('itemClassName', $value);
    }

    /**
     * 分割符类名
     *
     * @param string $value
     * @return self
     */
    public function separatorClassName(string $value = ''): static
    {
        return $this->set('separatorClassName', $value);
    }

    /**
     * 下拉菜单类名
     *
     * @param string $value
     * @return self
     */
    public function dropdownClassName(string $value = ''): static
    {
        return $this->set('dropdownClassName', $value);
    }

    /**
     * 下拉菜单项类名
     *
     * @param string $value
     * @return self
     */
    public function dropdownItemClassName(string $value = ''): static
    {
        return $this->set('dropdownItemClassName', $value);
    }

    /**
     * 分隔符
     *
     * @param string $value
     * @return self
     */
    public function separator(string $value = ''): static
    {
        return $this->set('separator', $value);
    }

    /**
     * 最大展示长度
     *
     * @param int|float $value
     * @return self
     */
    public function labelMaxLength(int|float $value = 16): static
    {
        return $this->set('labelMaxLength', $value);
    }

    /**
     * left \
     *
     * @param mixed $value
     * @return self
     */
    public function tooltipPosition(mixed $value = null): static
    {
        return $this->set('tooltipPosition', $value);
    }

    /**
     * 动态数据
     *
     * @param string $value
     * @return self
     */
    public function source(string $value = ''): static
    {
        return $this->set('source', $value);
    }
}
