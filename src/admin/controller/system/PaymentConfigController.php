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

    /** 允许的平台ID列表 */
    private const PLATFORM_IDS = [
        'alipay',
        'wechat',
        'unipay',
        'douyin',
        'jsb',
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
                amis()->TableColumn('name', translator('system.payment.platform')),
                amis()->TableColumn('status', translator('system.payment.status'))
                    ->type('mapping')
                    ->map([
                        '已启用' => '<span class="label label-success">' . translator('system.payment.enabled') . '</span>',
                        '未启用' => '<span class="label label-danger">' . translator('system.payment.disabled') . '</span>',
                    ]),
                amis()->TableColumn('mch_id', translator('system.payment.mch_id'))
                    ->visibleOn('data.mch_id')
                    ->copyable(),
                amis()->TableColumn('app_id', translator('system.payment.app_id'))
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

        if (in_array($platformId, self::PLATFORM_IDS)) {
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

        $enable = [amis()->Switch($enableName, translator('system.payment.enable'))->value(0)];

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
            ->btnLabel(translator('system.payment.upload_cert'))
            ->description(translator('system.payment.upload_desc', ['platform' => $platform, 'desc' => $desc]));
        if ($required) {
            $el->required();
        }
        return $el;
    }

    private function alipayFields(string $d, string $platform): array
    {
        return [
            amis()->Wrapper()->size('none')->label(translator('system.payment.alipay.title'))->body([
                amis()->InputText("{$d}.app_id", 'App ID')
                    ->description(translator('system.payment.alipay.app_id_desc'))
                    ->required(),
                $this->certFile($d, 'app_secret_cert', translator('system.payment.alipay.app_secret_cert'), translator('system.payment.alipay.app_secret_cert_desc'), $platform),
                $this->certFile($d, 'app_public_cert_path', translator('system.payment.alipay.app_public_cert_path'), translator('system.payment.alipay.app_public_cert_path_desc'), $platform),
                $this->certFile($d, 'alipay_public_cert_path', translator('system.payment.alipay.alipay_public_cert_path'), translator('system.payment.alipay.alipay_public_cert_path_desc'), $platform),
                $this->certFile($d, 'alipay_root_cert_path', translator('system.payment.alipay.alipay_root_cert_path'), translator('system.payment.alipay.alipay_root_cert_path_desc'), $platform),
                amis()->InputText("{$d}.return_url", translator('system.payment.alipay.return_url')),
                amis()->InputText("{$d}.notify_url", translator('system.payment.alipay.notify_url')),
                amis()->InputText("{$d}.app_auth_token", translator('system.payment.alipay.app_auth_token'))->description(translator('system.payment.alipay.app_auth_token_desc')),
                amis()->InputText("{$d}.service_provider_id", translator('system.payment.alipay.service_provider_id'))->description(translator('system.payment.alipay.service_provider_id_desc')),
                amis()->Select("{$d}.mode", translator('system.payment.mode'))
                    ->options([
                        ['label' => translator('system.payment.mode_options.normal'), 'value' => 'normal'],
                        ['label' => translator('system.payment.mode_options.sandbox'), 'value' => 'sandbox'],
                        ['label' => translator('system.payment.mode_options.service'), 'value' => 'service'],
                    ])
                    ->value('normal'),
            ]),
        ];
    }

    private function wechatFields(string $d, string $platform): array
    {
        return [
            amis()->Wrapper()->size('none')->label(translator('system.payment.wechat.title'))->body([
                amis()->InputText("{$d}.mch_id", translator('system.payment.mch_id'))
                    ->description(translator('system.payment.wechat.mch_id_desc'))
                    ->required(),
                amis()->InputPassword("{$d}.mch_secret_key_v2", translator('system.payment.wechat.mch_secret_key_v2'))
                    ->description(translator('system.payment.wechat.mch_secret_key_v2_desc'))->revealPassword(true),
                amis()->InputPassword("{$d}.mch_secret_key", translator('system.payment.wechat.mch_secret_key'))
                    ->description(translator('system.payment.wechat.mch_secret_key_desc'))
                    ->revealPassword(true)
                    ->required(),
                $this->certFile($d, 'mch_secret_cert', translator('system.payment.wechat.mch_secret_cert'), translator('system.payment.wechat.mch_secret_cert_desc'), $platform),
                $this->certFile($d, 'mch_public_cert_path', translator('system.payment.wechat.mch_public_cert_path'), translator('system.payment.wechat.mch_public_cert_path_desc'), $platform),
                amis()->InputText("{$d}.notify_url", translator('system.payment.wechat.notify_url'))
                    ->description(translator('system.payment.wechat.notify_url_desc'))
                    ->required(),
                amis()->InputText("{$d}.mp_app_id", translator('system.payment.wechat.mp_app_id'))->description(translator('system.payment.wechat.mp_app_id_desc')),
                amis()->InputText("{$d}.mini_app_id", translator('system.payment.wechat.mini_app_id'))->description(translator('system.payment.wechat.mini_app_id_desc')),
                amis()->InputText("{$d}.app_id", translator('system.payment.app_id'))->description(translator('system.payment.wechat.app_id_desc')),
                amis()->InputText("{$d}.sub_mp_app_id", translator('system.payment.wechat.sub_mp_app_id'))->description(translator('system.payment.wechat.sub_mp_app_id_desc')),
                amis()->InputText("{$d}.sub_app_id", translator('system.payment.wechat.sub_app_id'))->description(translator('system.payment.wechat.sub_app_id_desc')),
                amis()->InputText("{$d}.sub_mini_app_id", translator('system.payment.wechat.sub_mini_app_id'))->description(translator('system.payment.wechat.sub_mini_app_id_desc')),
                amis()->InputText("{$d}.sub_mch_id", translator('system.payment.wechat.sub_mch_id'))->description(translator('system.payment.wechat.sub_mch_id_desc')),
                amis()->Textarea("{$d}.wechat_public_cert_path", translator('system.payment.wechat.wechat_public_cert_path'))
                    ->description(translator('system.payment.wechat.wechat_public_cert_path_desc', ['platform' => $platform])),
                amis()->Select("{$d}.mode", translator('system.payment.mode'))
                    ->options([
                        ['label' => translator('system.payment.mode_options.normal'), 'value' => 'normal'],
                        ['label' => translator('system.payment.mode_options.service'), 'value' => 'service'],
                    ])
                    ->value('normal'),
            ]),
        ];
    }

    private function unipayFields(string $d, string $platform): array
    {
        return [
            amis()->Wrapper()->size('none')->label(translator('system.payment.unipay.title'))->body([
                amis()->InputText("{$d}.mch_id", translator('system.payment.mch_id'))->description(translator('system.payment.unipay.mch_id_desc'))->required(),
                amis()->InputPassword("{$d}.mch_secret_key", translator('system.payment.unipay.mch_secret_key'))
                    ->description(translator('system.payment.unipay.mch_secret_key_desc'))->revealPassword(true),
                $this->certFile($d, 'mch_cert_path', translator('system.payment.unipay.mch_cert_path'), translator('system.payment.unipay.mch_cert_path_desc'), $platform),
                amis()->InputPassword("{$d}.mch_cert_password", translator('system.payment.unipay.mch_cert_password'))
                    ->description(translator('system.payment.unipay.mch_cert_password_desc'))
                    ->revealPassword(true)
                    ->required(),
                $this->certFile($d, 'unipay_public_cert_path', translator('system.payment.unipay.unipay_public_cert_path'), translator('system.payment.unipay.unipay_public_cert_path_desc'), $platform),
                amis()->InputText("{$d}.return_url", translator('system.payment.unipay.return_url'))->description(translator('system.payment.unipay.return_url_desc'))->required(),
                amis()->InputText("{$d}.notify_url", translator('system.payment.unipay.notify_url'))->description(translator('system.payment.unipay.notify_url_desc'))->required(),
                amis()->Select("{$d}.mode", translator('system.payment.mode'))
                    ->options([
                        ['label' => translator('system.payment.mode_options.normal'), 'value' => 'normal'],
                    ])
                    ->value('normal'),
            ]),
        ];
    }

    private function douyinFields(string $d, string $platform): array
    {
        return [
            amis()->Wrapper()->size('none')->label(translator('system.payment.douyin.title'))->body([
                amis()->InputText("{$d}.mch_id", translator('system.payment.mch_id'))
                    ->description(translator('system.payment.douyin.mch_id_desc')),
                amis()->InputPassword("{$d}.mch_secret_token", translator('system.payment.douyin.mch_secret_token'))
                    ->description(translator('system.payment.douyin.mch_secret_token_desc'))
                    ->revealPassword(true)
                    ->required(),
                amis()->InputPassword("{$d}.mch_secret_salt", translator('system.payment.douyin.mch_secret_salt'))
                    ->description(translator('system.payment.douyin.mch_secret_salt_desc'))
                    ->revealPassword(true)
                    ->required(),
                amis()->InputText("{$d}.mini_app_id", translator('system.payment.douyin.mini_app_id'))
                    ->description(translator('system.payment.douyin.mini_app_id_desc'))
                    ->required(),
                amis()->InputText("{$d}.thirdparty_id", translator('system.payment.douyin.thirdparty_id'))->description(translator('system.payment.douyin.thirdparty_id_desc')),
                amis()->InputText("{$d}.notify_url", translator('system.payment.douyin.notify_url'))->description(translator('system.payment.douyin.notify_url_desc')),
            ]),
        ];
    }

    private function jsbFields(string $d, string $platform): array
    {
        return [
            amis()->Wrapper()->size('none')->label(translator('system.payment.jsb.title'))->body([
                amis()->InputText("{$d}.svr_code", translator('system.payment.jsb.svr_code')),
                amis()->InputText("{$d}.partner_id", translator('system.payment.jsb.partner_id'))->description(translator('system.payment.jsb.partner_id_desc'))->required(),
                amis()->InputText("{$d}.public_key_code", translator('system.payment.jsb.public_key_code'))->description(translator('system.payment.jsb.public_key_code_desc'))->value('00')->required(),
                $this->certFile($d, 'mch_secret_cert_path', translator('system.payment.jsb.mch_secret_cert_path'), translator('system.payment.jsb.mch_secret_cert_path_desc'), $platform),
                $this->certFile($d, 'mch_public_cert_path', translator('system.payment.jsb.mch_public_cert_path'), translator('system.payment.jsb.mch_public_cert_path_desc'), $platform),
                $this->certFile($d, 'jsb_public_cert_path', translator('system.payment.jsb.jsb_public_cert_path'), translator('system.payment.jsb.jsb_public_cert_path_desc'), $platform),
                amis()->InputText("{$d}.notify_url", translator('system.payment.jsb.notify_url')),
                amis()->Select("{$d}.mode", translator('system.payment.mode'))
                    ->options([
                        ['label' => translator('system.payment.mode_options.normal_desc'), 'value' => 'normal'],
                        ['label' => translator('system.payment.mode_options.sandbox_desc'), 'value' => 'sandbox'],
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
        if (!in_array($platform, self::PLATFORM_IDS)) {
            return $this->response()->fail(translator('system.payment.error.invalid_platform'));
        }

        $file = $request->file('file');
        if (!$file instanceof UploadFile) {
            return $this->response()->fail(translator('system.payment.error.no_file'));
        }

        $name = $file->getUploadName();
        $base = basename($name);
        if ($base === '' || preg_match('/[^\w.\-]/', $base)) {
            return $this->response()->fail(translator('system.payment.error.invalid_filename'));
        }
        $ext = strtolower(pathinfo($base, PATHINFO_EXTENSION));
        if (!in_array($ext, self::CERT_EXTENSIONS, true)) {
            return $this->response()->fail(translator('system.payment.error.invalid_extension'));
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
