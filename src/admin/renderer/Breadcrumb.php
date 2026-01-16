<?php
namespace warm\admin\renderer;
/**
 * Breadcrumb
 *
 * @see https://aisuda.bce.baidu.com/amis/zh-CN/components/breadcrumb
 */
class Breadcrumb extends BaseRenderer
{
    public string $type = 'breadcrumb';

    /**
     * 外层类名
     *
     * @param string $value
     * @return self
     */
    public function className(string $value = ''): self
    {
        return $this->set('className', $value);
    }

    /**
     * 导航项类名
     *
     * @param string $value
     * @return self
     */
    public function itemClassName(string $value = ''): self
    {
        return $this->set('itemClassName', $value);
    }

    /**
     * 分割符类名
     *
     * @param string $value
     * @return self
     */
    public function separatorClassName(string $value = ''): self
    {
        return $this->set('separatorClassName', $value);
    }

    /**
     * 下拉菜单类名
     *
     * @param string $value
     * @return self
     */
    public function dropdownClassName(string $value = ''): self
    {
        return $this->set('dropdownClassName', $value);
    }

    /**
     * 下拉菜单项类名
     *
     * @param string $value
     * @return self
     */
    public function dropdownItemClassName(string $value = ''): self
    {
        return $this->set('dropdownItemClassName', $value);
    }

    /**
     * 分隔符
     *
     * @param string $value
     * @return self
     */
    public function separator(string $value = ''): self
    {
        return $this->set('separator', $value);
    }

    /**
     * 最大展示长度
     *
     * @param int|float $value
     * @return self
     */
    public function labelMaxLength(int|float $value = 16): self
    {
        return $this->set('labelMaxLength', $value);
    }

    /**
     * left \
     *
     * @param mixed $value
     * @return self
     */
    public function tooltipPosition(mixed $value = null): self
    {
        return $this->set('tooltipPosition', $value);
    }

    /**
     * 动态数据
     *
     * @param string $value
     * @return self
     */
    public function source(string $value = ''): self
    {
        return $this->set('source', $value);
    }
}
