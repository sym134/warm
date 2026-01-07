<?php

namespace warm\admin\controller\system;

use warm\admin\controller\AdminController;
use warm\admin\model\system\SystemFile;
use warm\admin\renderer\Form;
use warm\admin\renderer\Page;
use warm\admin\service\system\SystemFileService;

/**
 * 系统文件控制器
 * 
 * 用于管理系统附件文件的浏览和管理
 * 提供文件列表查看和筛选功能
 */
class SystemFileController extends AdminController
{
    /**
     * @var string $serviceName 服务类名称
     * 指定当前控制器使用的服务类
     */
    protected string $serviceName = SystemFileService::class;

    /**
     * 文件列表页面
     * 
     * 展示系统附件文件列表，支持按文件类型和存储模式筛选
     * 
     * @return Page 返回文件列表页面
     */
    public function list(): Page
    {
        $crud = $this->baseCRUD()
            ->headerToolbar([
                ...$this->baseHeaderToolBar(),
            ])
            ->filterDefaultVisible(true)
            ->filter(
                $this->baseFilter()->submitOnChange()->body([
                    amis()->InputText('origin_name', translator('admin.admin_attachments.origin_name'))
                        ->size('sm'),
                    amis()->Select('file_type', translator('admin.admin_attachments.file_type'))
                        ->size('xs')->options(SystemFile::FILE_TYPE),
                    amis()->Select('storage_mode', translator('admin.admin_attachments.storage_mode'))
                        ->size('xs')->options(SystemFile::STORAGE_MODE),
                ])->actions()
            )
            ->columns([
                amis()->TableColumn('id', 'ID'),
                amis()->Image('url','预览')->enlargeAble()->width(70),
                amis()->TableColumn('storage_mode', translator('admin.admin_attachments.storage_mode'))
                    ->type('mapping')->map(SystemFile::STORAGE_MODE),
                amis()->TableColumn('origin_name', translator('admin.admin_attachments.origin_name')),
                amis()->TableColumn('new_name', translator('admin.admin_attachments.new_name')),
                amis()->TableColumn('mime_type', translator('admin.admin_attachments.mime_type')),
                amis()->TableColumn('storage_path', translator('admin.admin_attachments.storage_path')),
                amis()->TableColumn('file_size', translator('admin.admin_attachments.file_size'))->type('tpl')->tpl('${round(file_size/1024)}' . 'MB'),
                amis()->TableColumn('created_at', translator('admin.created_at'))->type('datetime')->sortable(true),
                $this->rowActions([
                    $this->rowDeleteButton(),
                ]),
            ]);

        return $this->baseList($crud);
    }

    /**
     * 文件表单页面
     * 
     * 定义文件管理表单结构
     * 
     * @return Form 返回文件表单
     */
    public function form(): Form
    {
        return $this->baseForm()
            ->body();
    }
}
