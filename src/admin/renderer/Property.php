<?php
namespace warm\admin\renderer;
/**
 * Property
 *
 * @see https://aisuda.bce.baidu.com/amis/zh-CN/components/property
 */
class Property extends BaseRenderer
{
    public string $type = 'property';

    /**
     * 外层 dom 的类名
     *
     * @param string $value
     * @return self
     */
    public function className(string $value = ''): self
    {
        return $this->set('className', $value);
    }

    /**
     * 外层 dom 的样式
     *
     * @param array $value
     * @return self
     */
    public function style(array $value = []): self
    {
        return $this->set('style', $value);
    }

    /**
     * 属性值的样式
     *
     * @param array $value
     * @return self
     */
    public function contentStyle(array $value = []): self
    {
        return $this->set('contentStyle', $value);
    }

    /**
     * 每行几列
     *
     * @param int|float $value
     * @return self
     */
    public function column(int|float $value = 3): self
    {
        return $this->set('column', $value);
    }

    /**
     * 显示模式，目前只有 'table' 和 'simple'
     *
     * @param string $value
     * @return self
     */
    public function mode(string $value = 'table'): self
    {
        return $this->set('mode', $value);
    }

    /**
     * 标题
     *
     * @param string $value
     * @return self
     */
    public function title(string $value = ''): self
    {
        return $this->set('title', $value);
    }

    /**
     * 数据源
     *
     * @param string $value
     * @return self
     */
    public function source(string $value = ''): self
    {
        return $this->set('source', $value);
    }
}
