<?php

namespace warm\admin\controller\notice;

use support\Request;
use support\Response;
use warm\admin\controller\AdminController;
use warm\admin\renderer\Form;
use warm\admin\renderer\Page;
use warm\admin\renderer\TableColumn;
use warm\admin\service\notice\NoticeConfigService;
use warm\common\service\notice\Notice;

/**
 * 通知配置控制器
 * 
 * 管理各种通知渠道的配置
 */
class NoticeConfigController extends AdminController
{
    protected string $serviceName = NoticeConfigService::class;
    
    /**
     * 首页
     * 
     * @return Response
     */
    public function index(): Response
    {
        if ($this->actionOfGetData()) {
            return $this->response()->success($this->service->list());
        }
        
        return $this->response()->success($this->list());
    }
    
    /**
     * 列表页面
     * 
     * @return Page
     */
    public function list(): Page
    {
        $crud = $this->baseCRUD()
            ->headerToolbar([])
            ->columns([
                TableColumn::make()->name('name')->label('通知渠道'),
                TableColumn::make()->name('description')->label('描述'),
                TableColumn::make()->name('status')->label('状态')->type('mapping')->map([
                    0 => '<span class="label label-danger">禁用</span>',
                    1 => '<span class="label label-success">启用</span>',
                ]),
                $this->rowActions([
                    $this->rowEditButton(true, 'md','配置'),
                ]),
            ]);

        return $this->baseList($crud);
    }
    
    /**
     * 配置表单
     * 
     * @param bool $isEdit 是否为编辑模式
     * @return Form
     */
    public function form(bool $isEdit = false): Form
    {
        $form = $this->baseForm();
        
        if ($isEdit) {
            $id = request()->get('id');
            $form->data($this->service->getEditData($id));
        }
        
        return $form->body([
            amis()->HiddenControl('id')->required(),
            amis()->StaticExactControl('description')->label('渠道描述'),
            amis()->Wrapper()->visibleOn("this.id === 'sms'")->body([
                $this->smsConfigForm(),
            ]),
            amis()->Wrapper()->visibleOn("this.id === 'wechat_official_account'")->body([
                $this->wechatOfficialAccountConfigForm(),
            ]),
            amis()->Wrapper()->visibleOn("this.id === 'wechat_mini_program'")->body([
                $this->wechatMiniProgramConfigForm(),
            ]),
            amis()->Wrapper()->visibleOn("this.id === 'email'")->body([
                $this->emailConfigForm(),
            ]),
        ]);
    }
    
    /**
     * 短信配置表单（简化版）
     * 
     * @return array
     */
    private function smsConfigForm(): array
    {
        return [
            amis()->Alert()->body('短信配置已迁移到独立页面，请前往<a href="' . admin_url('notice/sms-config') . '">短信配置</a>进行详细配置')->level('info'),
            amis()->GroupControl()->body([
                amis()->NumberControl('timeout', '请求超时时间(秒)')->value(5.0),
                amis()->NumberControl('connect_timeout', '连接超时时间(秒)')->value(5.0),
            ]),
            
            amis()->GroupControl()->body([
                amis()->SelectControl('default.strategy', '网关调用策略')
                    ->options([
                        ['label' => '顺序调用', 'value' => \Overtrue\EasySms\Strategies\OrderStrategy::class],
                        ['label' => '随机调用', 'value' => \Overtrue\EasySms\Strategies\RandomStrategy::class],
                    ]),
                amis()->SelectControl('default.gateways', '默认网关')
                    ->type('select')
                    ->multiple(true)
                    ->options([
                        ['label' => '阿里云', 'value' => 'aliyun'],
                        ['label' => '腾讯云', 'value' => 'qcloud'],
                        ['label' => '短信宝', 'value' => 'smsbao'],
                        ['label' => '云片', 'value' => 'yunpian'],
                        ['label' => '错误日志', 'value' => 'errorlog'],
                    ])
                    ->searchable(true)
                    ->required(),
            ]),
            
            // 阿里云配置
            amis()->GroupControl()->label('阿里云配置')->body([
                amis()->TextControl('gateways.aliyun.access_key_id', 'AccessKeyId'),
                amis()->TextControl('gateways.aliyun.access_key_secret', 'AccessKeySecret'),
                amis()->TextControl('gateways.aliyun.sign_name', '签名'),
            ])->visibleOn("data.default.gateways && data.default.gateways.includes('aliyun')"),
            
            // 腾讯云配置
            amis()->GroupControl()->label('腾讯云配置')->body([
                amis()->TextControl('gateways.qcloud.sdk_app_id', 'SDK AppID'),
                amis()->TextControl('gateways.qcloud.secret_id', 'SecretId'),
                amis()->TextControl('gateways.qcloud.secret_key', 'SecretKey'),
                amis()->TextControl('gateways.qcloud.sign_name', '签名'),
            ])->visibleOn("data.default.gateways && data.default.gateways.includes('qcloud')"),
            
            // 短信宝配置
            amis()->GroupControl()->label('短信宝配置')->body([
                amis()->TextControl('gateways.smsbao.user', '用户名'),
                amis()->TextControl('gateways.smsbao.password', '密码'),
            ])->visibleOn("data.default.gateways && data.default.gateways.includes('smsbao')"),
            
            // 云片配置
            amis()->GroupControl()->label('云片配置')->body([
                amis()->TextControl('gateways.yunpian.api_key', 'API Key'),
            ])->visibleOn("data.default.gateways && data.default.gateways.includes('yunpian')"),
            
            // 错误日志配置
            amis()->GroupControl()->label('错误日志配置')->body([
                amis()->TextControl('gateways.errorlog.file', '日志文件路径')->value('/tmp/easy-sms.log'),
            ])->visibleOn("data.default.gateways && data.default.gateways.includes('errorlog')"),
        ];
    }
    
    /**
     * 微信公众号配置表单
     * 
     * @return array
     */
    private function wechatOfficialAccountConfigForm(): array
    {
        return [
            amis()->TextControl('app_id', 'AppID')->required(),
            amis()->TextControl('app_secret', 'AppSecret')->required(),
            amis()->TextControl('token', 'Token'),
            amis()->TextControl('aes_key', 'AES Key'),
            amis()->SwitchControl('enable', '启用'),
        ];
    }
    
    /**
     * 微信小程序配置表单
     * 
     * @return array
     */
    private function wechatMiniProgramConfigForm(): array
    {
        return [
            amis()->TextControl('app_id', 'AppID')->required(),
            amis()->TextControl('app_secret', 'AppSecret')->required(),
            amis()->SwitchControl('enable', '启用'),
        ];
    }
    
    /**
     * 邮件配置表单
     * 
     * @return array
     */
    private function emailConfigForm(): array
    {
        return [
            amis()->TextControl('smtp_host', 'SMTP服务器')->required(),
            amis()->TextControl('smtp_username', 'SMTP用户名')->required(),
            amis()->TextControl('smtp_password', 'SMTP密码')->required(),
            amis()->NumberControl('smtp_port', 'SMTP端口')->value(465)->required(),
            amis()->SelectControl('smtp_secure', '加密方式')
                ->options([
                    ['label' => 'SSL', 'value' => 'ssl'],
                    ['label' => 'TLS', 'value' => 'tls'],
                ])
                ->value('ssl'),
            amis()->TextControl('from_email', '发件人邮箱'),
            amis()->TextControl('from_name', '发件人名称'),
            amis()->SwitchControl('enable', '启用'),
        ];
    }
    
    /**
     * 保存配置
     * 
     * @param Request $request
     * @return Response
     */
    public function save(Request $request): Response
    {
        $data = $request->post();
        $channel = $data['id'] ?? '';
        
        if (empty($channel)) {
            return $this->response()->fail('参数错误');
        }
        
        unset($data['id']);
        
        if ($this->service->saveConfig($channel, $data)) {
            return $this->response()->successMessage('保存成功');
        }
        
        return $this->response()->fail('保存失败');
    }
    
    /**
     * 场景配置
     * 
     * @return Response
     */
    public function scene(): Response
    {
        if ($this->actionOfGetData()) {
            return $this->response()->success($this->service->getSceneConfig());
        }
        
        if ($this->actionOfFormSubmit()) {
            $data = request()->post();
            if ($this->service->saveSceneChannelMapping($data)) {
                return $this->response()->successMessage('保存成功');
            }
            return $this->response()->fail('保存失败');
        }
        
        return $this->response()->success($this->sceneForm());
    }
    
    /**
     * 场景配置表单
     * 
     * @return Form
     */
    private function sceneForm(): Form
    {
        $notice = new Notice();
        $scenes = $notice->getScenes();
        $channels = $notice->getChannels();
        
        $channelOptions = [];
        foreach ($channels as $name => $description) {
            $channelOptions[] = ['label' => $description, 'value' => $name];
        }
        
        $formItems = [];
        foreach ($scenes as $scene => $description) {
            $formItems[] = amis()->CheckboxesControl($scene, $description)
                ->options($channelOptions);
        }
        
        return amis()->Form()
            ->title('通知场景配置')
            ->api('post:' . admin_url('notice/config/scene'))
            ->body($formItems);
    }

    public function update(Request $request, mixed $id): Response
    {
        // 获取主键值（优先从请求参数获取，其次使用路由参数）
        $primaryKey = $this->getPrimaryValue($request) ?: $id;

        // 执行更新操作
        $result = $this->service->saveConfig($primaryKey, $request->all());

        // 返回自动响应结果
        return $this->autoResponse($result, translator('admin.save'));
    }
}