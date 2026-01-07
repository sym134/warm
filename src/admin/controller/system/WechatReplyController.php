<?php

namespace warm\admin\controller\system;

use support\Request;
use support\Response;
use warm\admin\controller\AdminController;
use warm\admin\renderer\Form;
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
                    amis()->InputText('keys', '关键词')->clearable(true),
                    amis()->Select('reply.status', '状态')->options([
                        0 => '禁用',
                        1 => '启用'
                    ])->clearable(true),
                ])
            )
            ->columns([
                amis()->TableColumn('id', 'ID')->sortable(),
                amis()->TableColumn('keys', '关键词'),
                amis()->TableColumn('reply.type', '回复类型')->type('mapping')->map([
                    'text' => '文本',
                    'image' => '图片',
                    'news' => '图文',
                    'voice' => '语音',
                    'video' => '视频'
                ]),
                amis()->TableColumn('reply.status', '状态')->type('mapping')->map([
                    0 => '<span class="label label-danger">禁用</span>',
                    1 => '<span class="label label-success">启用</span>'
                ]),
                amis()->TableColumn('created_at', '创建时间')->sortable(),
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
            amis()->InputText('keys', '关键词')
                ->required()
                ->description('用户发送的关键词，支持精确匹配'),
            amis()->Select('reply.type', '回复类型')
                ->options([
                    ['label' => '文本', 'value' => 'text'],
                    ['label' => '图片', 'value' => 'image'],
                    ['label' => '图文', 'value' => 'news'],
                    ['label' => '语音', 'value' => 'voice'],
                    ['label' => '视频', 'value' => 'video']
                ])
                ->value('text')
                ->required(),
            amis()->InputText('reply.content', '回复内容')
                ->visibleOn('reply.type === "text"')
                ->required()
                ->description('请输入要回复的文本内容'),
            amis()->InputText('reply.media_id', '媒体ID')
                ->visibleOn('reply.type !== "text"')
                ->description('请输入微信素材的media_id'),
            amis()->Switch('reply.status', '状态')
                ->trueValue(1)
                ->falseValue(0)
                ->value(1)
                ->required(),
            amis()->Switch('reply.hide', '是否隐藏')
                ->trueValue(1)
                ->falseValue(0)
                ->value(0),
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
            ->api(['method' => 'get', 'url' => admin_url('/app/wechat/get_reply?key=subscribe')])
            ->body([
                amis()->Select('reply.type', '回复类型')
                    ->options([
                        ['label' => '文本', 'value' => 'text'],
                        ['label' => '图片', 'value' => 'image'],
                        ['label' => '图文', 'value' => 'news'],
                        ['label' => '语音', 'value' => 'voice'],
                        ['label' => '视频', 'value' => 'video']
                    ])
                    ->value('text')
                    ->required(),
                amis()->InputText('reply.content', '回复内容')
                    ->visibleOn('reply.type === "text"')
                    ->required()
                    ->description('请输入要回复的文本内容'),
                amis()->InputText('reply.media_id', '媒体ID')
                    ->visibleOn('reply.type !== "text"')
                    ->description('请输入微信素材的media_id'),
                amis()->ButtonToolbar()->buttons([
                    amis()->Button()
                        ->label('保存')
                        ->level('primary')
                        ->type('submit')
                        ->api(['method' => 'post', 'url' => admin_url('/app/wechat/save_reply?key=subscribe')])
                ])
            ]);

        return $this->basePage()->title('关注回复')->body([
            $form
        ]);
    }

    public function default(): Page
    {
        $form = $this->baseForm(false)
            ->api(['method' => 'get', 'url' => admin_url('/app/wechat/get_reply?key=default')])
            ->body([
                amis()->Select('reply.type', '回复类型')
                    ->options([
                        ['label' => '文本', 'value' => 'text'],
                        ['label' => '图片', 'value' => 'image'],
                        ['label' => '图文', 'value' => 'news'],
                        ['label' => '语音', 'value' => 'voice'],
                        ['label' => '视频', 'value' => 'video']
                    ])
                    ->value('text')
                    ->required(),
                amis()->InputText('reply.content', '回复内容')
                    ->visibleOn('reply.type === "text"')
                    ->required()
                    ->description('请输入要回复的文本内容'),
                amis()->InputText('reply.media_id', '媒体ID')
                    ->visibleOn('reply.type !== "text"')
                    ->description('请输入微信素材的media_id'),
                amis()->ButtonToolbar()->buttons([
                    amis()->Button()
                        ->label('保存')
                        ->level('primary')
                        ->type('submit')
                        ->api(['method' => 'post', 'url' => admin_url('/app/wechat/save_reply?key=default')])
                ])
            ]);

        return $this->basePage()->title('默认回复')->body($form);
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
                return $this->response()->successMessage('关注回复设置保存成功');
            } else {
                return $this->response()->fail('关注回复设置保存失败');
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
                return $this->response()->successMessage('默认回复设置保存成功');
            } else {
                return $this->response()->fail('默认回复设置保存失败');
            }
        } catch (\Exception $e) {
            return $this->response()->fail($e->getMessage());
        }
    }
}