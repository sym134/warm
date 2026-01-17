<?php
namespace warm\admin\renderer;
use warm\admin\renderer\trait\NameAndLabel;

/**
 * Status
 *
 * @see https://aisuda.bce.baidu.com/amis/zh-CN/components/status
 */
class Status extends BaseRenderer
{
    use NameAndLabel;

    public string $type = 'status';

    /**
     * 设置组件样式
     *
     * @param mixed $value
     * @return $this
     */
    public function className(mixed $value=''): static
    {
        return $this->set('className', $value);
    }

    /**
     * 
     *
     * @param string $value
     * @return self
     */
    public function placeholder(string $value = '-'): static
    {
        return $this->set('placeholder', $value);
    }

    /**
     * 2.3.0
     *
     * @param array $value
     * @return self
     */
    public function map(array $value = []): static
    {
        return $this->set('map', $value);
    }

    /**
     * 2.3.0
     *
     * @param array $value
     * @return self
     */
    public function labelMap(array $value = []): static
    {
        return $this->set('labelMap', $value);
    }

    /**
     * 2.8.0
     *
     * @param array $value
     * @return self
     */
    public function source(array $value = []): static
    {
        return $this->set('source', $value);
    }
}
