<?php
namespace warm\admin\renderer\form;

use warm\admin\renderer\BaseRenderer;
use warm\admin\renderer\trait\FormItem;

/**
 * InputGroup 输入框组合
 *
 * @see https://aisuda.bce.baidu.com/amis/zh-CN/components/input-group
 */
class InputGroup extends BaseRenderer
{
    use FormItem;

    public string $type = 'input-group';

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
     * 表单项集合
     *
     * @param mixed $value
     * @return self
     */
    public function body(mixed $value = null): static
    {
        return $this->set('body', $value);
    }

    /**
     * 校验相关配置, 具体配置属性如下
     *
     * @param mixed $value
     * @return self
     */
    public function validationConfig(mixed $value = null): static
    {
        return $this->set('validationConfig', $value);
    }

    /**
     * 错误提示风格, full整体飘红, partial仅错误元素飘红
     *
     * @param mixed $value
     * @return self
     */
    public function errorMode(mixed $value = null): static
    {
        return $this->set('+errorMode', $value);
    }

    /**
     * 单个子元素多条校验信息的分隔符
     *
     * @param string $value
     * @return self
     */
    public function delimiter(string $value = ';'): static
    {
        return $this->set('+delimiter', $value);
    }
}
