<?php

namespace warm\common\service;

use support\Db;
use warm\admin\service\AdminService;
use warm\common\model\WechatKey;
use warm\common\model\WechatReply;

/**
 * 微信回复服务类
 */
class WechatReplyService extends AdminService
{
    /**
     * 模型类名
     *
     * @var string
     */
    protected string $modelName = WechatReply::class;

    /**
     * 获取关键词回复列表
     *
     * @param int $page 页码
     * @param int $limit 每页数量
     * @return array
     */
    public function list(int $page = 1, int $limit = 15): array
    {
        $query = WechatKey::with('reply')
            ->where('key_type', 0) // 公众号自动回复
            ->orderBy('id', 'desc');

        $total = $query->count();
        $items = $query->forPage($page, $limit)->get();

        return compact('items', 'total');
    }

//    public function getEditData()
//    {
//
//    }

    public function store($data): bool
    {
        return $this->saveKeyReply($data);
    }

    public function update(mixed $primaryKey, array $data): bool
    {
        return $this->saveKeyReply($data, $primaryKey);
    }

    /**
     * 添加或编辑关键词回复
     *
     * @param array $data 数据
     * @param int|null $id 关键词ID（编辑时使用）
     * @return bool
     * @throws \Exception
     */
    public function saveKeyReply(array $data, int $id = null): bool
    {
        // 验证数据
        if (empty($data['keys'])) {
            throw new \InvalidArgumentException('关键词不能为空');
        }

        if (empty($data['reply']) || !is_array($data['reply'])) {
            throw new \InvalidArgumentException('回复内容不能为空');
        }

        // 开启事务
        Db::beginTransaction();
        try {
            // 保存回复内容
            $replyData = [
                'type' => $data['reply']['type'] ?? '',
                'data' => json_encode($data['reply'], JSON_UNESCAPED_UNICODE),
                'status' => $data['status'] ?? 1,
                'hide' => $data['hide'] ?? 0
            ];

            if ($id) {
                // 编辑模式
                $wechatKey = WechatKey::find($id);
                if (!$wechatKey) {
                    throw new \InvalidArgumentException('关键词不存在');
                }

                $reply = WechatReply::find($wechatKey->reply_id);
                if (!$reply) {
                    throw new \InvalidArgumentException('回复内容不存在');
                }

                $reply->fill($replyData)->save();
            } else {
                // 新增模式
                $reply = WechatReply::create($replyData);
            }

            // 保存关键词
            $keyData = [
                'reply_id' => $reply->id,
                'keys' => $data['keys'],
                'key_type' => 0 // 公众号自动回复
            ];

            if ($id) {
                $wechatKey->fill($keyData)->save();
            } else {
                WechatKey::create($keyData);
            }

            Db::commit();
            return true;
        } catch (\Exception $e) {
            Db::rollback();
            throw $e;
        }
    }

    /**
     * 获取关注回复
     *
     * @return array|null
     */
    public function getSubscribeReply(): ?array
    {
        $key = WechatKey::with('reply')
            ->where('keys', 'subscribe')
            ->where('key_type', 0)
            ->first();

        return $key?->toArray();
    }

    /**
     * 设置关注回复
     *
     * @param array $reply 回复内容
     * @return bool
     */
    public function setSubscribeReply(array $reply): bool
    {
        return $this->setFixedReply('subscribe', $reply);
    }

    /**
     * 获取默认回复
     *
     * @return array|null
     */
    public function getDefaultReply(): ?array
    {
        $key = WechatKey::with('reply')
            ->where('keys', 'default')
            ->where('key_type', 0)
            ->first();

        return $key?->toArray();
    }

    /**
     * 设置默认回复
     *
     * @param array $reply 回复内容
     * @return bool
     */
    public function setDefaultReply(array $reply): bool
    {
        return $this->setFixedReply('default', $reply);
    }

    /**
     * 设置固定关键词回复
     *
     * @param string $key 关键词
     * @param array $replyData 回复内容
     * @return bool
     */
    private function setFixedReply(string $key, array $replyData): bool
    {
        // 验证数据
        if (empty($replyData)) {
            throw new \InvalidArgumentException('回复内容不能为空');
        }

        // 开启事务
        Db::beginTransaction();
        try {
            // 查找是否已存在该关键词
            $wechatKey = WechatKey::where('keys', $key)
                ->where('key_type', 0)
                ->first();

            // 准备回复数据
            $data = [
                'type' => $replyData['type'] ?? '',
                'data' => isset($replyData['content']) ?
                    json_encode(['content' => $replyData['content']], JSON_UNESCAPED_UNICODE) :
                    json_encode(['media_id' => $replyData['media_id']], JSON_UNESCAPED_UNICODE),
                'status' => 1,
                'hide' => 0
            ];

            if ($wechatKey) {
                // 更新模式
                $reply = WechatReply::find($wechatKey->reply_id);
                if (!$reply) {
                    throw new \InvalidArgumentException('回复内容不存在');
                }

                $reply->fill($data)->save();
            } else {
                // 新增模式
                $reply = WechatReply::create($data);

                // 创建关键词
                WechatKey::create([
                    'reply_id' => $reply->id,
                    'keys' => $key,
                    'key_type' => 0
                ]);
            }

            Db::commit();
            return true;
        } catch (\Exception $e) {
            Db::rollback();
            throw $e;
        }
    }
}