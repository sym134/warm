<?php

namespace warm\admin\controller\system;

use support\Request;
use support\Response;
use warm\admin\controller\AdminController;
use warm\admin\renderer\Page;
use warm\admin\service\config\ConfigService;

/**
 * 短信配置控制器
 *
 * 管理短信通知渠道的配置，使用Tabs选项卡分隔基础配置和不同网关配置
 */
class SmsConfigController extends AdminController
{
    protected string $serviceName = ConfigService::class;

    /**
     * 短信配置页面
     *
     * @return Response
     */
    public function index(): Response
    {
        if ($this->actionOfGetData()) {
            $data = $this->service->getSmsConfig();
            return $this->response()->success($data);
        }

        return $this->response()->success($this->form());
    }

    /**
     * 短信配置表单
     * @return Page
     */
    public function form(): Page
    {
        // 使用Tabs组织配置项
        return $this->basePage()->body([
            amis()->Card()->body([
                amis()->Tabs()->tabs([
                    [
                        'title' => translator('sms.tab_basic'),
                        'body' => $this->baseForm()->data($this->service->getSmsConfig())
                            ->api('put:' . admin_url('/notice/sms-config/save'))
                            ->body($this->baseConfigForm())
                    ],
                    [
                        'title' => translator('sms.tab_gateways'),
                        'body' => $this->gatewaysConfigForm()
                    ],
                ])
            ])
        ]);
    }

    /** @var string[] */
    private const GATEWAY_KEYS = [
        'aliyun', 'qcloud', 'yunpian', 'smsbao', 'errorlog', 'submail', 'luosimao', 'yuntongxun',
        'huyi', 'juhe', 'baidu', 'huaxin', 'chuanglan', 'rongcloud', 'tianyiwuxian', 'huawei',
        'yunxin', 'jdcloud', 'ucloud', 'qiniu', 'sendcloud', 'nowcn', 'volcengine', 'yidongmasblack',
        'ctyun', 'weiqucloud',
    ];

    /**
     * 通过 translator 获取网关列表（每项 name/description 为独立键，避免 translator 取数组）
     *
     * @return array<array{gateway_key: string, name: string, description: string}>
     */
    private function getSmsGateways(): array
    {
        $list = [];
        foreach (self::GATEWAY_KEYS as $key) {
            $list[] = [
                'gateway_key' => $key,
                'name' => (string) (translator('sms.gateways_' . $key . '_name') ?? $key),
                'description' => (string) (translator('sms.gateways_' . $key . '_description') ?? ''),
            ];
        }
        return $list;
    }

    /**
     * 基础配置表单
     *
     * @return array
     */
    private function baseConfigForm(): array
    {
        $gateways = $this->getSmsGateways();
        $gatewayOptions = array_map(fn($g) => ['label' => $g['name'] ?? '', 'value' => $g['gateway_key'] ?? ''], $gateways);

        return [
            amis()->Group()->body([
                amis()->InputNumber('timeout', translator('sms.timeout'))->value(5.0),
                amis()->InputNumber('connect_timeout', translator('sms.connect_timeout'))->value(5.0),
            ]),
            amis()->Select('default.strategy', translator('sms.strategy'))
                ->options([
                    ['label' => translator('sms.strategy_order'), 'value' => \Overtrue\EasySms\Strategies\OrderStrategy::class],
                    ['label' => translator('sms.strategy_random'), 'value' => \Overtrue\EasySms\Strategies\RandomStrategy::class],
                ])
                ->value(\Overtrue\EasySms\Strategies\OrderStrategy::class),
            amis()->Select('default.gateways', translator('sms.default_gateways'))
                ->type('select')
                ->multiple()
                ->options($gatewayOptions)
                ->searchable(true),
        ];
    }

    /**
     * 网关配置表单
     *
     * @return array
     */
    private function gatewaysConfigForm(): array
    {
        $data = $this->getSmsGateways();

        return [
            amis()->Alert()->body(translator('sms.gateway_config_hint'))->level('info'),
            amis()->List()->listItem([
                'body' => [
                    amis()->hbox()->columns([
                        amis()->TableColumn('name'),
                        amis()->TableColumn('description'),
                    ])
                ],
                'actions' => [
                    amis()->Button()->type('button')->label(translator('sms.config_btn'))->actionType('dialog')->dialog([
                        'title' => '${name} ' . translator('sms.gateway_config_title'),
                        'size' => 'lg',
                        'body' => [
                            amis()->Service()->schemaApi('get:' . admin_url('setting/other_config/sms/gateway_form?gateway=${gateway_key}'))
                        ]
                    ])
                ]
            ])->data($data)
        ];
    }

    /**
     * 获取网关配置表单
     *
     * @param Request $request
     * @return Response
     */
    public function gatewayForm(Request $request): Response
    {
        $gateway = $request->get('gateway');

        $forms = [
            'aliyun' => $this->aliyunGatewayForm(),
            'qcloud' => $this->qcloudGatewayForm(),
            'yunpian' => $this->yunpianGatewayForm(),
            'smsbao' => $this->smsbaoGatewayForm(),
            'errorlog' => $this->errorlogGatewayForm(),
            'submail' => $this->submailGatewayForm(),
            'luosimao' => $this->luosimaoGatewayForm(),
            'yuntongxun' => $this->yuntongxunGatewayForm(),
            'huyi' => $this->huyiGatewayForm(),
            'juhe' => $this->juheGatewayForm(),
            'baidu' => $this->baiduGatewayForm(),
            'huaxin' => $this->huaxinGatewayForm(),
            'chuanglan' => $this->chuanglanGatewayForm(),
            'rongcloud' => $this->rongcloudGatewayForm(),
            'tianyiwuxian' => $this->tianyiwuxianGatewayForm(),
            'huawei' => $this->huaweiGatewayForm(),
            'yunxin' => $this->yunxinGatewayForm(),
            'jdcloud' => $this->jdcloudGatewayForm(),
            'ucloud' => $this->ucloudGatewayForm(),
            'qiniu' => $this->qiniuGatewayForm(),
            'sendcloud' => $this->sendcloudGatewayForm(),
            'nowcn' => $this->nowcnGatewayForm(),
            'volcengine' => $this->volcengineGatewayForm(),
            'yidongmasblack' => $this->yidongmasblackGatewayForm(),
            'ctyun' => $this->ctyunGatewayForm(),
            'weiqucloud' => $this->weiqucloudGatewayForm(),
        ];

        $form = amis()->Form()
            ->actions([])
            ->data($this->service->getSmsConfig())
            ->api('post:' . admin_url('notice/sms-config/save-gateway'))
            ->body([
                amis()->Hidden('gateway')->value($gateway),
                ...($forms[$gateway] ?? [])
            ]);

        return $this->response()->success($form);
    }

    /**
     * 保存网关配置
     *
     * @param Request $request
     * @return Response
     */
    public function saveGateway(Request $request): Response
    {
        $gateway = $request->post('gateway');
        $data = $request->post();

        // 移除gateway字段
        unset($data['gateway']);

        // 获取当前配置
        $config = $this->service->getSmsConfig();

        // 更新对应网关的配置
        $config['gateways'][$gateway] = array_merge($config['gateways'][$gateway] ?? [], $data['gateways'][$gateway]);

        // 保存配置
        if ($this->service->saveConfig('sms', $config)) {
            return $this->response()->successMessage(translator('sms.save_success'));
        }

        return $this->response()->fail(translator('sms.save_failed'));
    }

    /**
     * 阿里云网关配置表单
     *
     * @return array
     */
    private function aliyunGatewayForm(): array
    {
        return [
            amis()->InputText('gateways.aliyun.access_key_id', 'AccessKeyId')->required(),
            amis()->InputText('gateways.aliyun.access_key_secret', 'AccessKeySecret')->required(),
            amis()->InputText('gateways.aliyun.sign_name', translator('sms.sign_name'))->required(),
        ];
    }

    /**
     * 腾讯云网关配置表单
     *
     * @return array
     */
    private function qcloudGatewayForm(): array
    {
        return [
            amis()->InputText('gateways.qcloud.sdk_app_id', 'SDK AppID')->required(),
            amis()->InputText('gateways.qcloud.secret_id', 'SecretId')->required(),
            amis()->InputText('gateways.qcloud.secret_key', 'SecretKey')->required(),
            amis()->InputText('gateways.qcloud.sign_name', translator('sms.sign_name'))->required(),
        ];
    }

    /**
     * 云片网关配置表单
     *
     * @return array
     */
    private function yunpianGatewayForm(): array
    {
        return [
            amis()->InputText('gateways.yunpian.api_key', 'API Key')->required(),
        ];
    }

    /**
     * 短信宝网关配置表单
     *
     * @return array
     */
    private function smsbaoGatewayForm(): array
    {
        return [
            amis()->InputText('gateways.smsbao.user', translator('sms.user'))->required(),
            amis()->InputText('gateways.smsbao.password', translator('sms.password'))->required(),
        ];
    }

    /**
     * 错误日志网关配置表单
     *
     * @return array
     */
    private function errorlogGatewayForm(): array
    {
        return [
            amis()->InputText('gateways.errorlog.file', translator('sms.log_file_path'))->value('/tmp/easy-sms.log')->required(),
        ];
    }

    /**
     * Submail网关配置表单
     *
     * @return array
     */
    private function submailGatewayForm(): array
    {
        return [
            amis()->InputText('gateways.submail.app_id', 'App ID')->required(),
            amis()->InputText('gateways.submail.app_key', 'App Key')->required(),
            amis()->Select('gateways.submail.project', translator('sms.project'))
                ->options([
                    ['label' => 'SMS', 'value' => 'sms'],
                    ['label' => 'MMS', 'value' => 'mms'],
                    ['label' => 'Voice', 'value' => 'voice'],
                ]),
        ];
    }

    /**
     * 螺丝帽网关配置表单
     *
     * @return array
     */
    private function luosimaoGatewayForm(): array
    {
        return [
            amis()->InputText('gateways.luosimao.api_key', 'API Key')->required(),
        ];
    }

    /**
     * 容联云通讯网关配置表单
     *
     * @return array
     */
    private function yuntongxunGatewayForm(): array
    {
        return [
            amis()->InputText('gateways.yuntongxun.app_id', translator('sms.app_id'))->required(),
            amis()->InputText('gateways.yuntongxun.account_sid', 'Account SID')->required(),
            amis()->InputText('gateways.yuntongxun.account_token', 'Account Token')->required(),
            amis()->InputText('gateways.yuntongxun.is_sub_account', translator('sms.is_sub_account')),
        ];
    }

    /**
     * 互亿无线网关配置表单
     *
     * @return array
     */
    private function huyiGatewayForm(): array
    {
        return [
            amis()->InputText('gateways.huyi.api_id', 'API ID')->required(),
            amis()->InputText('gateways.huyi.api_key', 'API Key')->required(),
        ];
    }

    /**
     * 聚合数据网关配置表单
     *
     * @return array
     */
    private function juheGatewayForm(): array
    {
        return [
            amis()->InputText('gateways.juhe.app_key', 'App Key')->required(),
        ];
    }

    /**
     * 百度云网关配置表单
     *
     * @return array
     */
    private function baiduGatewayForm(): array
    {
        return [
            amis()->InputText('gateways.baidu.ak', 'Access Key')->required(),
            amis()->InputText('gateways.baidu.sk', 'Secret Access Key')->required(),
            amis()->InputText('gateways.baidu.invoke_id', 'Invoke ID')->required(),
            amis()->InputText('gateways.baidu.domain', 'Domain')->required(),
        ];
    }

    /**
     * 华信短信平台网关配置表单
     *
     * @return array
     */
    private function huaxinGatewayForm(): array
    {
        return [
            amis()->InputText('gateways.huaxin.user_id', translator('sms.user_id'))->required(),
            amis()->InputText('gateways.huaxin.password', translator('sms.password'))->required(),
            amis()->InputText('gateways.huaxin.account', translator('sms.account')),
            amis()->InputText('gateways.huaxin.ip', translator('sms.ip_address')),
            amis()->InputText('gateways.huaxin.ext_no', translator('sms.ext_no')),
        ];
    }

    /**
     * 253云通讯(创蓝)网关配置表单
     *
     * @return array
     */
    private function chuanglanGatewayForm(): array
    {
        return [
            amis()->InputText('gateways.chuanglan.account', translator('sms.account'))->required(),
            amis()->InputText('gateways.chuanglan.password', translator('sms.password'))->required(),
            amis()->Select('gateways.chuanglan.is_need_status', translator('sms.need_status_report'))
                ->options([
                    ['label' => translator('sms.need_status_yes'), 'value' => 'true'],
                    ['label' => translator('sms.need_status_no'), 'value' => 'false'],
                ]),
        ];
    }

    /**
     * 融云网关配置表单
     *
     * @return array
     */
    private function rongcloudGatewayForm(): array
    {
        return [
            amis()->InputText('gateways.rongcloud.app_key', 'App Key')->required(),
            amis()->InputText('gateways.rongcloud.app_secret', 'App Secret')->required(),
        ];
    }

    /**
     * 天毅无线网关配置表单
     *
     * @return array
     */
    private function tianyiwuxianGatewayForm(): array
    {
        return [
            amis()->InputText('gateways.tianyiwuxian.username', translator('sms.user'))->required(),
            amis()->InputText('gateways.tianyiwuxian.password', translator('sms.password'))->required(),
            amis()->InputText('gateways.tianyiwuxian.sign_name', translator('sms.sign_name'))->required(),
        ];
    }

    /**
     * 华为云网关配置表单
     *
     * @return array
     */
    private function huaweiGatewayForm(): array
    {
        return [
            amis()->InputText('gateways.huawei.app_key', 'App Key')->required(),
            amis()->InputText('gateways.huawei.app_secret', 'App Secret')->required(),
            amis()->InputText('gateways.huawei.url', 'URL')->required(),
            amis()->InputText('gateways.huawei.sign_name', translator('sms.sign_name'))->required(),
            amis()->InputText('gateways.huawei.sender', translator('sms.sender'))->required(),
        ];
    }

    /**
     * 网易云信网关配置表单
     *
     * @return array
     */
    private function yunxinGatewayForm(): array
    {
        return [
            amis()->InputText('gateways.yunxin.app_key', 'App Key')->required(),
            amis()->InputText('gateways.yunxin.app_secret', 'App Secret')->required(),
            amis()->InputText('gateways.yunxin.code_length', translator('sms.code_length')),
            amis()->InputText('gateways.yunxin.need_up', translator('sms.need_up')),
        ];
    }

    /**
     * 京东云网关配置表单
     *
     * @return array
     */
    private function jdcloudGatewayForm(): array
    {
        return [
            amis()->InputText('gateways.jdcloud.access_key', 'Access Key')->required(),
            amis()->InputText('gateways.jdcloud.secret_key', 'Secret Key')->required(),
            amis()->InputText('gateways.jdcloud.region', translator('sms.region'))->required(),
        ];
    }

    /**
     * Ucloud网关配置表单
     *
     * @return array
     */
    private function ucloudGatewayForm(): array
    {
        return [
            amis()->InputText('gateways.ucloud.private_key', 'Private Key')->required(),
            amis()->InputText('gateways.ucloud.public_key', 'Public Key')->required(),
            amis()->InputText('gateways.ucloud.project_id', 'Project ID')->required(),
        ];
    }

    /**
     * 七牛云网关配置表单
     *
     * @return array
     */
    private function qiniuGatewayForm(): array
    {
        return [
            amis()->InputText('gateways.qiniu.access_key', 'Access Key')->required(),
            amis()->InputText('gateways.qiniu.secret_key', 'Secret Key')->required(),
        ];
    }

    /**
     * SendCloud网关配置表单
     *
     * @return array
     */
    private function sendcloudGatewayForm(): array
    {
        return [
            amis()->InputText('gateways.sendcloud.sms_user', 'SMS User')->required(),
            amis()->InputText('gateways.sendcloud.sms_key', 'SMS Key')->required(),
        ];
    }

    /**
     * 时代互联网关配置表单
     *
     * @return array
     */
    private function nowcnGatewayForm(): array
    {
        return [
            amis()->InputText('gateways.nowcn.key', translator('sms.user_id'))->required(),
            amis()->InputText('gateways.nowcn.secret', translator('sms.dev_secret'))->required(),
            amis()->InputText('gateways.nowcn.api_type', translator('sms.sms_channel'))->required(),
        ];
    }

    /**
     * 火山引擎网关配置表单
     *
     * @return array
     */
    private function volcengineGatewayForm(): array
    {
        return [
            amis()->InputText('gateways.volcengine.access_key_id', 'Access Key ID')->required(),
            amis()->InputText('gateways.volcengine.access_key_secret', 'Access Key Secret')->required(),
            amis()->InputText('gateways.volcengine.region_id', translator('sms.region_id')),
            amis()->InputText('gateways.volcengine.sign_name', translator('sms.sign_name')),
            amis()->InputText('gateways.volcengine.sms_account', translator('sms.sms_account')),
        ];
    }

    /**
     * 移动云MAS(黑名单模式)网关配置表单
     *
     * @return array
     */
    private function yidongmasblackGatewayForm(): array
    {
        return [
            amis()->InputText('gateways.yidongmasblack.ecName', translator('sms.org_name'))->required(),
            amis()->InputText('gateways.yidongmasblack.secretKey', translator('sms.password'))->required(),
            amis()->InputText('gateways.yidongmasblack.apId', translator('sms.app_id_short'))->required(),
            amis()->InputText('gateways.yidongmasblack.sign', translator('sms.sign_name'))->required(),
            amis()->InputText('gateways.yidongmasblack.addSerial', translator('sms.channel_no')),
        ];
    }

    /**
     * 电信天翼云网关配置表单
     *
     * @return array
     */
    private function ctyunGatewayForm(): array
    {
        return [
            amis()->InputText('gateways.ctyun.access_key', 'Access Key')->required(),
            amis()->InputText('gateways.ctyun.secret_key', 'Secret Key')->required(),
            amis()->InputText('gateways.ctyun.sign', translator('sms.sign_name'))->required(),
        ];
    }

    /**
     * 微趣云网关配置表单
     *
     * @return array
     */
    private function weiqucloudGatewayForm(): array
    {
        return [
            amis()->InputText('gateways.weiqucloud.userId', '用户ID')->required(),
            amis()->InputText('gateways.weiqucloud.account', '账号')->required(),
            amis()->InputText('gateways.weiqucloud.password', '密码')->required(),
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

        if ($this->service->saveConfig('sms', $data)) {
            return $this->response()->successMessage(translator('sms.save_success'));
        }

        return $this->response()->fail(translator('sms.save_failed'));
    }
}