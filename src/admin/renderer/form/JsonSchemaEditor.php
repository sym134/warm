<?php
namespace warm\admin\renderer\form;

use warm\admin\renderer\BaseRenderer;
use warm\admin\renderer\form\FormItemTrait;

/**
 * JsonSchemaEditor
 *
 * @see https://aisuda.bce.baidu.com/amis/zh-CN/components/json-schema-editor
 */
class JsonSchemaEditor extends BaseRenderer
{
    use FormItemTrait;

    public string $type = 'json-schema-editor';

    /**
     * 顶级类型是否可配置
     *
     * @param bool $value
     * @return self
     */
    public function rootTypeMutable(bool $value = true): self
    {
        return $this->set('rootTypeMutable', $value);
    }

    /**
     * 是否显示顶级类型信息
     *
     * @param bool $value
     * @return self
     */
    public function showRootInfo(bool $value = true): self
    {
        return $this->set('showRootInfo', $value);
    }

    /**
     * 用来禁用默认数据类型，默认类型有：string、number、interger、object、number、array、boolean、null
     *
     * @param array $value
     * @return self
     */
    public function disabledTypes(array $value = []): self
    {
        return $this->set('disabledTypes', $value);
    }

    /**
     * 用来配置预设类型
     *
     * @param array $value
     * @return self
     */
    public function definitions(array $value = []): self
    {
        return $this->set('definitions', $value);
    }

    /**
     * 用来开启迷你模式，适应于边栏面板，宽度较低的情况
     *
     * @param bool $value
     * @return self
     */
    public function mini(bool $value = true): self
    {
        return $this->set('mini', $value);
    }

    /**
     * 属性输入控件的占位提示文本
     *
     * @param mixed $value
     * @return self
     */
    public function placeholder(mixed $value = null): self
    {
        return $this->set('placeholder', $value);
    }
}
