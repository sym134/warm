<?php
namespace warm\admin\renderer;

use warm\admin\renderer\trait\OnEvent;

/**
 * Tab 选项卡组件
 *
 * 选项卡容器组件，支持多标签页切换、动态添加/删除、懒加载、禁用状态和响应式布局等功能。
 *
 * @see https://aisuda.bce.baidu.com/amis/zh-CN/components/tabs
 */
class Tabs extends BaseRenderer
{
    use OnEvent;

    public string $type = 'tabs';

    /**
     * 组件初始化时激活的选项卡，hash 值或索引值，支持使用表达式
     * 
     * 2.7.1 以上版本支持
     *
     * @param mixed $value hash值或索引值
     * @return self
     */
    public function defaultKey(mixed $value = null): static
    {
        return $this->set('defaultKey', $value);
    }

    /**
     * 激活的选项卡，hash 值或索引值，支持使用表达式，可响应上下文数据变化
     *
     * @param mixed $value hash值或索引值
     * @return self
     */
    public function activeKey(mixed $value = null): static
    {
        return $this->set('activeKey', $value);
    }

    /**
     * 外层 Dom 的类名
     *
     * @param mixed $value 类名
     * @return self
     */
    public function className(mixed $value = ''): static
    {
        return $this->set('className', $value);
    }

    /**
     * Tabs 标题区的类名
     *
     * @param string $value 类名
     * @return self
     */
    public function linksClassName(string $value = ''): static
    {
        return $this->set('linksClassName', $value);
    }

    /**
     * Tabs 内容区的类名
     *
     * @param string $value 类名
     * @return self
     */
    public function contentClassName(string $value = ''): static
    {
        return $this->set('contentClassName', $value);
    }

    /**
     * 展示模式
     * 
     * 取值可以是 `line`、`card`、`radio`、`vertical`、`chrome`、`simple`、`strong`、`tiled`、`sidebar`
     *
     * @param string $value 展示模式
     * @return self
     */
    public function tabsMode(string $value = ''): static
    {
        return $this->set('tabsMode', $value);
    }

    /**
     * tabs 内容数组
     * 
     * 每个 tab 对象包含：title、body/tab、icon、hash、disabled、closable 等属性
     *
     * @param array $value tabs 配置数组
     * @return self
     */
    public function tabs(array $value = []): static
    {
        return $this->set('tabs', $value);
    }

    /**
     * tabs 关联数据，关联后可以重复生成选项卡
     * 
     * 使用数据映射表达式，如：`${arr}`
     *
     * @param string $value 数据源表达式
     * @return self
     */
    public function source(string $value = ''): static
    {
        return $this->set('source', $value);
    }

    /**
     * tabs 中的工具栏
     * 
     * 配置工具栏按钮等组件
     *
     * @param mixed $value 工具栏配置（SchemaNode 或数组）
     * @return self
     */
    public function toolbar(mixed $value = null): static
    {
        return $this->set('toolbar', $value);
    }

    /**
     * tabs 中工具栏的类名
     *
     * @param string $value 类名
     * @return self
     */
    public function toolbarClassName(string $value = ''): static
    {
        return $this->set('toolbarClassName', $value);
    }

    /**
     * 只有在点中 tab 的时候才渲染（懒加载）
     * 
     * 在内容较多的时候可以提升性能，但第一次点击的时候会有卡顿
     *
     * @param bool $value 是否启用懒加载
     * @return self
     */
    public function mountOnEnter(bool $value = true): static
    {
        return $this->set('mountOnEnter', $value);
    }

    /**
     * 切换 tab 的时候销毁隐藏的 tab
     * 
     * 如果想在切换 tab 时，自动销毁掉隐藏的 tab，请配置为 true
     *
     * @param bool $value 是否在退出时销毁
     * @return self
     */
    public function unmountOnExit(bool $value = true): static
    {
        return $this->set('unmountOnExit', $value);
    }

    /**
     * 是否支持新增标签页
     *
     * @param bool $value 是否支持新增
     * @return self
     */
    public function addable(bool $value = true): static
    {
        return $this->set('addable', $value);
    }

    /**
     * 新增按钮文案
     *
     * @param string $value 按钮文字
     * @return self
     */
    public function addBtnText(string $value = '增加'): static
    {
        return $this->set('addBtnText', $value);
    }

    /**
     * 是否支持删除标签页
     * 
     * 单个 tab 设置的 `closable` 优先级高于整体
     *
     * @param bool $value 是否支持删除
     * @return self
     */
    public function closable(bool $value = true): static
    {
        return $this->set('closable', $value);
    }

    /**
     * 是否支持拖拽排序标签页
     *
     * @param bool $value 是否支持拖拽
     * @return self
     */
    public function draggable(bool $value = true): static
    {
        return $this->set('draggable', $value);
    }

    /**
     * 是否支持提示
     * 
     * 当开启时，hover tab 标题会显示提示信息（tip 属性或 title）
     *
     * @param bool $value 是否支持提示
     * @return self
     */
    public function showTip(bool $value = true): static
    {
        return $this->set('showTip', $value);
    }

    /**
     * 提示的类名
     *
     * @param string $value 类名
     * @return self
     */
    public function showTipClassName(string $value = ' '): static
    {
        return $this->set('showTipClassName', $value);
    }

    /**
     * 是否可编辑标签名
     * 
     * 双击标签名，可开启编辑。当 `tabs[x].title` 为 SchemaNode 时，双击编辑 Tab 的 title 显示空的内容
     *
     * @param bool $value 是否可编辑
     * @return self
     */
    public function editable(bool $value = true): static
    {
        return $this->set('editable', $value);
    }

    /**
     * 是否导航支持内容溢出滚动
     * 
     * 注意：属性已废弃
     *
     * @param bool $value 是否支持滚动
     * @return self
     */
    public function scrollable(bool $value = true): static
    {
        return $this->set('scrollable', $value);
    }

    /**
     * sidebar 模式下，标签栏位置
     * 
     * 可选值：`left`、`right`
     *
     * @param mixed $value 位置（'left' 或 'right'）
     * @return self
     */
    public function sidePosition(mixed $value = null): static
    {
        return $this->set('sidePosition', $value);
    }

    /**
     * 当 tabs 超出多少个时开始折叠
     * 
     * 通过配置该属性可以实现超出折叠，额外还能通过 `collapseBtnLabel` 配置折叠按钮的文字
     *
     * @param int|float $value 折叠阈值
     * @return self
     */
    public function collapseOnExceed(int|float $value = 0): static
    {
        return $this->set('collapseOnExceed', $value);
    }

    /**
     * 用来设置折叠按钮的文字
     *
     * @param string $value 按钮文字
     * @return self
     */
    public function collapseBtnLabel(string $value = 'more'): static
    {
        return $this->set('collapseBtnLabel', $value);
    }

    /**
     * 是否开启手势滑动切换（移动端生效）
     * 
     * 响应式布局适配：移动端可以通过手势滑动来切换标签页
     *
     * @param bool $value 是否开启滑动切换
     * @return self
     */
    public function swipeable(bool $value = true): static
    {
        return $this->set('swipeable', $value);
    }

    /**
     * 添加单个标签页
     * 
     * 便捷方法：动态添加标签页到 tabs 数组
     *
     * @param array $tab 标签页配置（包含 title、body/tab、icon、hash、disabled、closable 等）
     * @return self
     * @throws \InvalidArgumentException 当标签页配置无效时抛出异常
     */
    public function addTab(array $tab): static
    {
        if (empty($tab['title'])) {
            throw new \InvalidArgumentException('Tab title is required');
        }

        $tabs = $this->get('tabs', []);
        $tabs[] = $tab;
        
        return $this->set('tabs', $tabs);
    }

    /**
     * 移除指定索引的标签页
     * 
     * 便捷方法：动态删除标签页
     *
     * @param int $index 要删除的标签页索引
     * @return self
     * @throws \OutOfBoundsException 当索引超出范围时抛出异常
     */
    public function removeTab(int $index): static
    {
        $tabs = $this->get('tabs', []);
        
        if (!isset($tabs[$index])) {
            throw new \OutOfBoundsException("Tab index {$index} does not exist");
        }

        unset($tabs[$index]);
        
        return $this->set('tabs', array_values($tabs));
    }

    /**
     * 设置标签页禁用状态
     * 
     * 便捷方法：设置指定索引标签页的禁用状态
     *
     * @param int $index 标签页索引
     * @param bool $disabled 是否禁用
     * @return self
     * @throws \OutOfBoundsException 当索引超出范围时抛出异常
     */
    public function setTabDisabled(int $index, bool $disabled = true): static
    {
        $tabs = $this->get('tabs', []);
        
        if (!isset($tabs[$index])) {
            throw new \OutOfBoundsException("Tab index {$index} does not exist");
        }

        $tabs[$index]['disabled'] = $disabled;
        
        return $this->set('tabs', $tabs);
    }

    /**
     * 设置标签页是否可关闭
     * 
     * 便捷方法：设置指定索引标签页的可关闭状态
     *
     * @param int $index 标签页索引
     * @param bool $closable 是否可关闭
     * @return self
     * @throws \OutOfBoundsException 当索引超出范围时抛出异常
     */
    public function setTabClosable(int $index, bool $closable = true): static
    {
        $tabs = $this->get('tabs', []);
        
        if (!isset($tabs[$index])) {
            throw new \OutOfBoundsException("Tab index {$index} does not exist");
        }

        $tabs[$index]['closable'] = $closable;
        
        return $this->set('tabs', $tabs);
    }

    /**
     * 获取所有标签页配置
     *
     * @return array 标签页配置数组
     */
    public function getTabs(): array
    {
        return $this->get('tabs', []);
    }

    /**
     * 获取标签页数量
     *
     * @return int 标签页数量
     */
    public function getTabCount(): int
    {
        return count($this->getTabs());
    }
}
