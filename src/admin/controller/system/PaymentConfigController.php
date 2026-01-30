<?php

namespace warm\admin\controller\system;

use support\Request;
use support\Response;
use Webman\Http\UploadFile;
use warm\admin\controller\AdminController;
use warm\admin\renderer\form\Form;
use warm\admin\renderer\Page;
use warm\admin\service\system\PaymentConfigService;

/**
 * 支付配置控制器
 *
 * 用于管理系统支付配置，支持 alipay、wechat、unipay、douyin、jsb（yansongda/pay 结构）
 * 证书文件上传至 /resource/app/{平台}/ 目录
 */
class PaymentConfigController extends AdminController
{
    /** 证书上传根目录（相对 base_path） */
    private const CERT_UPLOAD_DIR = 'resource/app';

    /** 允许的证书扩展名 */
    private const CERT_EXTENSIONS = ['crt', 'pem', 'cer', 'pfx', 'key'];
    /**
     * @var string $serviceName 服务类名称
     */
    protected string $serviceName = PaymentConfigService::class;

    /**
     * @var string|null 当前编辑的平台ID
     */
    protected ?string $currentPlatformId = null;

    /** 平台ID => 名称 */
    private const PLATFORM_NAMES = [
        'alipay' => '支付宝',
        'wechat' => '微信支付',
        'unipay' => '银联支付',
        'douyin' => '抖音支付',
        'jsb' => '江苏银行',
    ];

    /**
     * 支付配置列表页面
     *
     * @return Page
     */
    public function list(): Page
    {
        $crud = amis()->CRUD()
            ->api($this->getListGetDataPath())
            ->filterTogglable(false)
            ->loadDataOnce()
            ->columns([
                amis()->TableColumn('name', '支付平台'),
                amis()->TableColumn('status', '状态')
                    ->type('mapping')
                    ->map([
                        '已启用' => '<span class="label label-success">已启用</span>',
                        '未启用' => '<span class="label label-danger">未启用</span>',
                    ]),
                amis()->TableColumn('mch_id', '商户号')
                    ->visibleOn('data.mch_id')
                    ->copyable(),
                amis()->TableColumn('app_id', '应用ID')
                    ->visibleOn('data.app_id')
                    ->copyable(),
                $this->rowActions([
                    $this->rowEditButton(false, 'lg'),
                ]),
            ]);

        return $this->baseList($crud);
    }

    /**
     * 支付配置表单
     *
     * @param bool $isEdit 是否为编辑模式
     * @return Form
     */
    public function form(bool $isEdit = false): Form
    {
        $platformId = $this->currentPlatformId ?? request()->input('id') ?? request()->route?->param('id') ?? '';

        $form = $this->baseForm(false)->mode('horizontal');

        if (isset(self::PLATFORM_NAMES[$platformId])) {
            $form->body($this->formBodyFor($platformId));
        } else {
            $form->body([]);
        }

        return $form;
    }

    /**
     * 按平台返回表单内容
     *
     * @param string $id alipay|wechat|unipay|douyin|jsb
     * @return array
     */
    private function formBodyFor(string $id): array
    {
        $enableName = "payment.{$id}.enable";
        $d = "payment.{$id}.default";

        $enable = [amis()->Switch($enableName, '启用')->value(0)];

        return match ($id) {
            'alipay' => array_merge($enable, $this->alipayFields($d, $id)),
            'wechat' => array_merge($enable, $this->wechatFields($d, $id)),
            'unipay' => array_merge($enable, $this->unipayFields($d, $id)),
            'douyin' => array_merge($enable, $this->douyinFields($d, $id)),
            'jsb' => array_merge($enable, $this->jsbFields($d, $id)),
            default => [],
        };
    }

    /** 证书上传接口 URL，?platform= 必填 */
    private function certUploadReceiver(string $platform): string
    {
        return admin_url('system/payment_config/upload?platform=' . $platform);
    }

    private function certFile(string $d, string $name, string $label, string $desc, string $platform, bool $required = true)
    {
        $ext = '.crt,.pem,.cer,.pfx,.key';
        $el = amis()->InputFile("{$d}.{$name}", $label)
            ->accept($ext)
            ->maxLength(1)
            ->autoUpload(true)
            ->receiver($this->certUploadReceiver($platform))
            ->valueField('value')
            ->btnLabel('上传证书')
            ->description('保存于 /resource/app/' . $platform . '/。' . $desc);
        if ($required) {
            $el->required();
        }
        return $el;
    }

    private function alipayFields(string $d, string $platform): array
    {
        return [
            amis()->Wrapper()->size('none')->label('支付宝 default 配置')->body([
                amis()->InputText("{$d}.app_id", 'App ID')
                    ->description('「必填」支付宝分配的 app_id')
                    ->required(),
                $this->certFile($d, 'app_secret_cert', '应用私钥', '「必填」应用私钥 .pem，在 open.alipay.com 应用详情->开发设置->接口加签方式 中设置', $platform),
                $this->certFile($d, 'app_public_cert_path', '应用公钥证书', '「必填」如 appCertPublicKey_xxx.crt', $platform),
                $this->certFile($d, 'alipay_public_cert_path', '支付宝公钥证书', '「必填」如 alipayCertPublicKey_RSA2.crt', $platform),
                $this->certFile($d, 'alipay_root_cert_path', '支付宝根证书', '「必填」如 alipayRootCert.crt', $platform),
                amis()->InputText("{$d}.return_url", '同步回调 return_url'),
                amis()->InputText("{$d}.notify_url", '异步通知 notify_url'),
                amis()->InputText("{$d}.app_auth_token", '第三方应用授权 token')->description('选填'),
                amis()->InputText("{$d}.service_provider_id", '服务商 id')->description('选填，mode 为 MODE_SERVICE 时使用'),
                amis()->Select("{$d}.mode", '模式')
                    ->options([
                        ['label' => 'MODE_NORMAL', 'value' => 'normal'],
                        ['label' => 'MODE_SANDBOX', 'value' => 'sandbox'],
                        ['label' => 'MODE_SERVICE', 'value' => 'service'],
                    ])
                    ->value('normal'),
            ]),
        ];
    }

    private function wechatFields(string $d, string $platform): array
    {
        return [
            amis()->Wrapper()->size('none')->label('微信 default 配置')->body([
                amis()->InputText("{$d}.mch_id", '商户号')
                    ->description('「必填」https://pay.weixin.qq.com/ 账户中心->商户信息')
                    ->required(),
                amis()->InputPassword("{$d}.mch_secret_key_v2", 'V2 商户密钥')
                    ->description('选填')->revealPassword(true),
                amis()->InputPassword("{$d}.mch_secret_key", 'V3 商户秘钥')
                    ->description('「必填」API v3 密钥，账户中心->API安全')
                    ->revealPassword(true)
                    ->required(),
                $this->certFile($d, 'mch_secret_cert', '商户私钥', '「必填」API证书 PRIVATE KEY，如 apiclient_key.pem', $platform),
                $this->certFile($d, 'mch_public_cert_path', '商户公钥证书', '「必填」如 apiclient_cert.pem', $platform),
                amis()->InputText("{$d}.notify_url", '微信回调 notify_url')
                    ->description('「必填」不能有 ? 号、空格等')
                    ->required(),
                amis()->InputText("{$d}.mp_app_id", '公众号 App ID')->description('选填'),
                amis()->InputText("{$d}.mini_app_id", '小程序 App ID')->description('选填'),
                amis()->InputText("{$d}.app_id", 'App App ID')->description('选填'),
                amis()->InputText("{$d}.sub_mp_app_id", '子公众号 App ID')->description('服务商选填'),
                amis()->InputText("{$d}.sub_app_id", '子 App ID')->description('服务商选填'),
                amis()->InputText("{$d}.sub_mini_app_id", '子小程序 App ID')->description('服务商选填'),
                amis()->InputText("{$d}.sub_mch_id", '子商户号')->description('服务商选填'),
                amis()->Textarea("{$d}.wechat_public_cert_path", '微信支付公钥证书')
                    ->description('JSON 对象，如 {"公钥ID":"证书路径"}，路径可先上传证书后填写 resource/app/' . $platform . '/ 下的相对路径'),
                amis()->Select("{$d}.mode", '模式')
                    ->options([
                        ['label' => 'MODE_NORMAL', 'value' => 'normal'],
                        ['label' => 'MODE_SERVICE', 'value' => 'service'],
                    ])
                    ->value('normal'),
            ]),
        ];
    }

    private function unipayFields(string $d, string $platform): array
    {
        return [
            amis()->Wrapper()->size('none')->label('银联 default 配置')->body([
                amis()->InputText("{$d}.mch_id", '商户号')->description('「必填」')->required(),
                amis()->InputPassword("{$d}.mch_secret_key", '商户密钥')
                    ->description('选填，https://up.95516.com/open/openapi?code=unionpay')->revealPassword(true),
                $this->certFile($d, 'mch_cert_path', '商户公私钥', '「必填」如 unipayAppCert.pfx', $platform),
                amis()->InputPassword("{$d}.mch_cert_password", '商户公私钥密码')
                    ->description('「必填」')
                    ->revealPassword(true)
                    ->required(),
                $this->certFile($d, 'unipay_public_cert_path', '银联公钥证书', '「必填」如 unipayCertPublicKey.cer', $platform),
                amis()->InputText("{$d}.return_url", 'return_url')->description('「必填」')->required(),
                amis()->InputText("{$d}.notify_url", 'notify_url')->description('「必填」')->required(),
                amis()->Select("{$d}.mode", '模式')
                    ->options([
                        ['label' => 'MODE_NORMAL', 'value' => 'normal'],
                    ])
                    ->value('normal'),
            ]),
        ];
    }

    private function douyinFields(string $d, string $platform): array
    {
        return [
            amis()->Wrapper()->size('none')->label('抖音 default 配置')->body([
                amis()->InputText("{$d}.mch_id", '商户号')
                    ->description('选填，抖音开放平台 应用详情->支付信息->产品管理'),
                amis()->InputPassword("{$d}.mch_secret_token", '支付 Token')
                    ->description('「必填」支付设置->Token(令牌)')
                    ->revealPassword(true)
                    ->required(),
                amis()->InputPassword("{$d}.mch_secret_salt", '支付 SALT')
                    ->description('「必填」支付设置->SALT')
                    ->revealPassword(true)
                    ->required(),
                amis()->InputText("{$d}.mini_app_id", '小程序 app_id')
                    ->description('「必填」支付设置->小程序appid')
                    ->required(),
                amis()->InputText("{$d}.thirdparty_id", '服务商 id')->description('选填'),
                amis()->InputText("{$d}.notify_url", '抖音支付回调')->description('选填'),
            ]),
        ];
    }

    private function jsbFields(string $d, string $platform): array
    {
        return [
            amis()->Wrapper()->size('none')->label('江苏银行 default 配置')->body([
                amis()->InputText("{$d}.svr_code", '服务代码'),
                amis()->InputText("{$d}.partner_id", '合作商 ID')->description('「必填」')->required(),
                amis()->InputText("{$d}.public_key_code", '公私钥对编号')->description('「必填」')->value('00')->required(),
                $this->certFile($d, 'mch_secret_cert_path', '商户私钥', '「必填」加密签名', $platform),
                $this->certFile($d, 'mch_public_cert_path', '商户公钥证书', '「必填」供江苏银行验证签名', $platform),
                $this->certFile($d, 'jsb_public_cert_path', '江苏银行公钥', '「必填」解密江苏银行返回数据', $platform),
                amis()->InputText("{$d}.notify_url", '支付通知地址'),
                amis()->Select("{$d}.mode", '模式')
                    ->options([
                        ['label' => 'MODE_NORMAL 正式', 'value' => 'normal'],
                        ['label' => 'MODE_SANDBOX 测试', 'value' => 'sandbox'],
                    ])
                    ->value('normal'),
            ]),
        ];
    }

    /**
     * 支付证书上传
     * 保存至 /resource/app/{platform}/，返回相对 base_path 的路径如 resource/app/alipay/xxx.crt
     *
     * @param Request $request
     * @return Response
     */
    public function uploadCert(Request $request): Response
    {
        $platform = $request->input('platform', '');
        if (!isset(self::PLATFORM_NAMES[$platform])) {
            return $this->response()->fail('无效的 platform');
        }

        $file = $request->file('file');
        if (!$file instanceof UploadFile) {
            return $this->response()->fail('请选择证书文件');
        }

        $name = $file->getUploadName();
        $base = basename($name);
        if ($base === '' || preg_match('/[^\w.\-]/', $base)) {
            return $this->response()->fail('非法文件名');
        }
        $ext = strtolower(pathinfo($base, PATHINFO_EXTENSION));
        if (!in_array($ext, self::CERT_EXTENSIONS, true)) {
            return $this->response()->fail('仅允许 .crt .pem .cer .pfx .key');
        }

        $dir = base_path(self::CERT_UPLOAD_DIR . '/' . $platform);
        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }
        $fullPath = $dir . '/' . $base;
        if (is_uploaded_file($file->getPathname())) {
            move_uploaded_file($file->getPathname(), $fullPath);
        } else {
            copy($file->getPathname(), $fullPath);
        }

        $relative = self::CERT_UPLOAD_DIR . '/' . $platform . '/' . $base;
        return $this->response()->success(['value' => $relative, 'id' => 0]);
    }
}
