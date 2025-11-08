<?php

namespace warm\common\model;

use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * 微信回复内容模型
 * 
 * @property int $id 微信关键字回复id
 * @property string $type 回复类型
 * @property string $data 回复数据
 * @property int $status 0=不可用 1=可用
 * @property int $hide 是否隐藏
 * @property WechatKey $key 关联的关键词
 */
class WechatReply extends BaseModel
{
    /**
     * 表名
     * @var string
     */
    protected $table = 'wechat_reply';

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
    protected $fillable = ['type', 'data', 'status', 'hide'];

    /**
     * 类型转换
     * @var array
     */
    protected $casts = [
        'status' => 'integer',
        'hide' => 'integer',
    ];

    /**
     * 自动转换data字段为数组
     *
     * @param string $value
     * @return array|null
     */
    public function getDataAttribute($value)
    {
        if (empty($value)) {
            return null;
        }
        
        $data = json_decode($value, true);
        return is_array($data) ? $data : null;
    }

    /**
     * 自动将data字段转换为JSON存储
     *
     * @param array $value
     * @return void
     */
    public function setDataAttribute($value)
    {
        $this->attributes['data'] = is_array($value) ? json_encode($value, JSON_UNESCAPED_UNICODE) : $value;
    }

    /**
     * 关联关键词
     *
     * @return HasOne
     */
    public function key()
    {
        return $this->hasOne(WechatKey::class, 'reply_id', 'id');
    }
}