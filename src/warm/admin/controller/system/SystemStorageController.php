<?php

namespace warm\admin\controller\system;

use support\Request;
use support\Response;
use warm\admin\controller\AdminController;
use warm\admin\renderer\Form;
use warm\admin\service\system\StorageService;

/**
 * 存储设置控制器
 * 
 * 用于管理系统文件存储配置
 * 支持本地存储和多种云存储服务配置
 */
class SystemStorageController extends AdminController
{
    /**
     * @var string $serviceName 服务类名称
     * 指定当前控制器使用的服务类
     */
    protected string $serviceName = StorageService::class;

    /**
     * 存储设置页面
     * 
     * 展示存储配置表单，支持多种存储引擎配置
     * 
     * @return Response 返回存储设置页面
     */
    public function index(): Response
    {
        $this->isEdit = true;
        if ($this->actionOfGetData()) {
            return $this->response()->success($this->service->getEditData(0));
        }
        $form = amis()->Wrapper()->className('')->body([
            amis()->Card()->body('<div class="bg-yellow-100 text-yellow-600 p-2">⚠ 温馨提示：1.切换存储方式后，需要将资源文件传输至新的存储端；2.请勿随意切换存储方式，可能导致图片无法查看</div>'),
            amis()
                ->Card()
                ->className('base-form')
                ->header(['title' => '存储设置'])
                // ->toolbar([$this->backButton()])
                ->body(
                    [
                        $this->form(true)->api('put:' . admin_url($this->queryPath . '/update'))->initApi(admin_url($this->queryPath . '?_action=getData')),
                    ]
                ),
        ]);

        $page = $this->basePage()->body($form);

        return $this->response()->success($page);
    }

    /**
     * 存储配置表单
     * 
     * 定义存储配置表单结构，支持多种存储引擎的参数配置
     * 
     * @return Form 返回存储配置表单
     */
    public function form(): Form
    {
        return $this->baseForm(false)
            ->panelClassName('px-10 m:px-0')->mode('horizontal')
            ->body([
                amis()->Wrapper()->body([
                    amis()->SelectControl('engine', '存储状态')
                        ->options(['local' => '本地存储', 'qiniu' => '七牛云存储', 'aliyun' => '阿里云存储', 'qcloud' => '腾讯云存储']),
                    amis()->TextControl('upload_size', '上传大小')->value('5242880')->description('单位Byte,1MB=1024*1024Byte'),
                    amis()->TextControl('file_type', '文件类型')->value('txt,doc,docx,xls,xlsx,ppt,pptx,rar,zip,7z,gz,pdf,wps,md'),
                    amis()->TextControl('image_type', '图片类型')->value('jpg,jpeg,png,gif,svg,bmp'),
                ]),
                amis()->Wrapper()->visibleOn('engine==\'local\'')->body([
                    amis()->TextControl('storage.local.disk', '存储路径')->value('public')->required(),
                    amis()->TextControl('storage.local.root', 'root')->description('根目录，例如：uploads'),
                    amis()->TextControl('storage.local.domain', '域名')->description('请补全http://或https://，例如https://zzz.xxx.com')->required(),
                ]),
                amis()->Wrapper()->visibleOn('engine==\'qiniu\'')->body([
                    amis()->TextControl('storage.qiniu.bucket', '存储空间')->required(),
                    amis()->TextControl('storage.qiniu.access_key', 'AccessKey')->required(),
                    amis()->TextControl('storage.qiniu.secret_key', 'SecretKey')->required(),
                    amis()->TextControl('storage.qiniu.root', 'root')->description('根目录，例如：uploads'),
                    amis()->TextControl('storage.qiniu.domain', '域名')->required()->description('请补全http://或https://，例如https://zzz.xxx.com'),
                ]),
                amis()->Wrapper()->visibleOn('engine==\'aliyun\'')->body([
                    amis()->TextControl('storage.aliyun.bucket', '存储空间')->required(),
                    amis()->TextControl('storage.aliyun.access_key', 'AccessKey')->required(),
                    amis()->TextControl('storage.aliyun.secret_key', 'SecretKey')->required(),
                    amis()->TextControl('storage.aliyun.root', 'root')->description('根目录，例如：uploads'),
                    amis()->TextControl('storage.aliyun.domain', '域名')->required()->description('请补全http://或https://，例如https://zzz.xxx.com'),
                ]),
                amis()->Wrapper()->visibleOn('engine==\'qcloud\'')->body([
                    amis()->TextControl('storage.qcloud.bucket', '存储空间')->required(),
                    amis()->TextControl('storage.qcloud.access_key', 'AccessKey')->required(),
                    amis()->TextControl('storage.qcloud.secret_key', 'SecretKey')->required(),
                    amis()->TextControl('storage.qcloud.domain', '域名')->required()->description('请补全http://或https://，例如https://zzz.xxx.com'),
                    amis()->TextControl('storage.qcloud.root', 'root')->description('根目录，例如：uploads'),
                    amis()->TextControl('storage.qcloud.region', 'REGION')->required(),
                ]),
            ]);
    }

    /**
     * 更新存储配置
     * 
     * 保存用户提交的存储配置信息
     * 
     * @param Request $request HTTP请求对象
     * @param mixed $id 数据ID
     * @return Response 返回操作结果响应
     */
    public function update(Request $request, $id): Response
    {
        $response = fn($result) => $this->autoResponse($result, translator('admin.save'));
        return $response($this->service->saveConfig($request->all()));
    }
}
