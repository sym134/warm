<?php
namespace warm\admin\renderer\form;

use warm\admin\renderer\BaseRenderer;
use warm\admin\renderer\trait\FormItem;
use warm\admin\renderer\trait\OnEvent;

/**
 * InputTimeRange 时间范围选择器
 *
 * @see https://aisuda.bce.baidu.com/amis/zh-CN/components/input-time-range
 */
class InputTimeRange extends BaseRenderer
{
    use FormItem;
    use OnEvent;

    public string $type = 'input-time-range';

    /**
     * [时间范围选择器值格式](./date#%E5%80%BC%E6%A0%BC%E5%BC%8F)
     *
     * @param string $value
     * @return self
     */
    public function valueFormat(string $value = 'HH:mm'): static
    {
        return $this->set('valueFormat', $value);
    }

    /**
     * [时间范围选择器显示格式](./date#%E6%98%BE%E7%A4%BA%E6%A0%BC%E5%BC%8F)
     *
     * @param string $value
     * @return self
     */
    public function displayFormat(string $value = 'HH:mm'): static
    {
        return $this->set('displayFormat', $value);
    }

    /**
     * 占位文本
     *
     * @param string $value
     * @return self
     */
    public function placeholder(string $value = '请选择时间范围'): static
    {
        return $this->set('placeholder', $value);
    }

    /**
     * 是否可清除
     *
     * @param bool $value
     * @return self
     */
    public function clearable(bool $value = true): static
    {
        return $this->set('clearable', $value);
    }

    /**
     * 是否内联模式
     *
     * @param bool $value
     * @return self
     */
    public function embed(bool $value = true): static
    {
        return $this->set('embed', $value);
    }

    /**
     * 是否启用游标动画
     *
     * @param bool $value
     * @return self
     */
    public function animation(bool $value = true): static
    {
        return $this->set('animation', $value);
    }

    /**
     * 是否存成两个字段
     *
     * @param string $value
     * @return self
     */
    public function extraName(string $value = ''): static
    {
        return $this->set('extraName', $value);
    }

    /**
     * 弹层挂载位置选择器，会通过`querySelector`获取
     *
     * @param string $value
     * @return self
     */
    public function popOverContainerSelector(string $value = ''): static
    {
        return $this->set('popOverContainerSelector', $value);
    }
}
