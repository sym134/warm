<?php

namespace warm\admin\controller\system;

use support\Request;
use support\Response;
use warm\admin\controller\AdminController;
use warm\admin\renderer\Form;
use warm\admin\renderer\Page;
use warm\admin\renderer\Tabs;
use warm\admin\renderer\Tab;
use warm\admin\service\system\PaymentConfigService;

/**
 * 支付配置控制器
 *
 * 用于管理系统支付配置，支持微信支付和支付宝配置
 */
class PaymentConfigController extends AdminController
{
    /**
     * @var string $serviceName 服务类名称
     */
    protected string $serviceName = PaymentConfigService::class;

    /**
     * 支付配置页面
     *
     * @return Page
     */
    public function list(): Page
    {
        return amis()->Page()->body(
            $this->form()
                ->api('put:' . admin_url($this->queryPath . '/update'))
                ->initApi(admin_url($this->queryPath . '?_action=getData'))
        );
    }

    /**
     * 支付配置表单
     *
     * @return Form 返回支付配置表单
     */
    public function form(): Form
    {
        return $this->baseForm(false)
            ->panelClassName('px-10 m:px-0')
            ->mode('horizontal')
            ->body([
                amis()->SwitchControl('payment.wechat_pay.enable', '启用微信支付')->value(0),
                amis()->SwitchControl('payment.alipay.enable', '启用支付宝支付')->value(0),
                amis()->Tabs()->tabsMode(true)->contentClassName('')->tabs([
                    $this->wechatPayTab(),
                    $this->alipayTab(),
                ])
            ]);
    }

    /**
     * 微信支付配置 Tab
     *
     * @return Tab
     */
    private function wechatPayTab(): Tab
    {
        return amis()->Tab()->title('微信支付')->body([
            amis()->SelectControl('payment.wechat_pay.version', '支付接口类型')
                ->options([
                    ['label' => 'V3 (支持商户转账到零钱)', 'value' => 'v3'],
                    ['label' => 'V2 (支持企业付款到零钱)', 'value' => 'v2']
                ])
                ->value('v3'),
            
            // V3 配置
            amis()->GroupControl()->label('V3参数配置')->body([
                amis()->TextControl('payment.wechat_pay.v3.mch_id', 'Mchid')
                    ->description('微信商户商户号'),
                amis()->TextControl('payment.wechat_pay.v3.serial_no', '证书序列号')
                    ->description('「商户API证书」的「证书序列号」，可以在证书管理里面查看'),
                amis()->TextControl('payment.wechat_pay.v3.private_key', 'V3支付Key')
                    ->description('V3支付秘钥'),
                amis()->TextControl('payment.wechat_pay.v3.cert_path', '微信支付证书')
                    ->description('微信支付证书，在微信商家平台中可以下载！文件名一般为apiclient_cert.pem'),
                amis()->TextControl('payment.wechat_pay.v3.key_path', '微信支付证书密钥')
                    ->description('微信支付证书密钥，在微信商家平台中可以下载！文件名一般为apiclient_key.pem'),
                amis()->SelectControl('payment.wechat_pay.v3.mini_app_mode', '小程序商户号选择')
                    ->options([
                        ['label' => '商户号绑定', 'value' => 'merchant'],
                        ['label' => '非商户号绑定', 'value' => 'non_merchant']
                    ])
                    ->description('小程序开发后台有支付管理的请选择非商户号绑定'),
                amis()->SelectControl('payment.wechat_pay.v3.merchant_type', '商户类型')
                    ->options([
                        ['label' => '普通微信商户模式', 'value' => 'normal'],
                        ['label' => '普通微信服务商模式', 'value' => 'service']
                    ])
                    ->description('商户类型，目前支持普通微信商户模式和普通微信服务商模式'),
            ])->visibleOn("data.payment.wechat_pay.version === 'v3'"),
            
            // V2 配置
            amis()->GroupControl()->label('V2参数配置')->body([
                amis()->TextControl('payment.wechat_pay.v2.key', 'Key')
                    ->description('商户支付密钥Key。审核通过后，在微信发送的邮件中查看。'),
                amis()->TextControl('payment.wechat_pay.v2.mch_id', 'Mchid')
                    ->description('微信商户商户号'),
                amis()->TextControl('payment.wechat_pay.v2.cert_path', '微信支付证书')
                    ->description('微信支付证书，在微信商家平台中可以下载！文件名一般为apiclient_cert.pem'),
                amis()->TextControl('payment.wechat_pay.v2.key_path', '微信支付证书密钥')
                    ->description('微信支付证书密钥，在微信商家平台中可以下载！文件名一般为apiclient_key.pem'),
                amis()->SelectControl('payment.wechat_pay.v2.mini_app_mode', '小程序商户号选择')
                    ->options([
                        ['label' => '商户号绑定', 'value' => 'merchant'],
                        ['label' => '非商户号绑定', 'value' => 'non_merchant']
                    ])
                    ->description('小程序开发后台有支付管理的请选择非商户号绑定'),
                amis()->SelectControl('payment.wechat_pay.v2.merchant_type', '商户类型')
                    ->options([
                        ['label' => '普通微信商户模式', 'value' => 'normal'],
                        ['label' => '普通微信服务商模式', 'value' => 'service']
                    ])
                    ->description('商户类型，目前支持普通微信商户模式和普通微信服务商模式'),
            ])->visibleOn("data.payment.wechat_pay.version === 'v2'"),
        ]);
    }

    /**
     * 支付宝配置 Tab
     *
     * @return Tab
     */
    private function alipayTab(): Tab
    {
        return amis()->Tab()->title('支付宝')->body([
            amis()->TextControl('payment.alipay.app_id', '支付应用Appid'),
            amis()->TextControl('payment.alipay.public_key', '支付应用公钥')
                ->description('支付宝加签完成后申城的支付宝公钥'),
            amis()->TextControl('payment.alipay.private_key', '支付应用私钥'),
        ]);
    }

    /**
     * 获取配置数据
     *
     * @param Request $request
     * @return Response
     */
    public function getData(Request $request): Response
    {
        $data = $this->service->get();
        $data['id'] = 'payment';
        return $this->response()->success($data);
    }

    /**
     * 更新配置数据
     *
     * @param Request $request
     * @return Response
     */
    public function update(Request $request): Response
    {
        $data = $request->all();
        $result = $this->service->save($data['payment'] ?? []);
        return $this->autoResponse($result, '保存');
    }
}