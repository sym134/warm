<?php

namespace warm\admin\controller\notice;

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
                        'title' => '基础配置',
                        'body' => $this->baseForm()->data($this->service->getSmsConfig())
                            ->api('put:' . admin_url('/notice/sms-config/save'))
                            ->body($this->baseConfigForm())
                    ],
                    [
                        'title' => '网关配置',
                        'body' => $this->gatewaysConfigForm()
                    ],
                ])
            ])
        ]);
    }

    /**
     * 基础配置表单
     *
     * @return array
     */
    private function baseConfigForm(): array
    {
        return [
            amis()->Group()->body([
                amis()->InputNumber('timeout', '请求超时时间(秒)')->value(5.0),
                amis()->InputNumber('connect_timeout', '连接超时时间(秒)')->value(5.0),
            ]),
            amis()->Select('default.strategy', '网关调用策略')
                ->options([
                    ['label' => '顺序调用', 'value' => \Overtrue\EasySms\Strategies\OrderStrategy::class],
                    ['label' => '随机调用', 'value' => \Overtrue\EasySms\Strategies\RandomStrategy::class],
                ])
                ->value(\Overtrue\EasySms\Strategies\OrderStrategy::class),
            amis()->Select('default.gateways', '默认网关')
                ->type('select')
                ->multiple()
                ->options([
                    ['label' => '阿里云', 'value' => 'aliyun'],
                    ['label' => '腾讯云', 'value' => 'qcloud'],
                    ['label' => '云片', 'value' => 'yunpian'],
                    ['label' => '短信宝', 'value' => 'smsbao'],
                    ['label' => '错误日志', 'value' => 'errorlog'],
                    ['label' => 'Submail', 'value' => 'submail'],
                    ['label' => '螺丝帽', 'value' => 'luosimao'],
                    ['label' => '容联云通讯', 'value' => 'yuntongxun'],
                    ['label' => '互亿无线', 'value' => 'huyi'],
                    ['label' => '聚合数据', 'value' => 'juhe'],
                    ['label' => '百度云', 'value' => 'baidu'],
                    ['label' => '华信短信平台', 'value' => 'huaxin'],
                    ['label' => '253云通讯(创蓝)', 'value' => 'chuanglan'],
                    ['label' => '融云', 'value' => 'rongcloud'],
                    ['label' => '天毅无线', 'value' => 'tianyiwuxian'],
                    ['label' => '华为云', 'value' => 'huawei'],
                    ['label' => '网易云信', 'value' => 'yunxin'],
                    ['label' => '京东云', 'value' => 'jdcloud'],
                    ['label' => 'Ucloud', 'value' => 'ucloud'],
                    ['label' => '七牛云', 'value' => 'qiniu'],
                    ['label' => 'SendCloud', 'value' => 'sendcloud'],
                    ['label' => '时代互联', 'value' => 'nowcn'],
                    ['label' => '火山引擎', 'value' => 'volcengine'],
                    ['label' => '移动云MAS(黑名单模式)', 'value' => 'yidongmasblack'],
                    ['label' => '电信天翼云', 'value' => 'ctyun'],
                    ['label' => '微趣云', 'value' => 'weiqucloud'],
                ])
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
        return [
            amis()->Alert()->body('点击对应网关的"配置"按钮进行详细配置')->level('info'),
            amis()->List()->listItem([
                'body' => [
                    amis()->hbox()->columns([
                        amis()->TableColumn('name'),
                        amis()->TableColumn('description'),
                    ])
                ],
                'actions' => [
                    amis()->Button()->type('button')->label('配置')->actionType('dialog')->dialog([
                        'title' => '${name} 网关配置',
                        'size' => 'lg',
                        'body' => [
                            amis()->Service()->schemaApi('get:' . admin_url('setting/other_config/sms/gateway_form?gateway=${gateway_key}'))
                        ]
                    ])
                ]
            ])->data([
                ['name' => '阿里云', 'description' => '阿里云短信服务', 'gateway_key' => 'aliyun'],
                ['name' => '腾讯云', 'description' => '腾讯云短信服务', 'gateway_key' => 'qcloud'],
                ['name' => '云片', 'description' => '云片短信服务', 'gateway_key' => 'yunpian'],
                ['name' => '短信宝', 'description' => '短信宝服务', 'gateway_key' => 'smsbao'],
                ['name' => '错误日志', 'description' => '错误日志记录', 'gateway_key' => 'errorlog'],
                ['name' => 'Submail', 'description' => 'Submail短信服务', 'gateway_key' => 'submail'],
                ['name' => '螺丝帽', 'description' => '螺丝帽短信服务', 'gateway_key' => 'luosimao'],
                ['name' => '容联云通讯', 'description' => '容联云通讯', 'gateway_key' => 'yuntongxun'],
                ['name' => '互亿无线', 'description' => '互亿无线', 'gateway_key' => 'huyi'],
                ['name' => '聚合数据', 'description' => '聚合数据', 'gateway_key' => 'juhe'],
                ['name' => '百度云', 'description' => '百度云短信服务', 'gateway_key' => 'baidu'],
                ['name' => '华信短信平台', 'description' => '华信短信平台', 'gateway_key' => 'huaxin'],
                ['name' => '253云通讯(创蓝)', 'description' => '253云通讯(创蓝)', 'gateway_key' => 'chuanglan'],
                ['name' => '融云', 'description' => '融云短信服务', 'gateway_key' => 'rongcloud'],
                ['name' => '天毅无线', 'description' => '天毅无线', 'gateway_key' => 'tianyiwuxian'],
                ['name' => '华为云', 'description' => '华为云短信服务', 'gateway_key' => 'huawei'],
                ['name' => '网易云信', 'description' => '网易云信短信服务', 'gateway_key' => 'yunxin'],
                ['name' => '京东云', 'description' => '京东云短信服务', 'gateway_key' => 'jdcloud'],
                ['name' => 'Ucloud', 'description' => 'Ucloud短信服务', 'gateway_key' => 'ucloud'],
                ['name' => '七牛云', 'description' => '七牛云短信服务', 'gateway_key' => 'qiniu'],
                ['name' => 'SendCloud', 'description' => 'SendCloud短信服务', 'gateway_key' => 'sendcloud'],
                ['name' => '时代互联', 'description' => '时代互联短信服务', 'gateway_key' => 'nowcn'],
                ['name' => '火山引擎', 'description' => '火山引擎短信服务', 'gateway_key' => 'volcengine'],
                ['name' => '移动云MAS(黑名单模式)', 'description' => '移动云MAS(黑名单模式)', 'gateway_key' => 'yidongmasblack'],
                ['name' => '电信天翼云', 'description' => '电信天翼云短信服务', 'gateway_key' => 'ctyun'],
                ['name' => '微趣云', 'description' => '微趣云短信服务', 'gateway_key' => 'weiqucloud'],
            ])
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
            return $this->response()->successMessage('保存成功');
        }

        return $this->response()->fail('保存失败');
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
            amis()->InputText('gateways.aliyun.sign_name', '签名')->required(),
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
            amis()->InputText('gateways.qcloud.sign_name', '签名')->required(),
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
            amis()->InputText('gateways.smsbao.user', '用户名')->required(),
            amis()->InputText('gateways.smsbao.password', '密码')->required(),
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
            amis()->InputText('gateways.errorlog.file', '日志文件路径')->value('/tmp/easy-sms.log')->required(),
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
            amis()->Select('gateways.submail.project', '项目')
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
            amis()->InputText('gateways.yuntongxun.app_id', '应用 ID')->required(),
            amis()->InputText('gateways.yuntongxun.account_sid', 'Account SID')->required(),
            amis()->InputText('gateways.yuntongxun.account_token', 'Account Token')->required(),
            amis()->InputText('gateways.yuntongxun.is_sub_account', '是否子帐号'),
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
            amis()->InputText('gateways.huaxin.user_id', '用户ID')->required(),
            amis()->InputText('gateways.huaxin.password', '密码')->required(),
            amis()->InputText('gateways.huaxin.account', '账号'),
            amis()->InputText('gateways.huaxin.ip', 'IP地址'),
            amis()->InputText('gateways.huaxin.ext_no', '扩展号'),
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
            amis()->InputText('gateways.chuanglan.account', '账号')->required(),
            amis()->InputText('gateways.chuanglan.password', '密码')->required(),
            amis()->Select('gateways.chuanglan.is_need_status', '是否需要状态报告')
                ->options([
                    ['label' => '需要', 'value' => 'true'],
                    ['label' => '不需要', 'value' => 'false'],
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
            amis()->InputText('gateways.tianyiwuxian.username', '用户名')->required(),
            amis()->InputText('gateways.tianyiwuxian.password', '密码')->required(),
            amis()->InputText('gateways.tianyiwuxian.sign_name', '签名')->required(),
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
            amis()->InputText('gateways.huawei.sign_name', '签名')->required(),
            amis()->InputText('gateways.huawei.sender', '发送者')->required(),
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
            amis()->InputText('gateways.yunxin.code_length', '验证码长度'),
            amis()->InputText('gateways.yunxin.need_up', '是否需要上行'),
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
            amis()->InputText('gateways.jdcloud.region', '区域')->required(),
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
            amis()->InputText('gateways.nowcn.key', '用户ID')->required(),
            amis()->InputText('gateways.nowcn.secret', '开发密钥')->required(),
            amis()->InputText('gateways.nowcn.api_type', '短信通道')->required(),
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
            amis()->InputText('gateways.volcengine.region_id', '区域ID'),
            amis()->InputText('gateways.volcengine.sign_name', '签名'),
            amis()->InputText('gateways.volcengine.sms_account', '消息组账号'),
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
            amis()->InputText('gateways.yidongmasblack.ecName', '机构名称')->required(),
            amis()->InputText('gateways.yidongmasblack.secretKey', '密钥')->required(),
            amis()->InputText('gateways.yidongmasblack.apId', '应用ID')->required(),
            amis()->InputText('gateways.yidongmasblack.sign', '签名')->required(),
            amis()->InputText('gateways.yidongmasblack.addSerial', '通道号'),
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
            amis()->InputText('gateways.ctyun.sign', '签名')->required(),
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
            return $this->response()->successMessage('保存成功');
        }

        return $this->response()->fail('保存失败');
    }
}