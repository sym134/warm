<?php
namespace warm\admin\renderer;
/**
 * Table
 *
 * @see https://aisuda.bce.baidu.com/amis/zh-CN/components/table
 */
class Table extends BaseRenderer
{
    public string $type = 'table';

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
     * 数据源, 绑定当前环境变量
     *
     * @param string $value
     * @return self
     */
    public function source(string $value = '${items}'): self
    {
        return $this->set('source', $value);
    }

    /**
     * 当行数据中有 defer 属性时，用此接口进一步加载内容
     *
     * @param mixed $value
     * @return self
     */
    public function deferApi(mixed $value = null): self
    {
        return $this->set('deferApi', $value);
    }

    /**
     * 是否固定表头
     *
     * @param bool $value
     * @return self
     */
    public function affixHeader(bool $value = true): self
    {
        return $this->set('affixHeader', $value);
    }

    /**
     * 是否固定表格底部工具栏
     *
     * @param bool $value
     * @return self
     */
    public function affixFooter(bool $value = true): self
    {
        return $this->set('affixFooter', $value);
    }

    /**
     * 展示列显示开关, 自动即：列数量大于或等于 5 个时自动开启
     *
     * @param mixed $value
     * @return self
     */
    public function columnsTogglable(mixed $value = null): self
    {
        return $this->set('columnsTogglable', $value);
    }

    /**
     * 当没数据的时候的文字提示
     *
     * @param mixed $value
     * @return self
     */
    public function placeholder(mixed $value = null): self
    {
        return $this->set('placeholder', $value);
    }

    /**
     * 外层 CSS 类名
     *
     * @param string $value
     * @return self
     */
    public function className(string $value = 'panel-default'): self
    {
        return $this->set('className', $value);
    }

    /**
     * 是否展示序号
     *
     * @param bool $value
     * @return self
     */
    public function showIndex(bool $value = true): self
    {
        return $this->set('showIndex', $value);
    }

    /**
     * 表格 CSS 类名
     *
     * @param string $value
     * @return self
     */
    public function tableClassName(string $value = 'table-db table-striped'): self
    {
        return $this->set('tableClassName', $value);
    }

    /**
     * 顶部外层 CSS 类名
     *
     * @param string $value
     * @return self
     */
    public function headerClassName(string $value = 'Action.md-table-header'): self
    {
        return $this->set('headerClassName', $value);
    }

    /**
     * 底部外层 CSS 类名
     *
     * @param string $value
     * @return self
     */
    public function footerClassName(string $value = 'Action.md-table-footer'): self
    {
        return $this->set('footerClassName', $value);
    }

    /**
     * 工具栏 CSS 类名
     *
     * @param string $value
     * @return self
     */
    public function toolbarClassName(string $value = 'Action.md-table-toolbar'): self
    {
        return $this->set('toolbarClassName', $value);
    }

    /**
     * 用来设置列信息
     *
     * @param array $value
     * @return self
     */
    public function columns(array $value = []): self
    {
        return $this->set('columns', $value);
    }

    /**
     * 自动合并单元格
     *
     * @param int|float $value
     * @return self
     */
    public function combineNum(int|float $value = 0): self
    {
        return $this->set('combineNum', $value);
    }

    /**
     * 悬浮行操作按钮组
     *
     * @param mixed $value
     * @return self
     */
    public function itemActions(mixed $value = null): self
    {
        return $this->set('itemActions', $value);
    }

    /**
     * 配置当前行是否可勾选的条件，要用 [表达式](../../docs/concepts/expression)
     *
     * @param mixed $value
     * @return self
     */
    public function itemCheckableOn(mixed $value = null): self
    {
        return $this->set('itemCheckableOn', $value);
    }

    /**
     * 配置当前行是否可拖拽的条件，要用 [表达式](../../docs/concepts/expression)
     *
     * @param mixed $value
     * @return self
     */
    public function itemDraggableOn(mixed $value = null): self
    {
        return $this->set('itemDraggableOn', $value);
    }

    /**
     * 点击数据行是否可以勾选当前行
     *
     * @param bool $value
     * @return self
     */
    public function checkOnItemClick(bool $value = true): self
    {
        return $this->set('checkOnItemClick', $value);
    }

    /**
     * 给行添加 CSS 类名
     *
     * @param string $value
     * @return self
     */
    public function rowClassName(string $value = ''): self
    {
        return $this->set('rowClassName', $value);
    }

    /**
     * 通过模板给行添加 CSS 类名
     *
     * @param mixed $value
     * @return self
     */
    public function rowClassNameExpr(mixed $value = null): self
    {
        return $this->set('rowClassNameExpr', $value);
    }

    /**
     * 顶部总结行
     *
     * @param array $value
     * @return self
     */
    public function prefixRow(array $value = []): self
    {
        return $this->set('prefixRow', $value);
    }

    /**
     * 底部总结行
     *
     * @param array $value
     * @return self
     */
    public function affixRow(array $value = []): self
    {
        return $this->set('affixRow', $value);
    }

    /**
     * 行角标配置
     *
     * @param mixed $value
     * @return self
     */
    public function itemBadge(mixed $value = null): self
    {
        return $this->set('itemBadge', $value);
    }

    /**
     * 内容区域自适应高度，可选择自适应、固定高度和最大高度
     *
     * @param mixed $value
     * @return self
     */
    public function autoFillHeight(mixed $value = null): self
    {
        return $this->set('autoFillHeight', $value);
    }

    /**
     * 列宽度是否支持调整
     *
     * @param bool $value
     * @return self
     */
    public function resizable(bool $value = true): self
    {
        return $this->set('resizable', $value);
    }

    /**
     * 支持勾选
     *
     * @param bool $value
     * @return self
     */
    public function selectable(bool $value = true): self
    {
        return $this->set('selectable', $value);
    }

    /**
     * 勾选 icon 是否为多选样式`checkbox`， 默认为`radio`
     *
     * @param bool $value
     * @return self
     */
    public function multiple(bool $value = true): self
    {
        return $this->set('multiple', $value);
    }

    /**
     * 用来控制从第几行开始懒渲染行，用来渲染大表格时有用
     *
     * @param int|float $value
     * @return self
     */
    public function lazyRenderAfter(int|float $value = 100): self
    {
        return $this->set('lazyRenderAfter', $value);
    }

    /**
     * `auto`
     *
     * @param mixed $value
     * @return self
     */
    public function tableLayout(mixed $value = null): self
    {
        return $this->set('tableLayout', $value);
    }

    /**
     * 
     *
     * @param mixed $value
     * @return self
     */
    public function reUseRow(mixed $value = null): self
    {
        return $this->set('reUseRow', $value);
    }

    /**
     * 用于配置列排序、列显示的本地缓存所使用的 key
     *
     * @param string $value
     * @return self
     */
    public function persistKey(string $value = ''): self
    {
        return $this->set('persistKey', $value);
    }
}
