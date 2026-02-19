<?php

namespace warm\admin\controller\system;

use support\Request;
use support\Response;
use warm\admin\controller\AdminController;
use warm\admin\renderer\form\Form;
use warm\admin\renderer\Page;
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
     * @return Page
     */
    public function list(): Page
    {
        return amis()->Page()->body(
            [
                amis()->Card()->body([
                    '<div class="bg-yellow-100 text-yellow-600 p-2">' . translator('system.storage.warning') . '</div>'
                ]),
                $this->form()->api('put:' . admin_url($this->queryPath . '/update'))
                    ->initApi(admin_url($this->queryPath . '?_action=getData')),
    
            ]
        );
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
                    amis()->InputText('upload_size', translator('system.storage.upload_size'))->value('5242880')->description(translator('system.storage.upload_size_desc')),
                    amis()->InputText('file_type', translator('system.storage.file_type'))->value('txt,doc,docx,xls,xlsx,ppt,pptx,rar,zip,7z,gz,pdf,wps,md'),
                    amis()->InputText('image_type', translator('system.storage.image_type'))->value('jpg,jpeg,png,gif,svg,bmp'),
                    amis()->Select('default', translator('system.storage.default'))
                        ->options([
                            'public' => translator('system.storage.driver.public'),
                            'qiniu' => translator('system.storage.driver.qiniu'),
                            'aliyun' => translator('system.storage.driver.aliyun'),
                            'qcloud' => translator('system.storage.driver.qcloud')
                        ]),
                ]),
                amis()->Wrapper()->visibleOn('${default == "public"}')->body([
                    amis()->InputText('disks.public.url', translator('system.storage.domain'))->description(translator('system.storage.domain_desc'))->required(),
                ]),
                amis()->Wrapper()->visibleOn('${default == "qiniu"}')->body([
                    amis()->Hidden('disks.qiniu.driver')->value('qiniu'),
                    amis()->InputText('disks.qiniu.bucket', translator('system.storage.bucket'))->required(),
                    amis()->InputText('disks.qiniu.access_key', translator('system.storage.access_key'))->required(),
                    amis()->InputText('disks.qiniu.secret_key', translator('system.storage.secret_key'))->required(),
                    amis()->InputText('disks.qiniu.root', translator('system.storage.root'))->description(translator('system.storage.root_desc')),
                    amis()->InputText('disks.qiniu.url', translator('system.storage.domain'))->required()->description(translator('system.storage.domain_desc')),
                ]),
                amis()->Wrapper()->visibleOn('${default == "aliyun"}')->body([
                    amis()->Hidden('disks.aliyun.driver')->value('oss'),
                    amis()->InputText('disks.aliyun.bucket', translator('system.storage.bucket'))->required(),
                    amis()->InputText('disks.aliyun.access_key', translator('system.storage.access_key'))->required(),
                    amis()->InputText('disks.aliyun.secret_key', translator('system.storage.secret_key'))->required(),
                    amis()->InputText('disks.aliyun.root', translator('system.storage.root'))->description(translator('system.storage.root_desc')),
                    amis()->InputText('disks.aliyun.url', translator('system.storage.domain'))->required()->description(translator('system.storage.domain_desc')),
                ]),
                amis()->Wrapper()->visibleOn('${default == "qcloud"}')->body([
                    amis()->Hidden('disks.qiniu.driver')->value('cos'),
                    amis()->InputText('disks.qcloud.bucket', translator('system.storage.bucket'))->required(),
                    amis()->InputText('disks.qcloud.access_key', translator('system.storage.access_key'))->required(),
                    amis()->InputText('disks.qcloud.secret_key', translator('system.storage.secret_key'))->required(),
                    amis()->InputText('disks.qcloud.url', translator('system.storage.domain'))->required()->description(translator('system.storage.domain_desc')),
                    amis()->InputText('disks.qcloud.root', translator('system.storage.root'))->description(translator('system.storage.root_desc')),
                    amis()->InputText('disks.qcloud.region', translator('system.storage.region'))->required(),
                ]),
            ]);
    }

    /**
     * 更新存储配置
     *
     * 保存用户提交的存储配置信息
     *
     * @param Request $request HTTP请求对象
     * @return Response 返回操作结果响应
     */
    public function updateConfig(Request $request): Response
    {
        $response = fn($result) => $this->autoResponse($result, translator('admin.save'));
        return $response($this->service->saveConfig($request->all()));
    }
}
