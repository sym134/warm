<?php
namespace warm\admin\renderer;
/**
 * Flex
 *
 * @see https://aisuda.bce.baidu.com/amis/zh-CN/components/flex
 */
class Flex extends BaseRenderer
{

    public string $type = 'flex';

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
     * "start", "flex-start", "center", "end", "flex-end", "space-around", "space-between", "space-evenly"
     *
     * @param string $value
     * @return static
     */
    public function justify(string $value = ''): static
    {
        return $this->set('justify', $value);
    }

    /**
     * "stretch", "start", "flex-start", "flex-end", "end", "center", "baseline"
     *
     * @param string $value
     * @return static
     */
    public function alignItems(string $value = ''): static
    {
        return $this->set('alignItems', $value);
    }

    /**
     * 自定义样式
     *
     * @param array $value
     * @return static
     */
    public function style(array $value = []): static
    {
        return $this->set('style', $value);
    }

    /**
     * 列
     *
     * @param mixed $value
     * @return static
     */
    public function items(mixed $value):static
    {
        return $this->set('items', $value);
    }

    /**
     * "row", "column"
     *
     * @param string $string
     * @return static
     */
    public function direction(string $string): static
    {
        return $this->set('direction', $string);
    }
}
