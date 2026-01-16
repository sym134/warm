<?php
namespace warm\admin\renderer;
/**
 * Nav
 *
 * @see https://aisuda.bce.baidu.com/amis/zh-CN/components/nav
 */
class Nav extends BaseRenderer
{
    public string $type = 'nav';

    /**
     * 导航模式，悬浮/内联/悬浮面板，默认内联模式
     *
     * @param string $value
     * @return self
     */
    public function mode(string $value = 'inline'): self
    {
        return $this->set('mode', $value);
    }

    /**
     * 控制导航是否缩起
     *
     * @param bool $value
     * @return self
     */
    public function collapsed(bool $value = true): self
    {
        return $this->set('collapsed', $value);
    }

    /**
     * 层级缩进值，仅内联模式下生效
     *
     * @param int|float $value
     * @return self
     */
    public function indentSize(int|float $value = 16): self
    {
        return $this->set('indentSize', $value);
    }

    /**
     * 控制导航最大展示层级数
     *
     * @param int|float $value
     * @return self
     */
    public function level(int|float $value = 0): self
    {
        return $this->set('level', $value);
    }

    /**
     * 控制导航最大默认展开层级
     *
     * @param int|float $value
     * @return self
     */
    public function defaultOpenLevel(int|float $value = 0): self
    {
        return $this->set('defaultOpenLevel', $value);
    }

    /**
     * 外层 Dom 的类名
     *
     * @param string $value
     * @return self
     */
    public function className(string $value = ''): self
    {
        return $this->set('className', $value);
    }

    /**
     * 当为悬浮模式时，可自定义悬浮层样式
     *
     * @param string $value
     * @return self
     */
    public function popupClassName(string $value = ''): self
    {
        return $this->set('popupClassName', $value);
    }

    /**
     * 
     *
     * @param mixed $value
     * @return self
     */
    public function expandIcon(mixed $value = null): self
    {
        return $this->set('expandIcon', $value);
    }

    /**
     * 展开按钮位置，`"before"`或者`"after"`，不设置默认在前面
     *
     * @param string $value
     * @return self
     */
    public function expandPosition(string $value = ''): self
    {
        return $this->set('expandPosition', $value);
    }

    /**
     * 设置成 false 可以以 tabs 的形式展示
     *
     * @param bool $value
     * @return self
     */
    public function stacked(bool $value = true): self
    {
        return $this->set('stacked', $value);
    }

    /**
     * 是否开启手风琴模式
     *
     * @param bool $value
     * @return self
     */
    public function accordion(bool $value = true): self
    {
        return $this->set('accordion', $value);
    }

    /**
     * 可以通过变量或 API 接口动态创建导航
     *
     * @param mixed $value
     * @return self
     */
    public function source(mixed $value = null): self
    {
        return $this->set('source', $value);
    }

    /**
     * 用来延时加载选项详情的接口，可以不配置，不配置公用 source 接口。
     *
     * @param mixed $value
     * @return self
     */
    public function deferApi(mixed $value = null): self
    {
        return $this->set('deferApi', $value);
    }

    /**
     * 更多操作相关配置
     *
     * @param mixed $value
     * @return self
     */
    public function itemActions(mixed $value = null): self
    {
        return $this->set('itemActions', $value);
    }

    /**
     * 是否支持拖拽排序
     *
     * @param bool $value
     * @return self
     */
    public function draggable(bool $value = true): self
    {
        return $this->set('draggable', $value);
    }

    /**
     * 仅允许同层级内拖拽
     *
     * @param bool $value
     * @return self
     */
    public function dragOnSameLevel(bool $value = true): self
    {
        return $this->set('dragOnSameLevel', $value);
    }

    /**
     * 保存排序的 api
     *
     * @param mixed $value
     * @return self
     */
    public function saveOrderApi(mixed $value = null): self
    {
        return $this->set('saveOrderApi', $value);
    }

    /**
     * 角标
     *
     * @param mixed $value
     * @return self
     */
    public function itemBadge(mixed $value = null): self
    {
        return $this->set('itemBadge', $value);
    }

    /**
     * 链接集合
     *
     * @param array $value
     * @return self
     */
    public function links(array $value = []): self
    {
        return $this->set('links', $value);
    }

    /**
     * 响应式收纳配置
     *
     * @param mixed $value
     * @return self
     */
    public function overflow(mixed $value = null): self
    {
        return $this->set('overflow', $value);
    }

    /**
     * 是否开启搜索
     *
     * @param bool $value
     * @return self
     */
    public function searchable(bool $value = true): self
    {
        return $this->set('searchable', $value);
    }
}
