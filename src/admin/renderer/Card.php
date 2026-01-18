<?php
namespace warm\admin\renderer;
/**
 * Card 卡片
 *
 * @see https://aisuda.bce.baidu.com/amis/zh-CN/components/card
 */
class Card extends BaseRenderer
{
    public string $type = 'card';

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
     * 外部链接
     *
     * @param mixed $value
     * @return self
     */
    public function href(mixed $value = null): static
    {
        return $this->set('href', $value);
    }

    /**
     * Card 头部内容设置
     *
     * @param array $value
     * @return self
     */
    public function header(array $value = []): static
    {
        return $this->set('header', $value);
    }

    /**
     * 内容容器，主要用来放置非表单项组件
     *
     * @param mixed $value
     * @return self
     */
    public function body(mixed $value = []): static
    {
        return $this->set('body', $value);
    }

    /**
     * 内容区域类名
     *
     * @param string $value
     * @return self
     */
    public function bodyClassName(string $value = ''): static
    {
        return $this->set('bodyClassName', $value);
    }

    /**
     * 配置按钮集合
     *
     * @param mixed $value
     * @return self
     */
    public function actions(mixed $value = null): static
    {
        return $this->set('actions', $value);
    }

    /**
     * 按钮集合每行个数
     *
     * @param int|float $value
     * @return self
     */
    public function actionsCount(int|float $value = 4): static
    {
        return $this->set('actionsCount', $value);
    }

    /**
     * 点击卡片的行为
     *
     * @param mixed $value
     * @return self
     */
    public function itemAction(mixed $value = null): static
    {
        return $this->set('itemAction', $value);
    }

    /**
     * Card 多媒体部内容设置
     *
     * @param array $value
     * @return self
     */
    public function media(array $value = []): static
    {
        return $this->set('media', $value);
    }

    /**
     * 次要说明
     *
     * @param mixed $value
     * @return self
     */
    public function secondary(mixed $value = null): static
    {
        return $this->set('secondary', $value);
    }

    /**
     * 工具栏按钮
     *
     * @param mixed $value
     * @return self
     */
    public function toolbar(mixed $value = null): static
    {
        return $this->set('toolbar', $value);
    }

    /**
     * 是否显示拖拽图标
     *
     * @param bool $value
     * @return self
     */
    public function dragging(bool $value = true): static
    {
        return $this->set('dragging', $value);
    }

    /**
     * 卡片是否可选
     *
     * @param bool $value
     * @return self
     */
    public function selectable(bool $value = true): static
    {
        return $this->set('selectable', $value);
    }

    /**
     * 卡片选择按钮是否禁用
     *
     * @param bool $value
     * @return self
     */
    public function checkable(bool $value = true): static
    {
        return $this->set('checkable', $value);
    }

    /**
     * 卡片选择按钮是否选中
     *
     * @param bool $value
     * @return self
     */
    public function selected(bool $value = true): static
    {
        return $this->set('selected', $value);
    }

    /**
     * 卡片选择按钮是否隐藏
     *
     * @param bool $value
     * @return self
     */
    public function hideCheckToggler(bool $value = true): static
    {
        return $this->set('hideCheckToggler', $value);
    }

    /**
     * 卡片是否为多选
     *
     * @param bool $value
     * @return self
     */
    public function multiple(bool $value = true): static
    {
        return $this->set('multiple', $value);
    }

    /**
     * 卡片内容区的表单项 label 是否使用 Card 内部的样式
     *
     * @param bool $value
     * @return self
     */
    public function useCardLabel(bool $value = true): static
    {
        return $this->set('useCardLabel', $value);
    }
}
