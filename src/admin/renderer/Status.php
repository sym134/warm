<?php
namespace warm\admin\renderer;
/**
 * Status
 *
 * @see https://aisuda.bce.baidu.com/amis/zh-CN/components/status
 */
class Status extends BaseRenderer
{
    public string $type = 'status';

    /**
     * 
     *
     * @param string $value
     * @return self
     */
    public function className(string $value = ''): self
    {
        return $this->set('className', $value);
    }

    /**
     * 
     *
     * @param string $value
     * @return self
     */
    public function placeholder(string $value = '-'): self
    {
        return $this->set('placeholder', $value);
    }

    /**
     * 2.3.0
     *
     * @param array $value
     * @return self
     */
    public function map(array $value = []): self
    {
        return $this->set('map', $value);
    }

    /**
     * 2.3.0
     *
     * @param array $value
     * @return self
     */
    public function labelMap(array $value = []): self
    {
        return $this->set('labelMap', $value);
    }

    /**
     * 2.8.0
     *
     * @param array $value
     * @return self
     */
    public function source(array $value = []): self
    {
        return $this->set('source', $value);
    }
}
