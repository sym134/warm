<?php
namespace warm\admin\renderer;
use warm\admin\renderer\trait\DataDomain;
use warm\admin\renderer\trait\OnEvent;

/**
 * List
 *
 * @see https://aisuda.bce.baidu.com/amis/zh-CN/components/list
 */
class ListClass extends BaseRenderer
{
    use OnEvent;
    use DataDomain;

    public string $type = 'list';
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
     * 数据源, 获取当前数据域变量，支持[数据映射](../../docs/concepts/data-mapping)
     *
     * @param string $value
     * @return self
     */
    public function source(string $value = '${items}'): static
    {
        return $this->set('source', $value);
    }

    /**
     * 当没数据的时候的文字提示
     *
     * @param string $value
     * @return self
     */
    public function placeholder(string $value = '‘暂无数据’'): static
    {
        return $this->set('placeholder', $value);
    }

    /**
     * 列表是否可选
     *
     * @param bool $value
     * @return self
     */
    public function selectable(bool $value = true): static
    {
        return $this->set('selectable', $value);
    }

    /**
     * 列表是否为多选
     *
     * @param bool $value
     * @return self
     */
    public function multiple(bool $value = true): static
    {
        return $this->set('multiple', $value);
    }

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
     * 顶部外层 CSS 类名
     *
     * @param string $value
     * @return self
     */
    public function headerClassName(string $value = 'amis-list-header'): static
    {
        return $this->set('headerClassName', $value);
    }

    /**
     * 底部外层 CSS 类名
     *
     * @param string $value
     * @return self
     */
    public function footerClassName(string $value = 'amis-list-footer'): static
    {
        return $this->set('footerClassName', $value);
    }

    /**
     * 配置单条信息
     *
     * @param array $value
     * @return self
     */
    public function listItem(array $value = []): static
    {
        return $this->set('listItem', $value);
    }

    /**
     * 是否显示右侧字母索引条
     *
     * @param bool $value
     * @return self
     */
    public function showIndexBar(bool $value = true): static
    {
        return $this->set('showIndexBar', $value);
    }

    /**
     * 索引依据字段，默认使用 `title` 字段或列表项标题
     *
     * @param string $value
     * @return self
     */
    public function indexField(string $value = 'title'): static
    {
        return $this->set('indexField', $value);
    }

    /**
     * 索引条偏移量，用于设置点击索引条跳转时的滚动位置偏移
     *
     * @param int|float $value
     * @return self
     */
    public function indexBarOffset(int|float $value = 0): static
    {
        return $this->set('indexBarOffset', $value);
    }
}
