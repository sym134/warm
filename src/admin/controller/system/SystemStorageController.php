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
        return amis()->Page()->body(amis()->Wrapper()->className('')->body([
            amis()->Card()->body([
                '<div class="bg-yellow-100 text-yellow-600 p-2">⚠ 温馨提示：1.切换存储方式后，需要将资源文件传输至新的存储端；2.请勿随意切换存储方式，可能导致图片无法查看</div>'
            ]),
            $this->form()->api('put:' . admin_url($this->queryPath . '/update'))
                ->initApi(admin_url($this->queryPath . '?_action=getData')),

        ]));
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
                    amis()->InputText('upload_size', '上传大小')->value('5242880')->description('单位Byte,1MB=1024*1024Byte'),
                    amis()->InputText('file_type', '文件类型')->value('txt,doc,docx,xls,xlsx,ppt,pptx,rar,zip,7z,gz,pdf,wps,md'),
                    amis()->InputText('image_type', '图片类型')->value('jpg,jpeg,png,gif,svg,bmp'),
                    amis()->Select('default', '存储状态')
                        ->options(['public' => '本地存储', 'qiniu' => '七牛云存储', 'aliyun' => '阿里云存储', 'qcloud' => '腾讯云存储']),
                ]),
                amis()->Wrapper()->visibleOn('${default == "public"}')->body([
                    amis()->InputText('disks.public.url', '域名')->description('请补全http://或https://，例如https://zzz.xxx.com')->required(),
                ]),
                amis()->Wrapper()->visibleOn('${default == "qiniu"}')->body([
                    amis()->Hidden('disks.qiniu.driver')->value('qiniu'),
                    amis()->InputText('disks.qiniu.bucket', '存储空间')->required(),
                    amis()->InputText('disks.qiniu.access_key', 'AccessKey')->required(),
                    amis()->InputText('disks.qiniu.secret_key', 'SecretKey')->required(),
                    amis()->InputText('disks.qiniu.root', 'root')->description('根目录，例如：uploads'),
                    amis()->InputText('disks.qiniu.url', '域名')->required()->description('请补全http://或https://，例如https://zzz.xxx.com'),
                ]),
                amis()->Wrapper()->visibleOn('${default == "aliyun"}')->body([
                    amis()->Hidden('disks.aliyun.driver')->value('oss'),
                    amis()->InputText('disks.aliyun.bucket', '存储空间')->required(),
                    amis()->InputText('disks.aliyun.access_key', 'AccessKey')->required(),
                    amis()->InputText('disks.aliyun.secret_key', 'SecretKey')->required(),
                    amis()->InputText('disks.aliyun.root', 'root')->description('根目录，例如：uploads'),
                    amis()->InputText('disks.aliyun.url', '域名')->required()->description('请补全http://或https://，例如https://zzz.xxx.com'),
                ]),
                amis()->Wrapper()->visibleOn('${default == "qcloud"}')->body([
                    amis()->Hidden('disks.qiniu.driver')->value('cos'),
                    amis()->InputText('disks.qcloud.bucket', '存储空间')->required(),
                    amis()->InputText('disks.qcloud.access_key', 'AccessKey')->required(),
                    amis()->InputText('disks.qcloud.secret_key', 'SecretKey')->required(),
                    amis()->InputText('disks.qcloud.url', '域名')->required()->description('请补全http://或https://，例如https://zzz.xxx.com'),
                    amis()->InputText('disks.qcloud.root', 'root')->description('根目录，例如：uploads'),
                    amis()->InputText('disks.qcloud.region', 'REGION')->required(),
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
