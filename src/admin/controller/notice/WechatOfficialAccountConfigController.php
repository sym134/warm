<?php

namespace warm\admin\controller\notice;

use warm\framework\filesystem\facade\Storage;
use support\Request;
use support\Response;
use warm\admin\controller\AdminController;
use warm\admin\renderer\Page;
use warm\admin\service\config\ConfigService;

/**
 * 微信公众号配置控制器
 *
 * 用于管理微信公众号通知配置的增删改查操作
 */
class WechatOfficialAccountConfigController extends AdminController
{
    /**
     * @var string $serviceName 服务类名称
     */
    protected string $serviceName = ConfigService::class;

    public function index(): Response
    {
        // 如果是获取数据的操作，返回列表数据
        if ($this->actionOfGetData()) {
            return $this->response()->success($this->service->getEditData('wechat_official_account'));
        }

        // 如果是导出操作，执行导出逻辑
        if ($this->actionOfExport()) {
            return $this->export();
        }

        // 默认返回列表页面
        return $this->response()->success($this->list());
    }

    /**
     * 微信公众号配置表单页面
     *
     * 展示并编辑微信公众号配置信息
     *
     * @return Page 返回配置表单页面
     */
    public function list(): Page
    {
        $form = $this->baseForm(false)
            ->initApi(admin_url($this->queryPath . '?_action=getData'))
            ->api('post:' . admin_url($this->queryPath . '/save'))
            ->body([
                amis()->Alert()
                    ->body('请填写微信公众号相关配置信息')
                    ->level('info')
                    ->showIcon(),

                amis()->Group()->body([
                    amis()->Group()->direction('vertical')->body([
                        amis()->InputText('app_id', 'App ID')
                            ->required()
                            ->placeholder('请输入微信公众号App ID'),

                        amis()->InputText('app_secret', 'App Secret')
                            ->required()
                            ->placeholder('请输入微信公众号App Secret')
                            ->type('input-password'),

                        amis()->InputText('token', 'Token')
                            ->placeholder('请输入微信公众号Token'),

                        amis()->InputText('aes_key', 'AES Key')
                            ->placeholder('请输入微信公众号AES Key'),

                        amis()->InputFile('verify_file_path', '微信校验文件')
                            ->maxLength(1)
                            ->accept('.txt')
                            ->autoUpload(true)
                            ->uploadType('file')
                            ->receiver(admin_url('/app/wechat_official_account_config/upload'))
                            ->btnLabel('上传文件')
                            ->btnClassName('btn-secondary')
                            ->drag(true)
                            ->useChunk(false)
                            ->placeholder('请选择微信校验文件，文件将自动上传至public目录')
                            ->description('上传的文件将保存在 /public 目录下，并在此保存文件路径'),

                        amis()->Switch('enable', '启用')
                            ->trueValue(1)
                            ->falseValue(0)
                            ->option('启用')
                    ])
                ])
            ]);

        return amis()->Page()
            ->title('微信公众号配置')
            ->body($form);
    }

    /**
     * 保存微信公众号配置
     *
     * @param Request $request HTTP请求对象
     * @return Response 响应对象
     */
    public function save(Request $request): Response
    {
        $postData = $request->post();

        // 路径为空删除旧的文件
        if (empty($postData['verify_file_path'])) {
            $config = $this->service->getWechatOfficialAccountConfig();
            if (!empty($config['verify_file_path'])) {
                Storage::delete($config['verify_file_path']);
            }
        }

        $result = $this->service->saveConfig('wechat_official_account', $postData);

        if ($result) {
            return $this->response()->successMessage('保存成功');
        }

        return $this->response()->fail('保存失败: ' . $this->service->getError());
    }

    public function upload(Request $request): Response
    {
        $file = $request->file('file');
        Storage::disk('local')->put($file->getUploadName(), $file->getPathname());
        return $this->response()->success(['value' => $file->getUploadName(), 'id' => 0]);
    }
}