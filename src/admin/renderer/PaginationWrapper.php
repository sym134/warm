<?php
namespace warm\admin\renderer;
use warm\admin\renderer\trait\DataDomain;

/**
 * PaginationWrapper 分页容器
 *
 * @see https://aisuda.bce.baidu.com/amis/zh-CN/components/pagination-wrapper
 */
class PaginationWrapper extends BaseRenderer
{
    use DataDomain;

    public string $type = 'pagination-wrapper';

    /**
     * 是否显示快速跳转输入框
     *
     * @param bool $value
     * @return self
     */
    public function showPageInput(bool $value = true): static
    {
        return $this->set('showPageInput', $value);
    }

    /**
     * 最多显示多少个分页按钮
     *
     * @param int|float $value
     * @return self
     */
    public function maxButtons(int|float $value = 5): static
    {
        return $this->set('maxButtons', $value);
    }

    /**
     * 输入字段名
     *
     * @param string $value
     * @return self
     */
    public function inputName(string $value = 'items'): static
    {
        return $this->set('inputName', $value);
    }

    /**
     * 输出字段名
     *
     * @param string $value
     * @return self
     */
    public function outputName(string $value = 'items'): static
    {
        return $this->set('outputName', $value);
    }

    /**
     * 每页显示多条数据
     *
     * @param int|float $value
     * @return self
     */
    public function perPage(int|float $value = 10): static
    {
        return $this->set('perPage', $value);
    }

    /**
     * 分页显示位置，如果配置为 none 则需要自己在内容区域配置 pagination 组件，否则不显示
     *
     * @param mixed $value
     * @return self
     */
    public function position(mixed $value = null): static
    {
        return $this->set('position', $value);
    }

    /**
     * 内容区域
     *
     * @param mixed $value
     * @return self
     */
    public function body(mixed $value = null): static
    {
        return $this->set('body', $value);
    }
}
