<?php
namespace warm\admin\renderer;
/**
 * Spinner
 *
 * @see https://aisuda.bce.baidu.com/amis/zh-CN/components/spinner
 */
class Spinner extends BaseRenderer
{
    public string $type = 'spinner';

    /**
     * 是否显示 spinner 组件
     *
     * @param bool $value
     * @return self
     */
    public function show(bool $value = true): static
    {
        return $this->set('show', $value);
    }

    /**
     * 是否显示 spinner 组件的条件
     *
     * @param mixed $value
     * @return self
     */
    public function showOn(mixed $value = true): static
    {
        return $this->set('showOn', $value);
    }

    /**
     * 设置组件样式
     *
     * @param mixed $value
     * @return $this
     */
    public function className(mixed $value=''): static
    {
        return $this->set('className', $value);
    }

    /**
     * 组件中 icon 所在标签的自定义 class
     *
     * @param string $value
     * @return self
     */
    public function spinnerClassName(string $value = ''): static
    {
        return $this->set('spinnerClassName', $value);
    }

    /**
     * 作为容器使用时组件最外层标签的自定义 class
     *
     * @param string $value
     * @return self
     */
    public function spinnerWrapClassName(string $value = ''): static
    {
        return $this->set('spinnerWrapClassName', $value);
    }

    /**
     * 组件大小 `sm` `lg`
     *
     * @param string $value
     * @return self
     */
    public function size(string $value = ''): static
    {
        return $this->set('size', $value);
    }

    /**
     * 组件图标，可以是`amis`内置图标，也可以是字体图标或者网络图片链接，作为 ui 库使用时也可以是自定义组件
     *
     * @param string $value
     * @return self
     */
    public function icon(string $value = ''): static
    {
        return $this->set('icon', $value);
    }

    /**
     * 配置组件文案，例如`加载中...`
     *
     * @param string $value
     * @return self
     */
    public function tip(string $value = ''): static
    {
        return $this->set('tip', $value);
    }

    /**
     * 配置组件 `tip` 相对于 `icon` 的位置
     *
     * @param mixed $value
     * @return self
     */
    public function tipPlacement(mixed $value = null): static
    {
        return $this->set('tipPlacement', $value);
    }

    /**
     * 配置组件显示延迟的时间（毫秒）
     *
     * @param int|float $value
     * @return self
     */
    public function delay(int|float $value = 0): static
    {
        return $this->set('delay', $value);
    }

    /**
     * 配置组件显示 spinner 时是否显示遮罩层
     *
     * @param bool $value
     * @return self
     */
    public function overlay(bool $value = true): static
    {
        return $this->set('overlay', $value);
    }

    /**
     * 作为容器使用时，被包裹的内容
     *
     * @param mixed $value
     * @return self
     */
    public function body(mixed $value = null): static
    {
        return $this->set('body', $value);
    }

    /**
     * 为 `Spinner` 指定挂载的容器, `root` 是一个 selector，在拥有`Spinner`的组件上都可以通过传递`loadingConfig`改变 Spinner 的挂载位置，开启后，会强制开启属性`overlay=true`，并且`icon`会失效
     *
     * @param mixed $value
     * @return self
     */
    public function loadingConfig(mixed $value = null): static
    {
        return $this->set('loadingConfig', $value);
    }
}
