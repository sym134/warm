<?php
namespace warm\admin\renderer\form;

use warm\admin\renderer\BaseRenderer;
use warm\admin\renderer\trait\FormItem;
use warm\admin\renderer\trait\OnEvent;

/**
 * InputTime 时间选择器
 *
 * @see https://aisuda.bce.baidu.com/amis/zh-CN/components/input-time
 */
class InputTime extends BaseRenderer
{
    use FormItem;
    use OnEvent;

    public string $type = 'input-time';

    /**
     * [默认值](./date#%E9%BB%98%E8%AE%A4%E5%80%BC)
     *
     * @param string $value
     * @return self
     */
    public function value(string $value = ''): static
    {
        return $this->set('value', $value);
    }

    /**
     * 时间选择器值格式，更多格式类型请参考 [moment](http://momentjs.com/)
     *
     * @param string $value
     * @return self
     */
    public function valueFormat(string $value = 'X'): static
    {
        return $this->set('valueFormat', $value);
    }

    /**
     * 时间选择器显示格式，即时间戳格式，更多格式类型请参考 [moment](http://momentjs.com/)
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
    public function placeholder(string $value = '请选择时间'): static
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
     * 控制输入范围
     *
     * @param array $value
     * @return self
     */
    public function timeConstraints(array $value = []): static
    {
        return $this->set('timeConstraints', $value);
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
