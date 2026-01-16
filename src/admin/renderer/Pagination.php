<?php
namespace warm\admin\renderer;
/**
 * Pagination
 *
 * @see https://aisuda.bce.baidu.com/amis/zh-CN/components/pagination
 */
class Pagination extends BaseRenderer
{
    public string $type = 'pagination';

    /**
     * `normal`
     *
     * @param mixed $value
     * @return self
     */
    public function mode(mixed $value = null): self
    {
        return $this->set('mode', $value);
    }

    /**
     * `["pager"]`
     *
     * @param mixed $value
     * @return self
     */
    public function layout(mixed $value = null): self
    {
        return $this->set('layout', $value);
    }

    /**
     * `5`
     *
     * @param mixed $value
     * @return self
     */
    public function maxButtons(mixed $value = null): self
    {
        return $this->set('maxButtons', $value);
    }

    /**
     * 
     *
     * @param mixed $value
     * @return self
     */
    public function total(mixed $value = null): self
    {
        return $this->set('total', $value);
    }

    /**
     * `1`
     *
     * @param mixed $value
     * @return self
     */
    public function activePage(mixed $value = null): self
    {
        return $this->set('activePage', $value);
    }

    /**
     * `10`
     *
     * @param mixed $value
     * @return self
     */
    public function perPage(mixed $value = null): self
    {
        return $this->set('perPage', $value);
    }

    /**
     * 是否展示 perPage 切换器 layout 和 showPerPage 都可以控制
     *
     * @param bool $value
     * @return self
     */
    public function showPerPage(bool $value = true): self
    {
        return $this->set('showPerPage', $value);
    }

    /**
     * `md`
     *
     * @param mixed $value
     * @return self
     */
    public function size(mixed $value = null): self
    {
        return $this->set('size', $value);
    }

    /**
     * 5
     *
     * @param mixed $value
     * @return self
     */
    public function ellipsisPageGap(mixed $value = null): self
    {
        return $this->set('ellipsisPageGap', $value);
    }

    /**
     * 指定每页可以显示多少条
     *
     * @param array $value
     * @return self
     */
    public function perPageAvailable(array $value = []): self
    {
        return $this->set('perPageAvailable', $value);
    }

    /**
     * 是否显示快速跳转输入框 layout 和 showPageInput 都可以控制
     *
     * @param bool $value
     * @return self
     */
    public function showPageInput(bool $value = true): self
    {
        return $this->set('showPageInput', $value);
    }

    /**
     * 是否禁用
     *
     * @param bool $value
     * @return self
     */
    public function disabled(bool $value = true): self
    {
        return $this->set('disabled', $value);
    }

    /**
     * 分页改变触发
     *
     * @param mixed $value
     * @return self
     */
    public function onPageChange(mixed $value = null): self
    {
        return $this->set('onPageChange', $value);
 