<?php
namespace warm\admin\renderer;

use warm\admin\renderer\trait\DataDomain;
use warm\admin\renderer\trait\OnEvent;

/**
 * Cards
 *
 * @see https://aisuda.bce.baidu.com/amis/zh-CN/components/cards
 */
class Cards extends BaseRenderer
{
    use OnEvent;
    use DataDomain;

    public string $type = 'cards';

    /**
     * 标题
     *
     * @param mixed $value
     * @return self
     */
    public function title(mixed $value = null): static
    {
        return $this->set('title', $value);
    }

    /**
     * 数据源, 获取当前数据域中的变量
     *
     * @param mixed $value
     * @return self
     */
    public function source(mixed $value = null): static
    {
        return $this->set('source', $value);
    }

    /**
     * 当没数据的时候的文字提示
     *
     * @param mixed $value
     * @return self
     */
    public function placeholder(mixed $value = null): static
    {
        return $this->set('placeholder', $value);
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
    public function headerClassName(string $value = 'amis-grid-header'): static
    {
        return $this->set('headerClassName', $value);
    }

    /**
     * 底部外层 CSS 类名
     *
     * @param string $value
     * @return self
     */
    public function footerClassName(string $value = 'amis-grid-footer'): static
    {
        return $this->set('footerClassName', $value);
    }

    /**
     * 卡片 CSS 类名
     *
     * @param string $value
     * @return self
     */
    public function itemClassName(string $value = 'col-sm-4 col-md-3'): static
    {
        return $this->set('itemClassName', $value);
    }

    /**
     * 配置卡片信息
     *
     * @param mixed $value
     * @return self
     */
    public function card(mixed $value = null): static
    {
        return $this->set('card', $value);
    }

    /**
     * 卡片组是否可选
     *
     * @param bool $value
     * @return self
     */
    public function selectable(bool $value = true): static
    {
        return $this->set('selectable', $value);
    }

    /**
     * 卡片组是否为多选
     *
     * @param bool $value
     * @return self
     */
    public function multiple(bool $value = true): static
    {
        return $this->set('multiple', $value);
    }

    /**
     * 点选卡片内容是否选中卡片
     *
     * @param bool $value
     * @return self
     */
    public function checkOnItemClick(bool $value = true): static
    {
        return $this->set('checkOnItemClick', $value);
    }
}
