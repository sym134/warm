<?php
namespace warm\admin\renderer;
/**
 * Tabs
 *
 * @see https://aisuda.bce.baidu.com/amis/zh-CN/components/tabs
 */
class Tabs extends BaseRenderer
{
    public string $type = 'tabs';

    /**
     * 组件初始化时激活的选项卡，hash 值或索引值，支持使用表达式 `2.7.1 以上版本`
     *
     * @param mixed $value
     * @return self
     */
    public function defaultKey(mixed $value = null): self
    {
        return $this->set('defaultKey', $value);
    }

    /**
     * 激活的选项卡，hash 值或索引值，支持使用表达式，可响应上下文数据变化
     *
     * @param mixed $value
     * @return self
     */
    public function activeKey(mixed $value = null): self
    {
        return $this->set('activeKey', $value);
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
     * Tabs 标题区的类名
     *
     * @param string $value
     * @return self
     */
    public function linksClassName(string $value = ''): self
    {
        return $this->set('linksClassName', $value);
    }

    /**
     * Tabs 内容区的类名
     *
     * @param string $value
     * @return self
     */
    public function contentClassName(string $value = ''): self
    {
        return $this->set('contentClassName', $value);
    }

    /**
     * 展示模式，取值可以是 `line`、`card`、`radio`、`vertical`、`chrome`、`simple`、`strong`、`tiled`、`sidebar`
     *
     * @param string $value
     * @return self
     */
    public function tabsMode(string $value = ''): self
    {
        return $this->set('tabsMode', $value);
    }

    /**
     * tabs 内容
     *
     * @param array $value
     * @return self
     */
    public function tabs(array $value = []): self
    {
        return $this->set('tabs', $value);
    }

    /**
     * tabs 关联数据，关联后可以重复生成选项卡
     *
     * @param string $value
     * @return self
     */
    public function source(string $value = ''): self
    {
        return $this->set('source', $value);
    }

    /**
     * tabs 中的工具栏
     *
     * @param mixed $value
     * @return self
     */
    public function toolbar(mixed $value = null): self
    {
        return $this->set('toolbar', $value);
    }

    /**
     * tabs 中工具栏的类名
     *
     * @param string $value
     * @return self
     */
    public function toolbarClassName(string $value = ''): self
    {
        return $this->set('toolbarClassName', $value);
    }

    /**
     * 只有在点中 tab 的时候才渲染
     *
     * @param bool $value
     * @return self
     */
    public function mountOnEnter(bool $value = true): self
    {
        return $this->set('mountOnEnter', $value);
    }

    /**
     * 切换 tab 的时候销毁
     *
     * @param bool $value
     * @return self
     */
    public function unmountOnExit(bool $value = true): self
    {
        return $this->set('unmountOnExit', $value);
    }

    /**
     * 是否支持新增
     *
     * @param bool $value
     * @return self
     */
    public function addable(bool $value = true): self
    {
        return $this->set('addable', $value);
    }

    /**
     * 新增按钮文案
     *
     * @param string $value
     * @return self
     */
    public function addBtnText(string $value = '增加'): self
    {
        return $this->set('addBtnText', $value);
    }

    /**
     * 是否支持删除
     *
     * @param bool $value
     * @return self
     */
    public function closable(bool $value = true): self
    {
        return $this->set('closable', $value);
    }

    /**
     * 是否支持拖拽
     *
     * @param bool $value
     * @return self
     */
    public function draggable(bool $value = true): self
    {
        return $this->set('draggable', $value);
    }

    /**
     * 是否支持提示
     *
     * @param bool $value
     * @return self
     */
    public function showTip(bool $value = true): self
    {
        return $this->set('showTip', $value);
    }

    /**
     * 提示的类
     *
     * @param string $value
     * @return self
     */
    public function showTipClassName(string $value = ' '): self
    {
        return $this->set('showTipClassName', $value);
    }

    /**
     * 是否可编辑标签名。当 `tabs[x].title` 为 [SchemaNode](../types/schemanode) 时，双击编辑 Tab 的 title 显示空的内容
     *
     * @param bool $value
     * @return self
     */
    public function editable(bool $value = true): self
    {
        return $this->set('editable', $value);
    }

    /**
     * 是否导航支持内容溢出滚动。（属性废弃）
     *
     * @param bool $value
     * @return self
     */
    public function scrollable(bool $value = true): self
    {
        return $this->set('scrollable', $value);
    }

    /**
     * `sidebar` 模式下，标签栏位置
     *
     * @param mixed $value
     * @return self
     */
    public function sidePosition(mixed $value = null): self
    {
        return $this->set('sidePosition', $value);
    }

    /**
     * 当 tabs 超出多少个时开始折叠
     *
     * @param int|float $value
     * @return self
     */
    public function collapseOnExceed(int|float $value = 0): self
    {
        return $this->set('collapseOnExceed', $value);
    }

    /**
     * 用来设置折叠按钮的文字
     *
     * @param string $value
     * @return self
     */
    public function collapseBtnLabel(string $value = 'more'): self
    {
        return $this->set('collapseBtnLabel', $value);
    }

    /**
     * 是否开启手势滑动切换（移动端生效）
     *
     * @param bool $value
     * @return self
     */
    public function swipeable(bool $value = true): self
    {
        return $this->set('swipeable', $value);
    }
}
