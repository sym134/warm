<?php

namespace warm\admin\controller\system;

use support\Request;
use support\Response;
use warm\admin\controller\AdminController;
use warm\admin\renderer\form\Form;
use warm\admin\renderer\Page;
use warm\common\service\WechatReplyService;

/**
 * 微信回复控制器
 * @extends AdminController<WechatReplyService>
 */
class WechatReplyController extends AdminController
{
    /**
     * @var string 服务类名
     */
    protected string $serviceName = WechatReplyService::class;

    /**
     * 关键词回复列表页面
     *
     * @return Page
     */
    public function list(): Page
    {
        $crud = $this->baseCRUD()
            ->filter(
                amis()->Form()->body([
                    amis()->InputText('keys', translator('wechat_reply.list.keyword'))->clearable(true),
                    amis()->Select('reply.status', translator('wechat_reply.list.status'))->options([
                        0 => translator('wechat_reply.list.status_disabled'),
                        1 => translator('wechat_reply.list.status_enabled')
                    ])->clearable(true),
                ])
            )
            ->columns([
                amis()->TableColumn('id', 'ID')->sortable(),
                amis()->TableColumn('keys', translator('wechat_reply.list.keyword')),
                amis()->TableColumn('reply.type', translator('wechat_reply.list.reply_type'))->type('mapping')->map([
                    'text' => translator('wechat_reply.list.reply_type_text'),
                    'image' => translator('wechat_reply.list.reply_type_image'),
                    'news' => translator('wechat_reply.list.reply_type_news'),
                    'voice' => translator('wechat_reply.list.reply_type_voice'),
                    'video' => translator('wechat_reply.list.reply_type_video')
                ]),
                amis()->Switch('reply.status', translator('wechat_reply.list.status'))->type('mapping')->map([
                    0 => '<span class="label label-danger">' . translator('wechat_reply.list.status_disabled') . '</span>',
                    1 => '<span class="label label-success">' . translator('wechat_reply.list.status_enabled') . '</span>'
                ]),
                amis()->TableColumn('created_at', translator('wechat_reply.list.created_at'))->sortable(),
                $this->rowActions([
                    $this->rowEditButton(false),
                    $this->rowDeleteButton(false),
                ]),
            ]);

        return $this->baseList($crud);
    }

    /**
     * 关键词回复表单页面
     *
     * @param bool $isEdit 是否为编辑模式
     * @return Form
     */
    public function form(bool $isEdit = false): Form
    {
        return $this->baseForm()->body([
            amis()->InputText('keys', translator('wechat_reply.form.keyword'))
                ->required()
                ->description(translator('wechat_reply.form.keyword_description')),
            amis()->Select('reply.type', translator('wechat_reply.form.reply_type'))
                ->options([
                    ['label' => translator('wechat_reply.list.reply_type_text'), 'value' => 'text'],
                    ['label' => translator('wechat_reply.list.reply_type_image'), 'value' => 'image'],
                    ['label' => translator('wechat_reply.list.reply_type_news'), 'value' => 'news'],
                    ['label' => translator('wechat_reply.list.reply_type_voice'), 'value' => 'voice'],
                    ['label' => translator('wechat_reply.list.reply_type_video'), 'value' => 'video']
                ])
                ->value('text')
                ->required(),
            amis()->InputText('reply.content', translator('wechat_reply.form.reply_content'))
                ->visibleOn('reply.type === "text"')
                ->required()
                ->description(translator('wechat_reply.form.reply_content_description')),
            amis()->InputText('reply.media_id', translator('wechat_reply.form.media_id'))
                ->visibleOn('reply.type !== "text"')
                ->description(translator('wechat_reply.form.media_id_description')),
            amis()->Switch('reply.status', translator('wechat_reply.form.status'))
                ->trueValue(1)
                ->falseValue(0)
                ->value(1)
                ->required(),
        ]);
    }

    /**
     * 关键词回复详情页面
     *
     * @return Form
     */
    public function detail(): Form
    {
        return $this->baseDetail()->body([]);
    }

    public function reply(Request $request, string $key): Response
    {
        $page = match ($key) {
            'subscribe' => $this->subscribe(),
            'default' => $this->default(),
        };
        return $this->response()->success($page);
    }

    /**
     * 微信设置页面（包含关注回复和默认回复）
     *
     */
    public function subscribe(): Page
    {
        $form = $this->baseForm(false)
            // 提交保存到 save_reply
            ->api(['method' => 'post', 'url' => admin_url('/app/wechat/save_reply?key=subscribe')])
            // 初始化时从 get_reply 读取当前配置
            ->initApi(['method' => 'get', 'url' => admin_url('/app/wechat/get_reply?key=subscribe')])
            ->body([
                amis()->Select('reply.type', translator('wechat_reply.subscribe.reply_type'))
                    ->options([
                        ['label' => translator('wechat_reply.list.reply_type_text'), 'value' => 'text'],
                        ['label' => translator('wechat_reply.list.reply_type_image'), 'value' => 'image'],
                        ['label' => translator('wechat_reply.list.reply_type_news'), 'value' => 'news'],
                        ['label' => translator('wechat_reply.list.reply_type_voice'), 'value' => 'voice'],
                        ['label' => translator('wechat_reply.list.reply_type_video'), 'value' => 'video']
                    ])
                    ->value('text')
                    ->required(),
                amis()->InputText('reply.content', translator('wechat_reply.subscribe.reply_content'))
                    ->visibleOn('reply.type === "text"')
                    ->required()
                    ->description(translator('wechat_reply.subscribe.reply_content_description')),
                amis()->InputText('reply.media_id', translator('wechat_reply.subscribe.media_id'))
                    ->visibleOn('reply.type !== "text"')
                    ->description(translator('wechat_reply.subscribe.media_id_description')),
            ]);

        return $this->basePage()->title(translator('wechat_reply.subscribe.title'))->body([
            $form
        ]);
    }

    public function default(): Page
    {
        $form = $this->baseForm(false)
            // 提交保存到 save_reply
            ->api(['method' => 'post', 'url' => admin_url('/app/wechat/save_reply?key=default')])
            // 初始化时从 get_reply 读取当前配置
            ->initApi(['method' => 'get', 'url' => admin_url('/app/wechat/get_reply?key=default')])
            ->body([
                amis()->Select('reply.type', translator('wechat_reply.default.reply_type'))
                    ->options([
                        ['label' => translator('wechat_reply.list.reply_type_text'), 'value' => 'text'],
                        ['label' => translator('wechat_reply.list.reply_type_image'), 'value' => 'image'],
                        ['label' => translator('wechat_reply.list.reply_type_news'), 'value' => 'news'],
                        ['label' => translator('wechat_reply.list.reply_type_voice'), 'value' => 'voice'],
                        ['label' => translator('wechat_reply.list.reply_type_video'), 'value' => 'video']
                    ])
                    ->value('text')
                    ->required(),
                amis()->InputText('reply.content', translator('wechat_reply.default.reply_content'))
                    ->visibleOn('reply.type === "text"')
                    ->required()
                    ->description(translator('wechat_reply.default.reply_content_description')),
                amis()->InputText('reply.media_id', translator('wechat_reply.default.media_id'))
                    ->visibleOn('reply.type !== "text"')
                    ->description(translator('wechat_reply.default.media_id_description')),
            ]);

        return $this->basePage()->title(translator('wechat_reply.default.title'))->body($form);
    }

    public function getReply(Request $request): Response
    {
        $page = match ($request->get('key')) {
            'subscribe' => $this->subscribeReply(),
            'default' => $this->defaultReply(),
        };
        return $this->response()->success($page);
    }

    public function saveReply(Request $request): Response
    {
        return match ($request->get('key')) {
            'subscribe' => $this->saveSubscribeReply(),
            'default' => $this->saveDefaultReply(),
        };
    }

    /**
     * 获取关注回复
     *
     * @return array
     */
    public function subscribeReply(): array
    {
        $result = $this->service->getSubscribeReply();

        // 处理返回数据格式
        if ($result && isset($result['reply'])) {
            $reply = $result['reply'];
            $data = [
                'reply' => [
                    'type' => $reply['type'],
                ]
            ];

            // 根据回复类型处理内容
            if ($reply['type'] === 'text') {
                $data['reply']['content'] = $reply['data']['content'] ?? '';
            } else {
                $data['reply']['media_id'] = $reply['data']['media_id'] ?? '';
            }

            return $data;
        }

        return [
            'reply' => [
                'type' => 'text'
            ]
        ];
    }

    /**
     * 保存关注回复
     *
     * @return Response
     */
    public function saveSubscribeReply(): Response
    {
        $reply = input('reply');

        try {
            $result = $this->service->setSubscribeReply($reply);

            if ($result) {
                return $this->response()->successMessage(translator('wechat_reply.messages.subscribe_save_success'));
            } else {
                return $this->response()->fail(translator('wechat_reply.messages.subscribe_save_failed'));
            }
        } catch (\Exception $e) {
            return $this->response()->fail($e->getMessage());
        }
    }

    /**
     * 获取默认回复
     *
     * @return array
     */
    public function defaultReply(): array
    {
        $result = $this->service->getDefaultReply();

        // 处理返回数据格式
        if ($result && isset($result['reply'])) {
            $reply = $result['reply'];
            $data = [
                'reply' => [
                    'type' => $reply['type'],
                ]
            ];

            // 根据回复类型处理内容
            if ($reply['type'] === 'text') {
                $data['reply']['content'] = $reply['data']['content'] ?? '';
            } else {
                $data['reply']['media_id'] = $reply['data']['media_id'] ?? '';
            }

            return $data;
        }

        return [
            'reply' => [
                'type' => 'text'
            ]
        ];
    }

    /**
     * 保存默认回复
     *
     * @return Response
     */
    public function saveDefaultReply(): Response
    {
        $reply = input('reply');

        try {
            $result = $this->service->setDefaultReply($reply);

            if ($result) {
                return $this->response()->successMessage(translator('wechat_reply.messages.default_save_success'));
            } else {
                return $this->response()->fail(translator('wechat_reply.messages.default_save_failed'));
            }
        } catch (\Exception $e) {
            return $this->response()->fail($e->getMessage());
        }
    }
}