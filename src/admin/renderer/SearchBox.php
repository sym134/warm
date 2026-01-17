<?php
namespace warm\admin\renderer;
use warm\admin\renderer\trait\OnEvent;

/**
 * SearchBox
 *
 * @see https://aisuda.bce.baidu.com/amis/zh-CN/components/search-box
 */
class SearchBox extends BaseRenderer
{
    use OnEvent;

    public string $type = 'search-box';


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
     * 是否为 mini 模式
     *
     * @param bool $value
     * @return self
     */
    public function mini(bool $value = true): static
    {
        return $this->set('mini', $value);
    }

    /**
     * 是否立即搜索
     *
     * @param bool $value
     * @return self
     */
    public function searchImediately(bool $value = true): static
    {
        return $this->set('searchImediately', $value);
    }

    /**
     * 清空搜索框内容后立即执行搜索
     *
     * @param bool $value
     * @return self
     */
    public function clearAndSubmit(bool $value = true): static
    {
        return $this->set('clearAndSubmit', $value);
    }

    /**
     * 是否为禁用状态
     *
     * @param bool $value
     * @return self
     */
    public function disabled(bool $value = true): static
    {
        return $this->set('disabled', $value);
    }

    /**
     * 是否处于加载状态
     *
     * @param bool $value
     * @return self
     */
    public function loading(bool $value = true): static
    {
        return $this->set('loading', $value);
    }
}
 