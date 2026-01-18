<?php
namespace warm\admin\renderer;
use warm\admin\renderer\trait\DataDomain;
use warm\admin\renderer\trait\OnEvent;

/**
 * Table2 二维表格
 *
 * @see https://aisuda.bce.baidu.com/amis/zh-CN/components/table2
 */
class Table2 extends BaseRenderer
{
    use OnEvent;
    use DataDomain;

    public string $type = 'table2';
    /**
     * 标题
     *
     * @param string $value
     * @return self
     */
    public function title(string $value = ''): static
    {
        return $this->set('title', $value);
    }

    /**
     * 数据源, 绑定当前环境变量
     *
     * @param string $value
     * @return self
     */
    public function source(string $value = '${items}'): static
    {
        return $this->set('source', $value);
    }

    /**
     * 是否粘性头部
     *
     * @param bool $value
     * @return self
     */
    public function sticky(bool $value = true): static
    {
        return $this->set('sticky', $value);
    }

    /**
     * 
     *
     * @param mixed $value
     * @return self
     */
    public function footer(mixed $value = null): static
    {
        return $this->set('footer', $value);
    }

    /**
     * 表格是否加载中
     *
     * @param bool $value
     * @return self
     */
    public function loading(bool $value = true): static
    {
        return $this->set('loading', $value);
    }

    /**
     * 展示列显示开关, 自动即：列数量大于或等于 5 个时自动开启
     *
     * @param mixed $value
     * @return self
     */
    public function columnsTogglable(mixed $value = null): static
    {
        return $this->set('columnsTogglable', $value);
    }

    /**
     * `暂无数据`
     *
     * @param mixed $value
     * @return self
     */
    public function placeholder(mixed $value = null): static
    {
        return $this->set('placeholder', $value);
    }

    /**
     * 行相关配置
     *
     * @param mixed $value
     * @return self
     */
    public function rowSelection(mixed $value = null): static
    {
        return $this->set('rowSelection', $value);
    }

    /**
     * 行 CSS 类名，支持模版语法
     *
     * @param string $value
     * @return self
     */
    public function rowClassNameExpr(string $value = ''): static
    {
        return $this->set('rowClassNameExpr', $value);
    }

    /**
     * 展开行配置
     *
     * @param mixed $value
     * @return self
     */
    public function expandable(mixed $value = null): static
    {
        return $this->set('expandable', $value);
    }

    /**
     * 
     *
     * @param mixed $value
     * @return self
     */
    public function lineHeight(mixed $value = null): static
    {
        return $this->set('lineHeight', $value);
    }

    /**
     * 底部外层 CSS 类名
     *
     * @param string $value
     * @return self
     */
    public function footerClassName(string $value = 'Action.md-table-footer'): static
    {
        return $this->set('footerClassName', $value);
    }

    /**
     * 工具栏 CSS 类名
     *
     * @param string $value
     * @return self
     */
    public function toolbarClassName(string $value = 'Action.md-table-toolbar'): static
    {
        return $this->set('toolbarClassName', $value);
    }

    /**
     * 用来设置列信息
     *
     * @param array $value
     * @return self
     */
    public function columns(array $value = []): static
    {
        return $this->set('columns', $value);
    }

    /**
     * 悬浮行操作按钮组
     *
     * @param mixed $value
     * @return self
     */
    public function itemActions(mixed $value = null): static
    {
        return $this->set('itemActions', $value);
    }

    /**
     * 配置当前行是否可勾选的条件，要用 [表达式](../../docs/concepts/expression)
     *
     * @param mixed $value
     * @return self
     */
    public function itemCheckableOn(mixed $value = null): static
    {
        return $this->set('itemCheckableOn', $value);
    }

    /**
     * 配置当前行是否可拖拽的条件，要用 [表达式](../../docs/concepts/expression)
     *
     * @param mixed $value
     * @return self
     */
    public function itemDraggableOn(mixed $value = null): static
    {
        return $this->set('itemDraggableOn', $value);
    }

    /**
     * 点击数据行是否可以勾选当前行
     *
     * @param bool $value
     * @return self
     */
    public function checkOnItemClick(bool $value = true): static
    {
        return $this->set('checkOnItemClick', $value);
    }

    /**
     * 给行添加 CSS 类名
     *
     * @param string $value
     * @return self
     */
    public function rowClassName(string $value = ''): static
    {
        return $this->set('rowClassName', $value);
    }

    /**
     * 顶部总结行
     *
     * @param array $value
     * @return self
     */
    public function prefixRow(array $value = []): static
    {
        return $this->set('prefixRow', $value);
    }

    /**
     * 底部总结行
     *
     * @param array $value
     * @return self
     */
    public function affixRow(array $value = []): static
    {
        return $this->set('affixRow', $value);
    }

    /**
     * 行角标配置
     *
     * @param mixed $value
     * @return self
     */
    public function itemBadge(mixed $value = null): static
    {
        return $this->set('itemBadge', $value);
    }

    /**
     * 内容区域自适应高度，可选择自适应、固定高度和最大高度
     *
     * @param mixed $value
     * @return self
     */
    public function autoFillHeight(mixed $value = null): static
    {
        return $this->set('autoFillHeight', $value);
    }

    /**
     * 默认数据超过 100 条启动懒加载提升渲染性能，也可通过自定义该属性调整数值
     *
     * @param int|float $value
     * @return self
     */
    public function lazyRenderAfter(int|float $value = 100): static
    {
        return $this->set('lazyRenderAfter', $value);
    }
}
