<?php
namespace warm\admin\renderer\form;

use warm\admin\renderer\BaseRenderer;
use warm\admin\renderer\trait\FormItem;

/**
 * Group
 *
 * @see https://aisuda.bce.baidu.com/amis/zh-CN/components/group
 */
class Group extends BaseRenderer
{
    use FormItem;

    public string $type = 'group';

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
     * group 的标签
     *
     * @param string $value
     * @return self
     */
//    public function label(string $value = ''): static
//    {
//        return $this->set('label', $value);
//    }

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
     * 展示默认，同 [Form](./index#%E8%A1%A8%E5%8D%95%E5%B1%95%E7%A4%BA) 中的模式
     *
     * @param string $value
     * @return self
     */
    public function mode(string $value = ''): static
    {
        return $this->set('mode', $value);
    }

    /**
     * 表单项之间的间距，可选：`xs`、`sm`、`normal`
     *
     * @param string $value
     * @return self
     */
    public function gap(string $value = ''): static
    {
        return $this->set('gap', $value);
    }

    /**
     * 可以配置水平展示还是垂直展示。对应的配置项分别是：`vertical`、`horizontal`
     *
     * @param string $value
     * @return self
     */
    public function direction(string $value = 'horizontal'): static
    {
        return $this->set('direction', $value);
    }
}
