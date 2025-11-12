<?php

namespace warm\common\model;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * 微信关键词模型
 * 
 * @property int $id 自增ID
 * @property int $reply_id 回复内容id
 * @property string $keys 关键词
 * @property int $key_type 回复类型，0公众号自动回复，1客服自动回复
 * @property WechatReply $reply 关联的回复内容
 */
class WechatKey extends BaseModel
{
    /**
     * 表名
     * @var string
     */
    protected $table = 'wechat_key';

    /**
     * 主键
     * @var string
     */
    protected $primaryKey = 'id';

    /**
     * 是否自动维护时间戳
     * @var bool
     */
    public $timestamps = false;

    /**
     * 可批量赋值的属性
     * @var array
     */
    protected $fillable = ['reply_id', 'keys', 'key_type'];

    /**
     * 类型转换
     * @var array
     */
    protected $casts = [
        'reply_id' => 'integer',
        'key_type' => 'integer',
    ];

    /**
     * 关联回复内容
     *
     * @return BelongsTo
     */
    public function reply()
    {
        return $this->belongsTo(WechatReply::class, 'reply_id', 'id');
    }
}