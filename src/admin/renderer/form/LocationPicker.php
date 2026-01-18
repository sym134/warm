<?php
namespace warm\admin\renderer\form;

use warm\admin\renderer\BaseRenderer;
use warm\admin\renderer\trait\FormItem;
use warm\admin\renderer\trait\OnEvent;

/**
 * LocationPicker 地理位置选择器
 *
 * @see https://aisuda.bce.baidu.com/amis/zh-CN/components/location-picker
 */
class LocationPicker extends BaseRenderer
{
    use FormItem;
    use OnEvent;

    public string $type = 'location-picker';

    /**
     * 
     *
     * @param mixed $value
     * @return self
     */
    public function value(mixed $value = null): static
    {
        return $this->set('value', $value);
    }

    /**
     * 'baidu'
     *
     * @param mixed $value
     * @return self
     */
    public function vendor(mixed $value = null): static
    {
        return $this->set('vendor', $value);
    }

    /**
     * 百度/高德地图的 ak
     *
     * @param string $value
     * @return self
     */
    public function ak(string $value = '无'): static
    {
        return $this->set('ak', $value);
    }

    /**
     * 输入框是否可清空
     *
     * @param bool $value
     * @return self
     */
    public function clearable(bool $value = true): static
    {
        return $this->set('clearable', $value);
    }

    /**
     * 默认提示
     *
     * @param string $value
     * @return self
     */
    public function placeholder(string $value = '请选择位置'): static
    {
        return $this->set('placeholder', $value);
    }

    /**
     * 是否自动选中当前地理位置
     *
     * @param bool $value
     * @return self
     */
    public function autoSelectCurrentLoc(bool $value = true): static
    {
        return $this->set('autoSelectCurrentLoc', $value);
    }

    /**
     * 是否限制只能选中当前地理位置，设置为 true 后，可用于充当定位组件
     *
     * @param bool $value
     * @return self
     */
    public function onlySelectCurrentLoc(bool $value = true): static
    {
        return $this->set('onlySelectCurrentLoc', $value);
    }

    /**
     * 'bd09'
     *
     * @param mixed $value
     * @return self
     */
    public function coordinatesType(mixed $value = null): static
    {
        return $this->set('coordinatesType', $value);
    }

    /**
     * 静态展示，内嵌模式`{static: true: embed: true}`时的额外配置
     *
     * @param array $value
     * @return self
     */
    public function staticSchema(array $value = []): static
    {
        return $this->set('staticSchema', $value);
    }
}
