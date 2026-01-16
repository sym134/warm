<?php
namespace warm\admin\renderer\form;

use warm\admin\renderer\BaseRenderer;
use warm\admin\renderer\form\FormItemTrait;

/**
 * Fieldset
 *
 * @see https://aisuda.bce.baidu.com/amis/zh-CN/components/fieldset
 */
class Fieldset extends BaseRenderer
{
    use FormItemTrait;

    public string $type = 'fieldset';

    /**
     * CSS 类名
     *
     * @param string $value
     * @return self
     */
    public function className(string $value = ''): self
    {
        return $this->set('className', $value);
    }

    /**
     * 标题 CSS 类名
     *
     * @param string $value
     * @return self
     */
    public function headingClassName(string $value = ''): self
    {
        return $this->set('headingClassName', $value);
    }

    /**
     * 内容区域 CSS 类名
     *
     * @param string $value
     * @return self
     */
    public function bodyClassName(string $value = ''): self
    {
        return $this->set('bodyClassName', $value);
    }

    /**
     * 标题
     *
     * @param mixed $value
     * @return self
     */
    public function title(mixed $value = null): self
    {
        return $this->set('title', $value);
    }

    /**
     * 表单项集合
     *
     * @param mixed $value
     * @return self
     */
    public function body(mixed $value = null): self
    {
        return $this->set('body', $value);
    }

    /**
     * 展示默认，同 [Form](./index#%E8%A1%A8%E5%8D%95%E5%B1%95%E7%A4%BA) 中的模式
     *
     * @param string $value
     * @return self
     */
    public function mode(string $value = ''): self
    {
        return $this->set('mode', $value);
    }

    /**
     * 是否可折叠
     *
     * @param bool $value
     * @return self
     */
    public function collapsable(bool $value = true): self
    {
        return $this->set('collapsable', $value);
    }

    /**
     * 默认是否折叠
     *
     * @param mixed $value
     * @return self
     */
    public function collapsed(mixed $value = false): self
    {
        return $this->set('collapsed', $value);
    }

    /**
     * 收起的标题
     *
     * @param mixed $value
     * @return self
     */
    public function collapseTitle(mixed $value = null): self
    {
        return $this->set('collapseTitle', $value);
    }

    /**
     * 大小，支持 xs、sm、base、md、lg
     *
     * @param string $value
     * @return self
     */
    public function size(string $value = ''): self
    {
        return $this->set('size', $value);
    }
}
