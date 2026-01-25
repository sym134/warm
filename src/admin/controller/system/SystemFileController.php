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
 * 提供文件列表查看、上传、下载、删除、重命名、移动等功能
 */
class SystemFileController extends AdminController
{
    /**
     * @var string $serviceName 服务类名称
     * 指定当前控制器使用的服务类
     */
    protected string $serviceName = SystemFileService::class;

    /**
     * 不需要权限验证的方法
     */
    protected array $noNeedAuth = ['groups', 'download'];

    /**
     * 文件列表页面
     *
     * 展示系统附件文件列表，支持：
     * - 卡片视图和列表视图切换
     * - 按文件类型筛选（图片、视频、音频、文件）
     * - 按分组筛选
     * - 文件搜索
     * - 文件上传、删除、移动等操作
     *
     * @return Page 返回文件列表页面
     */
    public function list(): Page
    {
        // 获取文件类型参数（默认显示图片）
        $request = request();
        $defaultFileType = $request ? $request->input('file_type', 'image') : 'image';

        // 构建每个标签页的内容（图片、视频、音频、文件）
        $tabs = [];
        $fileTypes = [
            ['label' => '图片', 'value' => 'image'],
            ['label' => '视频', 'value' => 'video'],
            ['label' => '音频', 'value' => 'audio'],
            ['label' => '文件', 'value' => 'file'],
        ];

        foreach ($fileTypes as $fileType) {
            $tabs[] = $this->buildFileTypeTab($fileType['label'], $fileType['value']);
        }

        // 创建标签组件
        $tabsComponent = amis()->Tabs()
            ->tabsMode('line')
            ->activeKey($defaultFileType)
            ->tabs($tabs)
            ->onEvent([
                'change' => [
                    'actions' => [
                        [
                            'actionType' => 'setValue',
                            'componentId' => 'file-type-store',
                            'args' => [
                                'value' => '${event.data.value}',
                            ],
                        ],
                        [
                            'actionType' => 'reload',
                            'componentId' => 'file-crud-${event.data.value}',
                            'data' => [
                                'file_type' => '${event.data.value}',
                                'page' => 1,
                            ],
                        ],
                        [
                            'actionType' => 'reload',
                            'componentId' => 'file-groups-${event.data.value}',
                            'data' => [
                                'file_type' => '${event.data.value}',
                            ],
                        ],
                    ],
                ],
            ]);

        // 创建顶层页面，包含标签导航
        $page = amis()->Page()
            ->className('file-manager-page')
            ->body($tabsComponent);

        return $page;
    }

    /**
     * 构建文件类型标签页内容
     *
     * @param string $label 标签名称
     * @param string $value 标签值（文件类型）
     * @return array 标签页配置
     */
    protected function buildFileTypeTab(string $label, string $value): array
    {
        $crudCardsId = 'file-crud-cards-' . $value;
        $crudTableId = 'file-crud-table-' . $value;
        $viewModeVar = 'viewMode_' . $value;
        $pageId = 'file-page-' . $value;
        $groupsNavId = 'file-groups-' . $value;

        // 创建卡片模式的 CRUD 组件配置
        $crudCardsConfig = $this->buildCrudCardsConfig($crudCardsId, $value, $viewModeVar, $pageId, $crudTableId);

        // 创建列表模式的 CRUD 组件配置
        $crudTableConfig = $this->buildCrudTableConfig($crudTableId, $value, $viewModeVar, $pageId, $crudCardsId);

        // 创建文件列表容器（根据视图模式显示不同的 CRUD）
        $fileListContainer = amis()->Container()
            ->className('file-manager-content')
            ->body([
                // 卡片视图（网格）
                amis()->Wrapper()
                    ->visibleOn('${' . $viewModeVar . ' === "cards" || !' . $viewModeVar . '}')
                    ->body($crudCardsConfig),
                // 列表视图（表格）
                amis()->Wrapper()
                    ->visibleOn('${' . $viewModeVar . ' === "table"}')
                    ->body($crudTableConfig),
            ]);

        // 创建分组导航组件（使用Service包装以实现分页）
        $groupsNavServiceId = $groupsNavId . '-service';
        $groupsNav = amis()->Service()
            ->id($groupsNavServiceId)
            ->api($this->getGroupsPath() . '?file_type=' . $value)
            ->body([
                amis()->PaginationWrapper()
                    ->inputName('items')
                    ->outputName('items')
                    ->perPage(10)
                    ->position('bottom')
                    ->body([
                        amis()->Nav()
                            ->id($groupsNavId)
                            ->stacked(true)
                            ->source('${items}')
                            ->itemBadge([
                                'mode' => 'tpl',
                                'text' => '${count | default:0}',
                            ])
                            ->itemActions([
                                amis()->DropdownButton()
                                    ->level('link')
                                    ->icon('fa fa-ellipsis-h')
                                    ->hideCaret(true)
                                    ->visibleOn('${group_id !== null && group_id !== "ungrouped" && group_id !== undefined}')
                                    ->buttons([
                                        amis()->Button()
                                            ->label('重命名')
                                            ->actionType('dialog')
                                            ->dialog(
                                                amis()->Dialog()
                                                    ->title('重命名分组')
                                                    ->body(
                                                        amis()->Form()
                                                            ->api($this->getRenameGroupPath())
                                                            ->onEvent([
                                                                'submitSucc' => [
                                                                    'actions' => [
                                                                        [
                                                                            'actionType' => 'reload',
                                                                            'componentId' => $groupsNavServiceId,
                                                                        ],
                                                                    ],
                                                                ],
                                                            ])
                                                            ->body([
                                                                amis()->InputText('id')->hidden(true)->value('${group_id}'),
                                                                amis()->InputText('name', '分组名称')
                                                                    ->required(true)
                                                                    ->value('${label}'),
                                                            ])
                                                    )
                                            ),
                                        amis()->Button()
                                            ->label('删除')
                                            ->level('danger')
                                            ->actionType('ajax')
                                            ->api($this->getDeleteGroupPath())
                                            ->confirmText('确定要删除该分组吗？删除后将同时删除该分组下的所有文件！')
                                            ->data(['id' => '${group_id}'])
                                            ->onEvent([
                                                'ajaxSucc' => [
                                                    'actions' => [
                                                        [
                                                            'actionType' => 'reload',
                                                            'componentId' => $groupsNavServiceId,
                                                        ],
                                                        [
                                                            'actionType' => 'reload',
                                                            'componentId' => $crudCardsId,
                                                        ],
                                                        [
                                                            'actionType' => 'reload',
                                                            'componentId' => $crudTableId,
                                                        ],
                                                    ],
                                                ],
                                            ]),
                                    ]),
                            ])
                            ->onEvent([
                                'click' => [
                                    'actions' => [
                                        [
                                            'actionType' => 'setValue',
                                            'componentId' => $pageId,
                                            'args' => [
                                                'value' => [
                                                    'currentGroupId' => '${event.data.item.group_id}',
                                                ],
                                            ],
                                        ],
                                        [
                                            'actionType' => 'reload',
                                            'componentId' => $crudCardsId,
                                            'data' => [
                                                'group_id' => '${event.data.item.group_id}',
                                                'file_type' => $value,
                                                'page' => 1,
                                            ],
                                        ],
                                        [
                                            'actionType' => 'reload',
                                            'componentId' => $crudTableId,
                                            'data' => [
                                                'group_id' => '${event.data.item.group_id}',
                                                'file_type' => $value,
                                                'page' => 1,
                                            ],
                                        ],
                                    ],
                                ],
                            ]),
                ]),
            ]);

        // 创建添加分组按钮
        $addGroupButton = amis()->Button()
            ->label('添加分组')
            ->icon('fa fa-plus')
            ->level('link')
            ->block(true)
            ->className('mt-2')
            ->actionType('dialog')
            ->dialog(
                amis()->Dialog()
                    ->title('添加分组')
                    ->body(
                        amis()->Form()
                            ->api($this->getCreateGroupPath())
                            ->onEvent([
                                'submitSucc' => [
                                    'actions' => [
                                        [
                                            'actionType' => 'reload',
                                            'componentId' => $groupsNavServiceId,
                                        ],
                                    ],
                                ],
                            ])
                            ->body([
                                amis()->InputText('name', '分组名称')->required(true),
                                amis()->InputText('file_type')->hidden(true)->value($value),
                            ])
                    )
            );

        // 创建标签页内的页面
        $tabPage = amis()->Page()
            ->id($pageId)
            ->className('file-manager-tab-page')
            ->data([
                $viewModeVar => 'cards', // 默认卡片模式
                'currentGroupId' => null, // 当前选中的分组ID
            ])
            ->asideResizor(false)
            ->asideMinWidth(320)
            ->asideMaxWidth(400)
            ->wrapperCustomStyle([
                '.cxd-Page-aside' => ['width' => '300px']
            ])
            ->aside([
                amis()->Wrapper()
                    ->className('d-flex flex-column h-100')
                    ->body([
                        amis()->Wrapper()
                            ->className('flex-1 overflow-auto')
                            ->body([
                                $groupsNav,
                            ]),
                        amis()->Wrapper()
                            ->className('mt-auto')
                            ->body([
                                $addGroupButton,
                            ]),
                    ]),
            ])
            ->body($fileListContainer);

        return [
            'title' => $label,
            'hash' => $value,
            'body' => $tabPage,
        ];
    }

    /**
     * 构建卡片模式 CRUD 配置
     *
     * @param string $crudId CRUD 组件 ID
     * @param string $fileType 文件类型
     * @param string $viewModeVar 视图模式变量名
     * @param string $pageId 页面 ID
     * @return array CRUD 配置数组
     */
    protected function buildCrudCardsConfig(string $crudId, string $fileType, string $viewModeVar, string $pageId, ?string $crudTableId = null): array
    {
        $crud = $this->baseCRUD()
            ->id($crudId)
            ->mode('cards')
            ->checkOnItemClick(true)
            ->columnsCount(6)
            ->perPage(18)
            ->api($this->getListGetDataPath())
            ->syncLocation(false)
            ->defaultParams([
                'file_type' => $fileType,
            ])
            ->primaryField('id')
            ->card([
                'className' => 'file-card',
                'body' => [
                    // 文件预览区域
                    amis()->Container()
                        ->className('file-preview-container')
                        ->body([
                            // 图片预览 - 使用 Image 组件自带预览功能
                            amis()->Image()
                                ->src('${url}')
                                ->originalSrc('${url}')
                                ->thumbClassName('file-thumbnail')
                                ->thumbMode('cover')
                                ->enlargeAble(true)
                                ->imageMode('thumb')
                                ->visibleOn('${file_type === "image"}'),
                            // 视频播放 - 点击图标弹出播放对话框
                            amis()->Action()
                                ->actionType('dialog')
                                ->visibleOn('${file_type === "video"}')
                                ->dialog(
                                    amis()->Dialog()
                                        ->title('${origin_name}')
                                        ->body(
                                            amis()->Video()
                                                ->src('${url}')
                                                ->controls(['rates', 'play', 'time', 'process', 'volume'])
                                        )
                                )
                                ->body([
                                    amis()->Tpl()
                                        ->className('file-preview video-preview')
                                        ->tpl('<div class="file-icon video-icon" style="cursor: pointer; width: 100%; height: 100%; display: flex; align-items: center; justify-content: center;">'
                                            . '<i class="fa fa-play-circle" style="font-size: 48px; color: #666;"></i>'
                                            . '</div>'),
                                ]),
                            // 音频播放 - 点击图标弹出播放对话框
                            amis()->Action()
                                ->actionType('dialog')
                                ->visibleOn('${file_type === "audio"}')
                                ->dialog(
                                    amis()->Dialog()
                                        ->title('${origin_name}')
                                        ->body(
                                            amis()->Audio()
                                                ->src('${url}')
                                                ->controls(['rates', 'play', 'time', 'process', 'volume'])
                                        )
                                )
                                ->body([
                                    amis()->Tpl()
                                        ->className('file-preview audio-preview')
                                        ->tpl('<div class="file-icon audio-icon" style="cursor: pointer; width: 100%; height: 100%; display: flex; align-items: center; justify-content: center;">'
                                            . '<i class="fa fa-music" style="font-size: 48px; color: #666;"></i>'
                                            . '</div>'),
                                ]),
                            // 其他文件类型
                            amis()->Tpl()
                                ->className('file-preview')
                                ->visibleOn('${file_type !== "image" && file_type !== "video" && file_type !== "audio"}')
                                ->tpl('<div class="file-icon file-icon-${mime_type | replace:/[\/\.]/g:\'-\'}">'
                                    . '<i class="fa fa-file"></i>'
                                    . '</div>'),
                        ]),
                    // 文件名
                    amis()->Tpl()
                        ->className('file-name')
                        ->tpl('${origin_name | truncate:20}')
                        ->tooltip('${origin_name}'),
                    // 文件大小和时间
                    amis()->Tpl()
                        ->className('file-meta')
                        ->tpl('<span class="file-size">${file_size | round:2} KB</span>'
                            . '<span class="file-date">${created_at | date:MM-DD}</span>'),
                ],
                'actions' => [
                    // 下载按钮
                    amis()->Button()
                        ->level('link')
                        ->icon('fa fa-download')
                        ->tooltip('下载')
                        ->actionType('ajax')
                        ->api([
                            'method' => 'post',
                            'url' => $this->getDownloadPath() . '?id=${id}',
                            'responseType' => 'blob',
                        ]),
                    // 重命名按钮
                    amis()->Button()
                        ->level('link')
                        ->icon('fa fa-edit')
                        ->tooltip('重命名')
                        ->actionType('dialog')
                        ->dialog(
                            amis()->Dialog()
                                ->title('重命名文件')
                                ->body(
                                    amis()->Form()
                                        ->api($this->getRenamePath())
                                        ->body([
                                            amis()->InputText('id')->hidden(true)->value('${id}'),
                                            amis()->InputText('name', '文件名')
                                                ->required(true)
                                                ->value('${origin_name}'),
                                        ])
                                )
                        ),
                    // 删除按钮
                    amis()->Button()
                        ->level('link')
                        ->icon('fa fa-trash')
                        ->tooltip('删除')
                        ->actionType('ajax')
                        ->api($this->getDeletePath())
                        ->confirmText('确定要删除这个文件吗？')
                        ->data(['ids' => '${id}']),
                ],
            ])
            ->bulkActions([
                // 批量删除
                $this->bulkDeleteButton(),
                // 批量移动
                amis()->Button()
                    ->label('移动')
                    ->icon('fa fa-folder')
                    ->actionType('dialog')
                    ->dialog(
                        amis()->Dialog()
                            ->title('移动到分组')
                            ->body(
                                amis()->Form()
                                    ->api($this->getMovePath())
                                    ->body([
                                        amis()->InputText('ids')->hidden(true)->value('${ids|json}'),
                                        amis()->Select('group_id', '目标分组')
                                            ->source($this->getGroupsPath())
                                            ->labelField('label')
                                            ->valueField('group_id')
                                            ->clearable(true)
                                            ->placeholder('选择分组（留空为未分组）'),
                                    ])
                            )
                    ),
            ])
            ->headerToolbar([
                // 左侧组：全选、删除、移动、本地上传
                [
                    'type' => 'bulkActions',
                    'align' => 'left',
                ],
                // 本地上传按钮 - 弹窗上传，支持拖拽
                amis()->Button()
                    ->label('本地上传')
                    ->icon('fa fa-upload')
                    ->level('primary')
                    ->actionType('dialog')
                    ->dialog(
                        amis()->Dialog()
                            ->title('上传文件')
                            ->body([
                                amis()->InputFile('upload_file')
                                    ->label('')
                                    ->accept($this->getAcceptForFileType($fileType))
                                    ->multiple(true)
                                    ->drag(true)
                                    ->autoUpload(true)
                                    ->receiver($this->getUploadPath() . '?group_id=${currentGroupId}&file_type=' . $fileType)
                                    ->className('hide-file-list')
                                    ->onEvent([
                                        'success' => [
                                            'actions' => array_filter([
                                                [
                                                    'actionType' => 'setValue',
                                                    'componentId' => $pageId,
                                                    'args' => [
                                                        'value' => [
                                                            'upload_file' => null,
                                                        ],
                                                    ],
                                                ],
                                                [
                                                    'actionType' => 'reload',
                                                    'componentId' => $crudId,
                                                ],
                                                $crudTableId ? [
                                                    'actionType' => 'reload',
                                                    'componentId' => $crudTableId,
                                                ] : null,
                                                [
                                                    'actionType' => 'closeDialog',
                                                ],
                                            ]),
                                        ],
                                        'error' => [
                                            'actions' => [
                                                [
                                                    'actionType' => 'setValue',
                                                    'componentId' => $pageId,
                                                    'args' => [
                                                        'value' => [
                                                            'upload_file' => null,
                                                        ],
                                                    ],
                                                ],
                                            ],
                                        ],
                                    ]),
                            ])
                    ),
                // 右侧组：搜索、文件来源选择、刷新、视图切换
                amis()->ButtonGroup()
                    ->align('right')
                    ->buttons([
                        amis()->Button()
                            ->icon('fa fa-th')
                            ->tooltip('网格视图')
                            ->activeOn('${' . $viewModeVar . ' === "cards"}')
                            ->onEvent([
                                'click' => [
                                    'actions' => [
                                        [
                                            'actionType' => 'setValue',
                                            'componentId' => $pageId,
                                            'args' => [
                                                'value' => [
                                                    $viewModeVar => 'cards',
                                                ],
                                            ],
                                        ],
                                    ],
                                ],
                            ]),
                        amis()->Button()
                            ->icon('fa fa-list')
                            ->tooltip('列表视图')
                            ->activeOn('${' . $viewModeVar . ' === "table"}')
                            ->onEvent([
                                'click' => [
                                    'actions' => [
                                        [
                                            'actionType' => 'setValue',
                                            'componentId' => $pageId,
                                            'args' => [
                                                'value' => [
                                                    $viewModeVar => 'table',
                                                ],
                                            ],
                                        ],
                                    ],
                                ],
                            ]),
                    ]),
                // 搜索框
                amis()->InputText('origin_name')
                    ->placeholder('请输入名称')
                    ->align('right')
                    ->clearable(true)
                    ->addOn(
                        amis()->Button()
                            ->label('搜索')
                            ->actionType('submit')
                            ->icon('fa fa-search')
                    ),
                // 文件来源选择
                amis()->Select('storage_mode')
                    ->placeholder('请选择文件来源')
                    ->align('right')
                    ->clearable(true)
                    ->options(SystemFile::STORAGE_MODE)
                    ->onEvent([
                        'change' => [
                            'actions' => [
                                [
                                    'actionType' => 'reload',
                                    'componentId' => $crudId,
                                ],
                            ],
                        ],
                    ]),
                amis()->Button()
                    ->icon('fa fa-refresh')
                    ->tooltip('刷新')
                    ->align('right')
                    ->actionType('reload')
                    ->target($crudId),
            ])
            ->footerToolbar([
                'statistics',
                'pagination',
            ]);

        return $crud->toArray();
    }

    /**
     * 构建列表模式 CRUD 配置
     *
     * @param string $crudId CRUD 组件 ID
     * @param string $fileType 文件类型
     * @param string $viewModeVar 视图模式变量名
     * @param string $pageId 页面 ID
     * @return array CRUD 配置数组
     */
    protected function buildCrudTableConfig(string $crudId, string $fileType, string $viewModeVar, string $pageId, ?string $crudCardsId = null): array
    {
        $crud = $this->baseCRUD()
            ->id($crudId)
            ->mode('table')
            ->checkOnItemClick(true)
            ->api($this->getListGetDataPath())
            ->syncLocation(false)
            ->defaultParams([
                'file_type' => $fileType,
            ])
            ->primaryField('id')
            ->columns([
                amis()->TableColumn('id', 'ID')->width(80),
                amis()->TableColumn('origin_name', '文件名')
                    ->type('tpl')
                    ->tpl('<% if (data.file_type === "image") { %>'
                        . '<img src="<%- data.url %>" style="width: 40px; height: 40px; object-fit: cover; border-radius: 4px; margin-right: 8px;" />'
                        . '<% } else if (data.file_type === "video") { %>'
                        . '<div style="width: 40px; height: 40px; display: flex; align-items: center; justify-content: center; background: #f5f5f5; border-radius: 4px; margin-right: 8px;">'
                        . '<i class="fa fa-play-circle" style="color: #999;"></i>'
                        . '</div>'
                        . '<% } else if (data.file_type === "audio") { %>'
                        . '<div style="width: 40px; height: 40px; display: flex; align-items: center; justify-content: center; background: #f5f5f5; border-radius: 4px; margin-right: 8px;">'
                        . '<i class="fa fa-music" style="color: #999;"></i>'
                        . '</div>'
                        . '<% } else { %>'
                        . '<div style="width: 40px; height: 40px; display: flex; align-items: center; justify-content: center; background: #f5f5f5; border-radius: 4px; margin-right: 8px;">'
                        . '<i class="fa fa-file" style="color: #999;"></i>'
                        . '</div>'
                        . '<% } %>'
                        . '<span><%- data.origin_name %></span>'),
                amis()->TableColumn('file_size', '大小')
                    ->type('tpl')
                    ->tpl('${file_size | round:2} KB')
                    ->width(200),
                amis()->TableColumn('storage_mode', '来源')
                    ->type('mapping')
                    ->map(SystemFile::STORAGE_MODE)
                    ->width(100),
                amis()->TableColumn('created_at', '创建时间')
                    ->type('datetime')
                    ->width(180),
            ])
            ->bulkActions([
                $this->bulkDeleteButton(),
                amis()->Button()
                    ->label('移动')
                    ->icon('fa fa-folder')
                    ->actionType('dialog')
                    ->dialog(
                        amis()->Dialog()
                            ->title('移动到分组')
                            ->body(
                                amis()->Form()
                                    ->api($this->getMovePath())
                                    ->body([
                                        amis()->InputText('ids')->hidden(true)->value('${ids|json}'),
                                        amis()->Select('group_id', '目标分组')
                                            ->source($this->getGroupsPath())
                                            ->labelField('label')
                                            ->valueField('group_id')
                                            ->clearable(true)
                                            ->placeholder('选择分组（留空为未分组）'),
                                    ])
                            )
                    ),
            ])
            ->headerToolbar([
                // 左侧组：全选、删除、移动、本地上传
                [
                    'type' => 'bulkActions',
                    'align' => 'left',
                ],
                // 本地上传按钮 - 弹窗上传，支持拖拽
                amis()->Button()
                    ->label('本地上传')
                    ->icon('fa fa-upload')
                    ->level('primary')
                    ->actionType('dialog')
                    ->dialog(
                        amis()->Dialog()
                            ->title('上传文件')
                            ->body([
                                amis()->InputFile('upload_file')
                                    ->label('')
                                    ->accept($this->getAcceptForFileType($fileType))
                                    ->multiple(true)
                                    ->drag(true)
                                    ->autoUpload(true)
                                    ->receiver($this->getUploadPath() . '?group_id=${currentGroupId}&file_type=' . $fileType)
                                    ->className('hide-file-list')
                                    ->onEvent([
                                        'success' => [
                                            'actions' => array_filter([
                                                [
                                                    'actionType' => 'setValue',
                                                    'componentId' => $pageId,
                                                    'args' => [
                                                        'value' => [
                                                            'upload_file' => null,
                                                        ],
                                                    ],
                                                ],
                                                [
                                                    'actionType' => 'reload',
                                                    'componentId' => $crudId,
                                                ],
                                                $crudCardsId ? [
                                                    'actionType' => 'reload',
                                                    'componentId' => $crudCardsId,
                                                ] : null,
                                                [
                                                    'actionType' => 'closeDialog',
                                                ],
                                            ]),
                                        ],
                                        'error' => [
                                            'actions' => [
                                                [
                                                    'actionType' => 'setValue',
                                                    'componentId' => $pageId,
                                                    'args' => [
                                                        'value' => [
                                                            'upload_file' => null,
                                                        ],
                                                    ],
                                                ],
                                            ],
                                        ],
                                    ]),
                            ])
                    ),
                // 右侧组：搜索、文件来源选择、刷新、视图切换
                amis()->ButtonGroup()
                    ->align('right')
                    ->buttons([
                        amis()->Button()
                            ->icon('fa fa-th')
                            ->tooltip('网格视图')
                            ->activeOn('${' . $viewModeVar . ' === "cards"}')
                            ->onEvent([
                                'click' => [
                                    'actions' => [
                                        [
                                            'actionType' => 'setValue',
                                            'componentId' => $pageId,
                                            'args' => [
                                                'value' => [
                                                    $viewModeVar => 'cards',
                                                ],
                                            ],
                                        ],
                                    ],
                                ],
                            ]),
                        amis()->Button()
                            ->icon('fa fa-list')
                            ->tooltip('列表视图')
                            ->activeOn('${' . $viewModeVar . ' === "table"}')
                            ->onEvent([
                                'click' => [
                                    'actions' => [
                                        [
                                            'actionType' => 'setValue',
                                            'componentId' => $pageId,
                                            'args' => [
                                                'value' => [
                                                    $viewModeVar => 'table',
                                                ],
                                            ],
                                        ],
                                    ],
                                ],
                            ]),
                    ]),
                amis()->InputText('origin_name')
                    ->placeholder('请输入名称')
                    ->align('right')
                    ->clearable(true)
                    ->addOn(
                        amis()->Button()
                            ->label('搜索')
                            ->actionType('submit')
                            ->icon('fa fa-search')
                    ),
                amis()->Select('storage_mode')
                    ->placeholder('请选择文件来源')
                    ->align('right')
                    ->clearable(true)
                    ->options(SystemFile::STORAGE_MODE)
                    ->onEvent([
                        'change' => [
                            'actions' => [
                                [
                                    'actionType' => 'reload',
                                    'componentId' => $crudId,
                                ],
                            ],
                        ],
                    ]),
                amis()->Button()
                    ->icon('fa fa-refresh')
                    ->tooltip('刷新')
                    ->align('right')
                    ->actionType('reload')
                    ->target($crudId),
            ])
            ->footerToolbar([
                'statistics',
                'pagination',
            ]);

        return $crud->toArray();
    }

    /**
     * 获取文件分组列表
     *
     * @return Response 返回分组列表
     */
    public function groups(): Response
    {
        $request = request();
        $fileType = $request ? $request->input('file_type') : null;
        $groups = $this->service->getGroups($fileType);

        // 转换数据格式，确保字段名符合 Nav 组件要求
        // 移除 to 字段，使用 group_id 用于事件处理
        $links = array_map(function ($group) {
            $item = [
                'label' => $group['name'],
                'group_id' => $group['id'],
                'count' => $group['count'],
                'icon' => 'fa fa-folder', // 统一使用文件夹图标
            ];

            // 如果是"全部"分组（id为null），设置active为true
            if ($group['id'] === null) {
                $item['active'] = true;
            }

            return $item;
        }, $groups);

        return $this->response()->success($links);
    }

    /**
     * 文件上传
     *
     * @param Request $request
     * @return Response
     */
    public function upload(Request $request): Response
    {
        $file = $request->file('file');
        if (!$file) {
            return $this->response()->fail('请选择要上传的文件');
        }

        try {
            $fileInfo = $this->systemFileUpload($file);
            $groupId = $request->input('group_id');

            // 如果有分组，更新文件分组
            if ($groupId) {
                $this->service->moveToGroup($fileInfo['id'], $groupId);
            }

            return $this->response()->success($fileInfo, '上传成功');
        } catch (\Throwable $e) {
            return $this->response()->fail($e->getMessage());
        }
    }

    /**
     * 文件下载
     *
     * @param Request $request
     * @return Response
     */
    public function download(Request $request): Response
    {
        $id = $request->input('id');
        if (!$id) {
            return $this->response()->fail('文件ID不能为空');
        }

        $file = SystemFile::baseQuery()->find($id);
        if (!$file) {
            return $this->response()->fail('文件不存在');
        }
        

        try {
            $storage = \warm\framework\filesystem\facade\Storage::disk('public');
            
            // 获取文件的完整路径
            // public 磁盘的根目录是 base_path('public')
            $filePath = base_path('public/' . $file->storage_path);
            
            // 检查文件是否存在
            if (!file_exists($filePath)) {
                return $this->response()->fail('文件不存在');
            }

            // 使用 webman 的 download 方法
            return response()->download(\warm\framework\filesystem\facade\Storage::get($file->storage_path), rawurlencode($file->origin_name));
        } catch (\Throwable $e) {
            return $this->response()->fail('文件下载失败：' . $e->getMessage());
        }
    }

    /**
     * 文件重命名
     *
     * @param Request $request
     * @return Response
     */
    public function rename(Request $request): Response
    {
        $id = $request->input('id');
        $name = $request->input('name');

        if (!$id || !$name) {
            return $this->response()->fail('参数不完整');
        }

        $result = $this->service->rename($id, $name);
        return $this->autoResponse($result, '重命名');
    }

    /**
     * 文件移动到分组
     *
     * @param Request $request
     * @return Response
     */
    public function move(Request $request): Response
    {
        $ids = $request->input('ids');
        $groupId = $request->input('group_id');

        if (!$ids) {
            // 尝试从 selectedItems 获取（Amis 批量操作时）
            $ids = $request->input('selectedItems');
        }

        if (!$ids) {
            return $this->response()->fail('请选择要移动的文件');
        }

        // 如果是 JSON 字符串，解析为数组
        if (is_string($ids) && (str_starts_with($ids, '[') || str_starts_with($ids, '"'))) {
            $ids = json_decode($ids, true);
        }

        // 如果是逗号分隔的字符串，转换为数组
        if (is_string($ids) && str_contains($ids, ',')) {
            $ids = explode(',', $ids);
        }

        // 确保是数组
        if (!is_array($ids)) {
            $ids = [$ids];
        }

        $result = $this->service->moveToGroup($ids, $groupId);
        return $this->autoResponse($result, '移动');
    }

    /**
     * 创建分组
     *
     * @param Request $request
     * @return Response
     */
    public function createGroup(Request $request): Response
    {
        $name = $request->input('name');
        $fileType = $request->input('file_type');
        if (!$name) {
            return $this->response()->fail('分组名称不能为空');
        }

        $result = $this->service->createGroup($name, $fileType);
        return $this->autoResponse($result, '创建分组');
    }

    /**
     * 删除分组
     *
     * @param Request $request
     * @return Response
     */
    public function deleteGroup(Request $request): Response
    {
        $groupId = $request->input('id');
        if (!$groupId) {
            return $this->response()->fail('分组ID不能为空');
        }

        $result = $this->service->deleteGroup($groupId);
        return $this->autoResponse($result, '删除分组');
    }

    /**
     * 重命名分组
     *
     * @param Request $request
     * @return Response
     */
    public function renameGroup(Request $request): Response
    {
        $groupId = $request->input('id');
        $newName = $request->input('name');

        if (!$groupId) {
            return $this->response()->fail('分组ID不能为空');
        }

        if (!$newName) {
            return $this->response()->fail('分组名称不能为空');
        }

        $result = $this->service->renameGroup($groupId, $newName);
        return $this->autoResponse($result, '重命名分组');
    }

    /**
     * 获取上传路径
     */
    protected function getUploadPath(): string
    {
        return admin_url($this->queryPath . '/upload');
    }

    /**
     * 获取下载路径
     */
    protected function getDownloadPath(): string
    {
        return admin_url($this->queryPath . '/download');
    }

    /**
     * 获取重命名路径
     */
    protected function getRenamePath(): string
    {
        return 'post:' . admin_url($this->queryPath . '/rename');
    }

    /**
     * 获取移动路径
     */
    protected function getMovePath(): string
    {
        return 'post:' . admin_url($this->queryPath . '/move');
    }

    /**
     * 获取分组列表路径
     */
    protected function getGroupsPath(): string
    {
        return admin_url($this->queryPath . '/groups');
    }

    /**
     * 获取创建分组路径
     */
    protected function getCreateGroupPath(): string
    {
        return 'post:' . admin_url($this->queryPath . '/createGroup');
    }

    /**
     * 获取重命名分组路径
     */
    protected function getRenameGroupPath(): string
    {
        return 'post:' . admin_url($this->queryPath . '/renameGroup');
    }

    /**
     * 获取删除分组路径
     */
    protected function getDeleteGroupPath(): string
    {
        return 'delete:' . admin_url($this->queryPath . '/deleteGroup');
    }

    /**
     * 文件表单页面
     *
     * 定义文件管理表单结构
     *
     * @return Form 返回文件表单
     */
    /**
     * 根据文件类型获取 accept 值
     *
     * @param string $fileType 文件类型 (image, video, audio, file)
     * @return string accept 值
     */
    protected function getAcceptForFileType(string $fileType): string
    {
        return match ($fileType) {
            'image' => 'image/*',
            'video' => 'video/*',
            'audio' => 'audio/*',
            'file' => '.pdf,.doc,.docx,.xls,.xlsx,.txt,.zip,.rar,.7z,.tar,.gz',
            default => '*',
        };
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
