<?php
namespace warm\admin\renderer;
/**
 * Toast
 *
 * @see https://aisuda.bce.baidu.com/amis/zh-CN/components/toast
 */
class Toast extends BaseRenderer
{
    public string $type = 'toast';

    /**
     * 指定为 toast 轻提示组件
     *
     * @param string $value
     * @return self
     */
    public function actionType(string $value = 'toast'): self
    {
        return $this->set('actionType', $value);
    }

    /**
     * 轻提示内容
     *
     * @param array $value
     * @return self
     */
    public function items(array $value = []): self
    {
        return $this->set('items', $value);
    }

    /**
     * 提示显示位置，可用'top-right'、'top-center'、'top-left'、'bottom-center'、'bottom-left'、'bottom-right'、'center'
     *
     * @param string $value
     * @return self
     */
    public function position(string $value = 'top-center（移动端为center）'): self
    {
        return $this->set('position', $value);
    }

    /**
     * 是否展示关闭按钮，移动端不展示
     *
     * @param bool $value
     * @return self
     */
    public function closeButton(bool $value = true): self
    {
        return $this->set('closeButton', $value);
    }

    /**
     * 是否展示图标
     *
     * @param bool $value
     * @return self
     */
    public function showIcon(bool $value = true): self
    {
        return $this->set('showIcon', $value);
    }

    /**
     * 持续时间
     *
     * @param int|float $value
     * @return self
     */
    public function timeout(int|float $value = 0): self
    {
        return $this->set('timeout', $value);
    }
}
