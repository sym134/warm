<?php

namespace warm\admin\model\system;

use Illuminate\Database\Eloquent\Relations\HasMany;
use warm\common\model\BaseModel as Model;

/**
 * 微信菜单模型类
 * 
 * 用于存储和管理微信公众号自定义菜单配置
 */
class WechatMenu extends Model
{
    /**
     * 与模型关联的表名
     * 
     * @var string
     */
    protected $table = 'wechat_menu';

    /**
     * 需要进行类型转换的字段
     * 
     * @var array
     */
    protected $casts = [
        'sub_button' => 'json',  // 子菜单
    ];

    /**
     * 可批量赋值的字段
     * 
     * @var array
     */
    protected $fillable = [
        'name',
        'type',
        'key',
        'url',
        'appid',
        'pagepath',
        'miniprogram_url',
        'parent_id',
        'sort',
    ];

    /**
     * 一级菜单类型
     */
    const TYPE_CLICK = 'click';      // 点击事件
    const TYPE_VIEW = 'view';        // 跳转链接
    const TYPE_MINIPROGRAM = 'miniprogram'; // 小程序

    /**
     * 获取子菜单
     * 
     * @return HasMany
     */
    public function subMenus(): HasMany
    {
        return $this->hasMany(WechatMenu::class, 'parent_id', 'id')
            ->orderBy('sort', 'asc');
    }

    /**
     * 获取父菜单
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function parentMenu()
    {
        return $this->belongsTo(WechatMenu::class, 'parent_id', 'id');
    }
}

