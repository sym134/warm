<?php

namespace warm\admin\controller\notice;

use support\Request;
use support\Response;
use warm\admin\controller\AdminController;
use warm\admin\renderer\Form;
use warm\admin\renderer\Page;
use warm\admin\service\notice\NoticeConfigService;
use warm\common\service\NoticeConfigDefaults;
use warm\framework\support\facade\Storage;

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
    protected string $serviceName = NoticeConfigService::class;

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

                amis()->GroupControl()->body([
                    amis()->GroupControl()->direction('vertical')->body([
                        amis()->TextControl('app_id', 'App ID')
                            ->required()
                            ->placeholder('请输入微信公众号App ID'),

                        amis()->TextControl('app_secret', 'App Secret')
                            ->required()
                            ->placeholder('请输入微信公众号App Secret')
                            ->type('input-password'),

                        amis()->TextControl('token', 'Token')
                            ->placeholder('请输入微信公众号Token'),

                        amis()->TextControl('aes_key', 'AES Key')
                            ->placeholder('请输入微信公众号AES Key'),

                        amis()->FileControl('verify_file_path', '微信校验文件')
                            ->maxLength(1)
                            ->accept('.txt')
                            ->autoUpload(true)
                            ->uploadType('file')
                            ->api('post:' . admin_url('upload_file'))
                            ->btnLabel('上传文件')
                            ->btnClassName('btn-secondary')
                            ->drag(true)
                            ->useChunk(false)
                            ->placeholder('请选择微信校验文件，文件将自动上传至public目录')
                            ->description('上传的文件将保存在 /public 目录下，并在此保存文件路径'),

                        amis()->SwitchControl('enable', '启用')
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

        // 处理微信校验文件上传
        if (!empty($postData['verify_file_path'])) {
            if (!Storage::has($postData['verify_file_path'])) {
                return $this->response()->fail('无法读取文件: ' . $postData['verify_file_path']);
            }

            // 使用Storage类获取文件内容
            $fileContent = Storage::read($postData['verify_file_path']);
            $fileName = basename($postData['verify_file_path']);
            // 确保public目录存在
            $publicPath = base_path('public');
            if (!is_dir($publicPath)) {
                mkdir($publicPath, 0755, true);
            }

            // 保存文件到public目录，保持文件名一致
            $targetPath = $publicPath . '/' . $fileName;
            file_put_contents($targetPath, $fileContent);

            $postData['verify_file_path'] = '/' . $fileName;
        }

        $result = $this->service->saveConfig('wechat_official_account', $postData);

        if ($result) {
            return $this->response()->successMessage('保存成功');
        }

        return $this->response()->fail('保存失败: ' . $this->service->getError());
    }
}