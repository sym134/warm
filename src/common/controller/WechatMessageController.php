<?php

namespace warm\common\controller;

use support\Log;
use support\Request;
use support\Response;
use warm\common\wechat\OfficialAccount;
use warm\common\model\WechatKey;
use warm\common\service\WechatReplyService;
use Symfony\Component\HttpFoundation\HeaderBag;
use Symfony\Component\HttpFoundation\Request as SymfonyRequest;

/**
 * 微信公众号消息处理控制器
 * 
 * 处理微信服务器推送的消息，包括：
 * - Token验证（GET请求）
 * - 消息接收和处理（POST请求）
 * - 关注回复
 * - 关键词回复
 * - 默认回复
 */
class WechatMessageController
{
    /**
     * 微信回复服务
     *
     * @var WechatReplyService
     */
    protected WechatReplyService $replyService;

    public function __construct()
    {
        $this->replyService = new WechatReplyService();
    }

    /**
     * 处理微信服务器请求
     * 
     * GET请求：Token验证
     * POST请求：消息接收和处理
     *
     * @param Request $request
     * @return Response
     */
    public function handle(Request $request): Response
    {
        Log::info('handle', $request->all());
        if ($request->method() === 'GET') {
            return $this->verifyToken($request);
        }

        if ($request->method() === 'POST') {
            return $this->handleMessage($request);
        }

        return response('Method Not Allowed', 405);
    }

    /**
     * Token验证（微信服务器验证）
     * 
     * 微信服务器会发送GET请求来验证服务器有效性
     * 参数：signature, timestamp, nonce, echostr
     *
     * @param Request $request
     * @return Response
     */
    protected function verifyToken(Request $request): Response
    {
        Log::info('verifyToken', $request->all());
        try {
            $api = new OfficialAccount();
            $app = $api->app();
            $symfony_request = new SymfonyRequest($request->get(), $request->post(), [], $request->cookie(), [], [], $request->rawBody());
            $symfony_request->headers = new HeaderBag($request->header());
            $app->setRequestFromSymfonyRequest($symfony_request); //必须替换服务端请求
            $server = $app->getServer();

            // EasyWeChat 会自动验证签名并返回 echostr
            $response = $server->serve();

            return response($response->getBody()->getContents(), $response->getStatusCode(), $response->getHeaders());
        } catch (\Exception $e) {
            // 验证失败时返回错误
            return response('验证失败：' . $e->getMessage(), 403);
        }
    }

    /**
     * 处理微信消息
     * 
     * 接收微信服务器推送的消息，并根据消息类型进行回复
     *
     * @param Request $request
     * @return Response
     */
    protected function handleMessage(Request $request): Response
    {
        try {
            $api = new OfficialAccount();
            $app = $api->app();
            $symfony_request = new SymfonyRequest($request->get(), $request->post(), [], $request->cookie(), [], [], $request->rawBody());
            $symfony_request->headers = new HeaderBag($request->header());
            $app->setRequestFromSymfonyRequest($symfony_request); //必须替换服务端请求
            $server = $app->getServer();

            // 使用 with() 方法设置消息处理器（EasyWeChat 6.x 方式）
            $server->with(function ($message, \Closure $next) {
                try {
                    Log::info('处理微信消息', [
                        'message_type' => get_class($message),
                        'msg_type' => $message->MsgType ?? null,
                        'from_user' => $message->FromUserName ?? null
                    ]);
                    
                    $reply = $this->processMessage($message);

                    // 如果返回空字符串，不回复
                    if (empty($reply)) {
                        return '';
                    }

                    // 返回回复内容
                    return $reply;
                } catch (\Exception $e) {
                    Log::error('消息处理异常', [
                        'error' => $e->getMessage(),
                        'trace' => $e->getTraceAsString()
                    ]);
                    return '';
                }
            });

            // 处理消息并返回响应
            $response = $server->serve();

            // serve() 方法返回的是字符串或 Response 对象
            $content = is_string($response) ? $response : (string)$response;

            return response($content, 200, [
                'Content-Type' => 'application/xml; charset=utf-8'
            ]);
        } catch (\Exception $e) {
            // 记录错误日志
            \support\Log::error('微信消息处理失败：' . $e->getMessage(), [
                'exception' => $e,
                'request' => $request->all()
            ]);

            // 返回空响应，避免微信重试
            return response('success', 200);
        }
    }

    /**
     * 处理消息并返回回复内容
     *
     * @param object $message 微信消息对象
     * @return string|array 回复内容
     */
    protected function processMessage($message): string|array
    {
        $msgType = $message->MsgType ?? '';
        $openid = $message->FromUserName ?? '';

        // 处理关注事件
        if ($msgType === 'event') {
            $event = $message->Event ?? '';
            if ($event === 'subscribe') {
                return $this->handleSubscribe($openid);
            }
        }

        // 处理文本消息
        if ($msgType === 'text') {
            $content = $message->Content ?? '';
            return $this->handleTextMessage($openid, $content);
        }

        // 其他消息类型返回默认回复
        return $this->getDefaultReply($openid);
    }

    /**
     * 处理关注事件
     *
     * @param string $openid 用户openid
     * @return string|array 回复内容
     */
    protected function handleSubscribe(string $openid): string|array
    {
        $reply = $this->replyService->getSubscribeReply();

        if ($reply && isset($reply['reply']) && $reply['reply']['status'] == 1) {
            return $this->formatReply($reply['reply'], $openid);
        }

        // 如果没有配置关注回复，返回默认回复
        return $this->getDefaultReply($openid);
    }

    /**
     * 处理文本消息（关键词回复）
     *
     * @param string $openid 用户openid
     * @param string $content 消息内容
     * @return string|array 回复内容
     */
    protected function handleTextMessage(string $openid, string $content): string|array
    {
        // 查找匹配的关键词
        $key = WechatKey::with('reply')
            ->where('key_type', 1) // 公众号自动回复
            ->where('keys', $content) // 精确匹配
            ->whereHas('reply', function ($query) {
                $query->where('status', 1); // 只查询启用的回复
            })
            ->first();

        if ($key && $key->reply) {
            return $this->formatReply($key->reply->toArray(), $openid);
        }

        // 没有匹配的关键词，返回默认回复
        return $this->getDefaultReply($openid);
    }

    /**
     * 获取默认回复
     *
     * @param string $openid 用户openid
     * @return string|array 回复内容
     */
    protected function getDefaultReply(string $openid): string|array
    {
        $reply = $this->replyService->getDefaultReply();

        if ($reply && isset($reply['reply']) && $reply['reply']['status'] == 1) {
            return $this->formatReply($reply['reply'], $openid);
        }

        // 如果没有配置默认回复，返回空字符串（不回复）
        return '';
    }

    /**
     * 格式化回复内容
     * 
     * 根据回复类型（text, image, news, voice, video）生成对应的回复内容
     * EasyWeChat 会自动将返回的数组转换为 XML 格式
     *
     * @param array $reply 回复数据
     * @param string $openid 用户openid（此参数保留用于未来扩展）
     * @return string|array 格式化后的回复内容
     */
    protected function formatReply(array $reply, string $openid): string|array
    {
        $type = $reply['type'] ?? 'text';

        // 确保 data 是数组格式（WechatReply 模型会自动转换 JSON 为数组）
        $data = $reply['data'] ?? [];
        if (!is_array($data)) {
            $data = [];
        }

        switch ($type) {
            case 'text':
                // 文本回复 - 直接返回字符串，EasyWeChat 会自动处理
                $content = $data['content'] ?? '感谢您的关注！';
                return !empty($content) ? $content : '感谢您的关注！';

            case 'image':
                // 图片回复 - 返回数组格式
                $mediaId = $data['media_id'] ?? '';
                if (empty($mediaId)) {
                    return '感谢您的关注！';
                }
                return [
                    'MsgType' => 'image',
                    'Image' => [
                        'MediaId' => $mediaId
                    ]
                ];

            case 'news':
                // 图文回复
                if (isset($data['articles']) && is_array($data['articles']) && !empty($data['articles'])) {
                    $articles = [];
                    foreach ($data['articles'] as $article) {
                        if (is_array($article)) {
                            $articles[] = [
                                'Title' => $article['title'] ?? '',
                                'Description' => $article['description'] ?? '',
                                'Url' => $article['url'] ?? '',
                                'PicUrl' => $article['picurl'] ?? $article['pic_url'] ?? ''
                            ];
                        }
                    }
                    if (!empty($articles)) {
                        return [
                            'MsgType' => 'news',
                            'ArticleCount' => count($articles),
                            'Articles' => $articles
                        ];
                    }
                }
                // 图文数据无效，返回默认文本
                return '感谢您的关注！';

            case 'voice':
                // 语音回复
                $mediaId = $data['media_id'] ?? '';
                if (empty($mediaId)) {
                    return '感谢您的关注！';
                }
                return [
                    'MsgType' => 'voice',
                    'Voice' => [
                        'MediaId' => $mediaId
                    ]
                ];

            case 'video':
                // 视频回复
                $mediaId = $data['media_id'] ?? '';
                if (empty($mediaId)) {
                    return '感谢您的关注！';
                }
                return [
                    'MsgType' => 'video',
                    'Video' => [
                        'MediaId' => $mediaId,
                        'Title' => $data['title'] ?? '',
                        'Description' => $data['description'] ?? ''
                    ]
                ];
        }

        // 默认返回文本回复
        return '感谢您的关注！';
    }
}
