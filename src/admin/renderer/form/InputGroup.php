<?php
namespace warm\admin\renderer\form;

use warm\admin\renderer\BaseRenderer;
use warm\admin\renderer\form\FormItemTrait;

/**
 * InputGroup
 *
 * @see https://aisuda.bce.baidu.com/amis/zh-CN/components/input-group
 */
class InputGroup extends BaseRenderer
{
    use FormItemTrait;

    public string $type = 'input-group';

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
     * 校验相关配置, 具体配置属性如下
     *
     * @param mixed $value
     * @return self
     */
    public function validationConfig(mixed $value = null): self
    {
        return $this->set('validationConfig', $value);
    }

    /**
     * 错误提示风格, full整体飘红, partial仅错误元素飘红
     *
     * @param mixed $value
     * @return self
     */
    public function errorMode(mixed $value = null): self
    {
        return $this->set('+errorMode', $value);
    }

    /**
     * 单个子元素多条校验信息的分隔符
     *
     * @param string $value
     * @return self
     */
    public function delimiter(string $value = ';'): self
    {
        return $this->set('+delimiter', $value);
    }
}
