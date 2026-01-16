<?php
namespace warm\admin\renderer\form;

use warm\admin\renderer\BaseRenderer;
use warm\admin\renderer\form\FormItemTrait;

/**
 * InputMonth
 *
 * @see https://aisuda.bce.baidu.com/amis/zh-CN/components/input-month
 */
class InputMonth extends BaseRenderer
{
    use FormItemTrait;

    public string $type = 'input-month';

    /**
     * [默认值](./date#%E9%BB%98%E8%AE%A4%E5%80%BC)
     *
     * @param string $value
     * @return self
     */
    public function value(string $value = ''): self
    {
        return $this->set('value', $value);
    }

    /**
     * 月份选择器值格式，更多格式类型请参考 [moment](http://momentjs.com/)
     *
     * @param string $value
     * @return self
     */
    public function valueFormat(string $value = 'X'): self
    {
        return $this->set('valueFormat', $value);
    }

    /**
     * 月份选择器显示格式，即时间戳格式，更多格式类型请参考 [moment](http://momentjs.com/)
     *
     * @param string $value
     * @return self
     */
    public function displayFormat(string $value = 'YYYY-MM'): self
    {
        return $this->set('displayFormat', $value);
    }

    /**
     * 占位文本
     *
     * @param string $value
     * @return self
     */
    public function placeholder(string $value = '请选择月份'): self
    {
        return $this->set('placeholder', $value);
    }

    /**
     * 是否可清除
     *
     * @param bool $value
     * @return self
     */
    public function clearable(bool $value = true): self
    {
        return $this->set('clearable', $value);
    }

    /**
     * 弹层挂载位置选择器，会通过`querySelector`获取
     *
     * @param string $value
     * @return self
     */
    public function popOverContainerSelector(string $value = ''): self
    {
        return $this->set('popOverContainerSelector', $value);
    }
}
