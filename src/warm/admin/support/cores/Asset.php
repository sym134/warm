<?php

namespace warm\admin\support\cores;

/**
 * 资源管理类
 * 
 * 用于管理系统前端资源，包括CSS、JS、脚本和样式等
 * 提供资源的添加、获取和管理功能
 */
class Asset
{
    /** @var array JavaScript资源列表 */
    protected array $js = [];

    /** @var array CSS资源列表 */
    protected array $css = [];

    /** @var array 脚本资源列表 */
    protected array $scripts = [];

    /** @var array 样式资源列表 */
    protected array $styles = [];

    /** @var mixed 追加导航内容 */
    protected $appendNav;

    /** @var mixed 预置导航内容 */
    protected $prependNav;

    /**
     * 资源处理方法
     * 
     * 统一处理各种类型的资源添加和获取
     * 
     * @param string $type 资源类型
     * @param array|string|null $assets 资源内容
     * @return $this|array 当$assets为null时返回资源列表，否则返回当前实例
     */
    private function assetsHandler($type, $assets)
    {
        if (is_null($assets)) {
            return $this->{$type};
        }

        if (is_array($assets)) {
            $this->{$type} = array_merge($this->{$type}, $assets);
        } else {
            $this->{$type}[] = $assets;
        }

        return $this;
    }

    /**
     * JavaScript资源管理
     * 
     * @param array|string|null $js JS资源
     * @return $this|array 当$js为null时返回JS资源列表，否则返回当前实例
     */
    public function js($js = null)
    {
        return $this->assetsHandler('js', $js);
    }

    /**
     * CSS资源管理
     * 
     * @param array|string|null $css CSS资源
     * @return $this|array 当$css为null时返回CSS资源列表，否则返回当前实例
     */
    public function css($css = null)
    {
        return $this->assetsHandler('css', $css);
    }

    /**
     * 脚本资源管理
     * 
     * @param array|string|null $scripts 脚本资源
     * @return $this|array 当$scripts为null时返回脚本资源列表，否则返回当前实例
     */
    public function scripts($scripts = null)
    {
        return $this->assetsHandler('scripts', $scripts);
    }

    /**
     * 样式资源管理
     * 
     * @param array|string|null $styles 样式资源
     * @return $this|array 当$styles为null时返回样式资源列表，否则返回当前实例
     */
    public function styles($styles = null)
    {
        return $this->assetsHandler('styles', $styles);
    }

    /**
     * 追加导航内容管理
     * 
     * @param mixed $appendNav 追加的导航内容
     * @return static|null 当$appendNav为null时返回当前追加的导航内容，否则返回当前实例
     */
    public function appendNav($appendNav = null): static|null
    {
        if (is_null($appendNav)) {
            return $this->appendNav;
        }

        $this->appendNav = $appendNav;

        return $this;
    }

    /**
     * 预置导航内容管理
     * 
     * @param mixed $prependNav 预置的导航内容
     * @return static|null 当$prependNav为null时返回当前预置的导航内容，否则返回当前实例
     */
    public function prependNav($prependNav = null): static|null
    {
        if (is_null($prependNav)) {
            return $this->prependNav;
        }

        $this->prependNav = $prependNav;

        return $this;
    }
}