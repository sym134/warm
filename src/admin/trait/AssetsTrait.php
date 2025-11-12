<?php

namespace warm\admin\trait;

use warm\admin\support\cores\Asset;

/**
 * 资源Trait
 * 
 * 提供前端资源管理功能，包括JS、CSS文件和脚本的加载与管理
 * 用于在Admin系统中便捷地添加和管理前端资源
 */
trait AssetsTrait
{
    /**
     * 创建Asset实例
     * 
     * @return Asset Asset实例
     */
    public static function asset(): Asset
    {
        return new Asset;
    }

    /**
     * 加载 js 文件
     *
     * @param string|array|null $js JS文件路径或路径数组
     * @return Asset Asset实例
     */
    public static function js($js = null): Asset
    {
        return static::asset()->js($js);
    }

    /**
     * 加载 css 文件
     *
     * @param string|array|null $css CSS文件路径或路径数组
     * @return Asset Asset实例
     */
    public static function css($css = null): Asset
    {
        return static::asset()->css($css);
    }

    /**
     * 加载 js 脚本
     *
     * @param string|array|null $scripts JS脚本代码或脚本数组
     * @return Asset Asset实例
     */
    public static function scripts($scripts = null): Asset
    {
        return static::asset()->scripts($scripts);
    }

    /**
     * 加载样式表
     *
     * @param string|array|null $styles CSS样式代码或样式数组
     * @return Asset Asset实例
     */
    public static function styles($styles = null): Asset
    {
        return static::asset()->styles($styles);
    }

    /**
     * 获取所有资源
     * 
     * @return array 包含所有类型资源的数组
     */
    public static function getAssets(): array
    {
        return [
            'js'      => static::asset()->js(),
            'css'     => static::asset()->css(),
            'scripts' => static::asset()->scripts(),
            'styles'  => static::asset()->styles(),
        ];
    }

    /**
     * 在后面添加 Nav
     *
     * @param mixed $appendNav 要追加的导航内容
     * @return Asset Asset实例
     */
    public static function appendNav($appendNav = null): Asset
    {
        return static::asset()->appendNav($appendNav);
    }

    /**
     * 在前面添加 Nav
     *
     * @param mixed $prependNav 要前置的导航内容
     * @return Asset Asset实例
     */
    public static function prependNav($prependNav = null): Asset
    {
        return static::asset()->prependNav($prependNav);
    }

    /**
     * 获取导航内容
     * 
     * @return array 包含前置和追加导航内容的数组
     */
    public static function getNav(): array
    {
        return [
            'appendNav'  => static::asset()->appendNav(),
            'prependNav' => static::asset()->prependNav(),
        ];
    }
}