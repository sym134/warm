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
     * css 类名
     *
     * @param string $value
     * @return self
     */
    public function className(string $value = ''): self
    {
        return $this->set('className', $value);
    }

    /**
     * "start", "flex-start", "center", "end", "flex-end", "space-around", "space-between", "space-evenly"
     *
     * @param string $value
     * @return self
     */
    public function justify(string $value = ''): self
    {
        return $this->set('justify', $value);
    }

    /**
     * "stretch", "start", "flex-start", "flex-end", "end", "center", "baseline"
     *
     * @param string $value
     * @return self
     */
    public function alignItems(string $value = ''): self
    {
        return $this->set('alignItems', $value);
    }

    /**
     * 自定义样式
     *
     * @param array $value
     * @return self
     */
    public function style(array $value = []): self
    {
        return $this->set('style', $value);
    }
}
