<?php

namespace warm\admin\controller\system;

use support\Request;
use support\Response;
use warm\admin\controller\AdminController;
use warm\admin\model\system\SystemFile;
use warm\admin\renderer\form\Form;
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
     * 展示系统附件文件列表，支持按文件类型和分组筛选
     * 提供网格视图和列表视图切换
     * 
     * @return Page 返回文件列表页面
     */
    public function list(): Page
    {
        // 文件类型标签
        $tabs = amis()->Tabs()
            ->tabsMode('line')
            ->className('mb-4')
            ->tabs([
                amis()->Tabs()
                    ->title('图片')
                    ->tab($this->fileContent('image'))
                    ->body($this->fileContent('image')),
                amis()->Tabs()
                    ->title('视频')
                    ->tab($this->fileContent('video'))
                    ->body($this->fileContent('video')),
                amis()->Tabs()
                    ->title('文件')
                    ->tab($this->fileContent('file'))
                    ->body($this->fileContent('file')),
            ]);

        return $this->basePage()->body($tabs);
    }

    /**
     * 文件内容区域
     * 
     * @param string $fileType 文件类型
     * @return mixed
     */
    private function fileContent(string $fileType)
    {
        return amis()->Grid()
            ->columns([
                // 左侧分组管理
                [
                    'body' => amis()->Card()
                        ->header(['title' => '分组管理'])
                        ->body([
                            $this->groupSidebar($fileType)
                        ]),
                    'md' => 3,
                ],
                // 右侧文件列表
                [
                    'body' => $this->fileListArea($fileType),
                    'md' => 9,
                ],
            ]);
    }

    /**
     * 左侧分组管理
     * 
     * @param string $fileType 文件类型
     * @return mixed
     */
    private function groupSidebar(string $fileType)
    {
        return amis()->Wrapper()->body([
            amis()->Button()
                ->label('全部')
                ->level('link')
                ->className('w-full text-left mb-2')
                ->actionType('url')
                ->url('?file_type=' . $fileType . '&group_id='),
            amis()->Button()
                ->label('未分组')
                ->level('link')
                ->className('w-full text-left mb-2')
                ->actionType('url')
                ->url('?file_type=' . $fileType . '&group_id=0'),
            amis()->Button()
                ->label('添加分组')
                ->icon('fa fa-plus')
                ->level('link')
                ->className('w-full text-left')
                ->actionType('dialog')
                ->dialog([
                    'title' => '添加分组',
                    'body' => [
                        amis()->InputText('name', '分组名称')->required(true),
                    ],
                    'actions' => [
                        amis()->Button()->label('确定')->actionType('submit'),
                        amis()->Button()->label('取消')->actionType('close'),
                    ],
                ]),
        ]);
    }

    /**
     * 文件列表区域
     * 
     * @param string $fileType 文件类型
     * @return mixed
     */
    private function fileListArea(string $fileType)
    {
        $crud = $this->baseCRUD()
            ->headerToolbar([
                amis()->InputFile()
                    ->label('本地上传')
                    ->accept('*')
                    ->multiple(true)
                    ->receiver('post:' . admin_url('upload_file') . '?file_type=' . $fileType),
                amis()->Button()
                    ->label('删除')
                    ->level('danger'),
                amis()->Button()
                    ->label('移动'),
                ...$this->baseHeaderToolBar(),
            ])
            ->filterDefaultVisible(true)
            ->filter(
                $this->baseFilter()->submitOnChange()->body([
                    amis()->Select('storage_mode', '文件来源')
                        ->placeholder('请选择文件来源')
                        ->options(SystemFile::STORAGE_MODE)
                        ->size('sm'),
                    amis()->InputText('origin_name', '名称')
                        ->placeholder('请输入名称')
                        ->size('sm'),
                ])->actions()
            )
            ->columns([
                amis()->TableColumn('id', 'ID'),
                amis()->TableColumn('url', '预览')
                    ->type('image')
                    ->enlargeAble(true)
                    ->width(100),
                amis()->TableColumn('origin_name', translator('admin.admin_attachments.origin_name')),
                amis()->TableColumn('storage_mode', translator('admin.admin_attachments.storage_mode'))
                    ->type('mapping')
                    ->map(SystemFile::STORAGE_MODE),
                amis()->TableColumn('file_size', translator('admin.admin_attachments.file_size'))
                    ->type('tpl')
                    ->tpl('${round(file_size/1024/1024, 2)} MB'),
                amis()->TableColumn('created_at', translator('admin.created_at'))
                    ->type('datetime')
                    ->sortable(true),
                $this->rowActions([
                    $this->rowDeleteButton(),
                ]),
            ]);

        // 添加文件类型筛选
        $crud->api(admin_url($this->queryPath) . '?file_type=' . $fileType);

        return $crud;
    }

    /**
     * 文件移动接口
     * 
     * @param Request $request
     * @return Response
     */
    public function move(Request $request): Response
    {
        $ids = $request->post('ids', '');
        $groupId = $request->post('group_id', 0);
        
        $result = $this->service->move($ids, $groupId);
        return $this->autoResponse($result, '移动');
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
