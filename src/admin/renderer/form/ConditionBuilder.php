<?php
namespace warm\admin\renderer\form;

use warm\admin\renderer\BaseRenderer;
use warm\admin\renderer\form\FormItemTrait;

/**
 * ConditionBuilder
 *
 * @see https://aisuda.bce.baidu.com/amis/zh-CN/components/condition-builder
 */
class ConditionBuilder extends BaseRenderer
{
    use FormItemTrait;

    public string $type = 'condition-builder';

    /**
     * 外层 dom 类名
     *
     * @param string $value
     * @return self
     */
    public function className(string $value = ''): self
    {
        return $this->set('className', $value);
    }

    /**
     * 输入字段的类名
     *
     * @param string $value
     * @return self
     */
    public function fieldClassName(string $value = ''): self
    {
        return $this->set('fieldClassName', $value);
    }

    /**
     * 通过远程拉取配置项
     *
     * @param string $value
     * @return self
     */
    public function source(string $value = ''): self
    {
        return $this->set('source', $value);
    }

    /**
     * 内嵌展示
     *
     * @param bool $value
     * @return self
     */
    public function embed(bool $value = true): self
    {
        return $this->set('embed', $value);
    }

    /**
     * 弹窗配置的顶部标题
     *
     * @param string $value
     * @return self
     */
    public function title(string $value = ''): self
    {
        return $this->set('title', $value);
    }

    /**
     * 字段配置
     *
     * @param mixed $value
     * @return self
     */
    public function fields(mixed $value = null): self
    {
        return $this->set('fields', $value);
    }

    /**
     * 用于 simple 模式下显示切换按钮
     *
     * @param bool $value
     * @return self
     */
    public function showANDOR(bool $value = true): self
    {
        return $this->set('showANDOR', $value);
    }

    /**
     * 是否显示「非」按钮
     *
     * @param bool $value
     * @return self
     */
    public function showNot(bool $value = true): self
    {
        return $this->set('showNot', $value);
    }

    /**
     * 是否可拖拽
     *
     * @param bool $value
     * @return self
     */
    public function draggable(bool $value = true): self
    {
        return $this->set('draggable', $value);
    }

    /**
     * 字段是否可搜索
     *
     * @param bool $value
     * @return self
     */
    public function searchable(bool $value = true): self
    {
        return $this->set('searchable', $value);
    }

    /**
     * `'chained'`
     *
     * @param mixed $value
     * @return self
     */
    public function selectMode(mixed $value = null): self
    {
        return $this->set('selectMode', $value);
    }

    /**
     * 表达式：控制按钮“添加条件”的显示。参数为`depth`、`breadth`，分别代表深度、长度。表达式需要返回`boolean`类型
     *
     * @param string $value
     * @return self
     */
    public function addBtnVisibleOn(string $value = ''): self
    {
        return $this->set('addBtnVisibleOn', $value);
    }

    /**
     * 表达式：控制按钮“添加条件组”的显示。参数为`depth`、`breadth`，分别代表深度、长度。表达式需要返回`boolean`类型
     *
     * @param string $value
     * @return self
     */
    public function addGroupBtnVisibleOn(string $value = ''): self
    {
        return $this->set('addGroupBtnVisibleOn', $value);
    }

    /**
     * 开启公式编辑模式时的输入控件类型
     *
     * @param mixed $value
     * @return self
     */
    public function inputSettings(mixed $value = null): self
    {
        return $this->set('inputSettings', $value);
    }

    /**
     * 字段输入控件变成公式编辑器。
     *
     * @param array $value
     * @return self
     */
    public function formula(array $value = []): self
    {
        return $this->set('formula', $value);
    }

    /**
     * 开启后条件中额外还能配置启动条件。
     *
     * @param bool $value
     * @return self
     */
    public function showIf(bool $value = true): self
    {
        return $this->set('showIf', $value);
    }

    /**
     * 给 showIF 表达式用的公式信息
     *
     * @param array $value
     * @return self
     */
    public function formulaForIf(array $value = []): self
    {
        return $this->set('formulaForIf', $value);
    }

    /**
     * 是否限制字段唯一，也就是说不允许一个字段设置在两个规则里面
     *
     * @param bool $value
     * @return self
     */
    public function uniqueFields(bool $value = true): self
    {
        return $this->set('uniqueFields', $value);
    }
}
