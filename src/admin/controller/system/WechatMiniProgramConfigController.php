<?php

namespace warm\admin\controller\system;

use support\Request;
use support\Response;
use warm\admin\controller\AdminController;
use warm\admin\renderer\Page;
use warm\admin\service\config\ConfigService;

/**
 * 微信小程序配置控制器
 *
 * 用于管理微信小程序通知配置的增删改查操作
 */
class WechatMiniProgramConfigController extends AdminController
{
    /**
     * @var string $serviceName 服务类名称
     */
    protected string $serviceName = ConfigService::class;

    /**
     * 列表页面
     */
    public function index(): Response
    {
        // 如果是获取数据的操作，返回列表数据
        if ($this->actionOfGetData()) {
            return $this->response()->success($this->service->getEditData('wechat_mini_program'));
        }

        // 如果是导出操作，执行导出逻辑
        if ($this->actionOfExport()) {
            return $this->export();
        }

        // 默认返回列表页面
        return $this->response()->success($this->list());
    }

    /**
     * 微信小程序配置表单页面
     *
     * 展示并编辑微信小程序配置信息
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
                    ->body('请填写微信小程序相关配置信息')
                    ->level('info')
                    ->showIcon(),

                amis()->Group()->body([
                    amis()->Group()->direction('vertical')->body([
                        amis()->InputText('app_id', 'App ID')
                            ->required()
                            ->placeholder('请输入微信小程序App ID'),

                        amis()->InputText('app_secret', 'App Secret')
                            ->required()
                            ->placeholder('请输入微信小程序App Secret')
                            ->type('input-password'),

                        amis()->Switch('enable', '启用')
                            ->trueValue(1)
                            ->falseValue(0)
                            ->option('启用')
                    ])
                ])
            ]);

        return amis()->Page()
            ->title('微信小程序配置')
            ->body($form);
    }

    /**
     * 保存微信小程序配置
     *
     * @param Request $request HTTP请求对象
     * @return Response 响应对象
     */
    public function save(Request $request): Response
    {
        $result = $this->service->saveConfig('wechat_mini_program', $request->post());

        if ($result) {
            return $this->response()->successMessage('保存成功');
        }

        return $this->response()->fail('保存失败: ' . $this->service->getError());
    }
}