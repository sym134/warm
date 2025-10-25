<?php

namespace warm\admin\model;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use warm\common\model\BaseModel;

/**
 * 管理菜单模型类
 * 
 * 该模型用于管理系统菜单项，包括：
 * 1. 菜单项的基本信息（标题、图标、排序等）
 * 2. 菜单项的类型（路由、链接、iframe、页面等）
 * 3. 菜单项的层级关系（父子菜单）
 * 4. 菜单项的扩展信息
 * 
 * 支持多语言菜单标题和层级菜单结构。
 */
class AdminMenu extends BaseModel
{
    /**
     * 不可批量赋值的属性
     * 
     * 设置为空数组表示所有属性都可以批量赋值
     * 
     * @var array
     */
    protected $guarded = [];

    /**
     * 菜单项类型常量定义
     */
    const TYPE_ROUTE  = 1;   // 路由类型
    const TYPE_LINK   = 2;   // 链接类型
    const TYPE_IFRAME = 3;   // iframe类型
    const TYPE_PAGE   = 4;   // 页面类型

    /**
     * 获取菜单项类型选项
     * 
     * 返回所有支持的菜单项类型及其对应的翻译文本
     * 
     * @return array 菜单项类型选项数组
     */
    public static function getType(): array
    {
        return [
            self::TYPE_ROUTE  => translator('admin.admin_menu.route'),
            self::TYPE_LINK   => translator('admin.admin_menu.link'),
            self::TYPE_IFRAME => translator('admin.admin_menu.iframe'),
            self::TYPE_PAGE   => translator('admin.admin_menu.page'),
        ];
    }

    /**
     * 父级菜单关联关系
     *
     * 定义当前菜单项与父级菜单项的关联关系
     * 一个菜单项属于一个父级菜单项
     *
     * @return BelongsTo 父级菜单关联关系
     */
    public function parent(): BelongsTo
    {
        return $this->belongsTo(self::class, 'parent_id');
    }

    /**
     * 菜单标题访问器
     * 
     * 获取菜单项的标题，支持多语言翻译：
     * 1. 首先尝试使用扩展命名空间获取翻译
     * 2. 如果没有找到翻译，则使用原始值
     * 
     * @return Attribute 菜单标题属性访问器
     */
    public function title(): Attribute
    {
        return Attribute::get(function ($value) {
            // 构造翻译键名
            $transKey  = ($this->extension ? $this->extension . '::' : '') . "menu.{$value}";
            // 尝试获取翻译
            $translate = translator($transKey);

            // 如果翻译键名和翻译结果相同，说明没有找到翻译，返回原始值
            return $translate == $transKey ? $value : $translate;
        });
    }
}