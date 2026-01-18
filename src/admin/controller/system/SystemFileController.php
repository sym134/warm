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
     * - 按文件类型筛选（图片、视频、文件）
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

        // 创建顶层页面，包含标签导航
        $page = amis()->Page()
            ->className('file-manager-page')
            ->body([
                [
                    'type' => 'tabs',
                    'tabsMode' => 'line',
                    'activeKey' => $defaultFileType,
                    'tabs' => $tabs,
                    'onEvent' => [
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
                    ],
                ],
            ]);

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
        $crudCardsConfig = $this->buildCrudCardsConfig($crudCardsId, $value, $viewModeVar, $pageId);
        
        // 创建列表模式的 CRUD 组件配置
        $crudTableConfig = $this->buildCrudTableConfig($crudTableId, $value, $viewModeVar, $pageId);
        
        // 创建文件列表容器（根据视图模式显示不同的 CRUD）
        $fileListContainer = [
            'type' => 'container',
            'className' => 'file-manager-content',
            'body' => [
                // 卡片视图（网格）
                [
                    'type' => 'wrapper',
                    'visibleOn' => '${' . $viewModeVar . ' === "cards" || !' . $viewModeVar . '}',
                    'body' => $crudCardsConfig,
                ],
                // 列表视图（表格）
                [
                    'type' => 'wrapper',
                    'visibleOn' => '${' . $viewModeVar . ' === "table"}',
                    'body' => $crudTableConfig,
                ],
            ],
        ];
        
        return [
            'title' => $label,
            'hash' => $value,
            'body' => [
                'type' => 'page',
                'id' => $pageId,
                'className' => 'file-manager-tab-page',
                'data' => [
                    $viewModeVar => 'cards', // 默认卡片模式
                ],
                'asideResizor' => true,
                'asideMinWidth' => 200,
                'asideMaxWidth' => 300,
                'aside' => [
                    [
                        'type' => 'nav',
                        'id' => $groupsNavId,
                        'stacked' => true,
                        'source' => $this->getGroupsPath() . '?file_type=' . $value,
                        'links' => [
                            [
                                'label' => '${name}',
                                'to' => '${to}',
                                'badge' => '${count}',
                            ],
                        ],
                        'itemBadge' => [
                            'mode' => 'tpl',
                            'text' => '${count | default:0}',
                        ],
                    ],
                    [
                        'type' => 'button',
                        'label' => '添加分组',
                        'icon' => 'fa fa-plus',
                        'level' => 'link',
                        'block' => true,
                        'className' => 'mt-2',
                        'actionType' => 'dialog',
                        'dialog' => [
                            'title' => '添加分组',
                            'body' => [
                                'type' => 'form',
                                'api' => $this->getCreateGroupPath(),
                                'onEvent' => [
                                    'submitSucc' => [
                                        'actions' => [
                                            [
                                                'actionType' => 'reload',
                                                'componentId' => $groupsNavId,
                                            ],
                                        ],
                                    ],
                                ],
                                'body' => [
                                    [
                                        'type' => 'input-text',
                                        'name' => 'name',
                                        'label' => '分组名称',
                                        'required' => true,
                                    ],
                                    [
                                        'type' => 'input-text',
                                        'name' => 'file_type',
                                        'hidden' => true,
                                        'value' => $value,
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
                'body' => $fileListContainer,
            ],
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
    protected function buildCrudCardsConfig(string $crudId, string $fileType, string $viewModeVar, string $pageId): array
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
            ->card([
                'className' => 'file-card',
                'body' => [
                    // 文件预览区域
                    [
                        'type' => 'container',
                        'className' => 'file-preview-container',
                        'body' => [
                            [
                                'type' => 'tpl',
                                'className' => 'file-preview',
                                'tpl' => '<% if (file_type === "image") { %>'
                                    . '<img src="${url}" class="file-thumbnail" />'
                                    . '<% } else if (file_type === "video") { %>'
                                    . '<div class="file-icon video-icon">'
                                    . '<i class="fa fa-play-circle"></i>'
                                    . '</div>'
                                    . '<% } else { %>'
                                    . '<div class="file-icon file-icon-${mime_type | replace:/[\/\.]/g:\'-\'}">'
                                    . '<i class="fa fa-file"></i>'
                                    . '</div>'
                                    . '<% } %>',
                            ],
                        ],
                    ],
                    // 文件名
                    [
                        'type' => 'tpl',
                        'className' => 'file-name',
                        'tpl' => '${origin_name | truncate:20}',
                        'tooltip' => '${origin_name}',
                    ],
                    // 文件大小和时间
                    [
                        'type' => 'tpl',
                        'className' => 'file-meta',
                        'tpl' => '<span class="file-size">${file_size | round:2} KB</span>'
                            . '<span class="file-date">${created_at | date:MM-DD}</span>',
                    ],
                ],
                'actions' => [
                    // 下载按钮
                    [
                        'type' => 'button',
                        'level' => 'link',
                        'icon' => 'fa fa-download',
                        'tooltip' => '下载',
                        'actionType' => 'url',
                        'url' => $this->getDownloadPath() . '?id=${id}',
                    ],
                    // 重命名按钮
                    [
                        'type' => 'button',
                        'level' => 'link',
                        'icon' => 'fa fa-edit',
                        'tooltip' => '重命名',
                        'actionType' => 'dialog',
                        'dialog' => [
                            'title' => '重命名文件',
                            'body' => [
                                'type' => 'form',
                                'api' => $this->getRenamePath(),
                                'body' => [
                                    [
                                        'type' => 'input-text',
                                        'name' => 'id',
                                        'hidden' => true,
                                        'value' => '${id}',
                                    ],
                                    [
                                        'type' => 'input-text',
                                        'name' => 'name',
                                        'label' => '文件名',
                                        'required' => true,
                                        'value' => '${origin_name}',
                                    ],
                                ],
                            ],
                        ],
                    ],
                    // 删除按钮
                    [
                        'type' => 'button',
                        'level' => 'link',
                        'icon' => 'fa fa-trash',
                        'tooltip' => '删除',
                        'actionType' => 'ajax',
                        'api' => $this->getDeletePath(),
                        'confirmText' => '确定要删除这个文件吗？',
                        'data' => ['ids' => '${id}'],
                    ],
                ],
            ])
            ->bulkActions([
                // 批量删除
                $this->bulkDeleteButton(),
                // 批量移动
                [
                    'type' => 'button',
                    'label' => '移动',
                    'icon' => 'fa fa-folder',
                    'actionType' => 'dialog',
                    'dialog' => [
                        'title' => '移动到分组',
                        'body' => [
                            'type' => 'form',
                            'api' => $this->getMovePath(),
                            'body' => [
                                [
                                    'type' => 'input-text',
                                    'name' => 'ids',
                                    'hidden' => true,
                                    'value' => '${ids|json}',
                                ],
                                [
                                    'type' => 'select',
                                    'name' => 'group_id',
                                    'label' => '目标分组',
                                    'source' => $this->getGroupsPath(),
                                    'labelField' => 'name',
                                    'valueField' => 'id',
                                    'clearable' => true,
                                    'placeholder' => '选择分组（留空为未分组）',
                                ],
                            ],
                        ],
                    ],
                ],
            ])
            ->headerToolbar([
                // 本地上传按钮
                [
                    'type' => 'button',
                    'label' => '本地上传',
                    'icon' => 'fa fa-upload',
                    'level' => 'primary',
                    'actionType' => 'dialog',
                    'dialog' => [
                        'title' => '上传文件',
                        'size' => 'lg',
                        'body' => [
                            'type' => 'form',
                            'api' => $this->getUploadPath(),
                            'body' => [
                                [
                                    'type' => 'input-file',
                                    'name' => 'file',
                                    'label' => '选择文件',
                                    'required' => true,
                                    'accept' => 'image/*,video/*,.pdf,.doc,.docx,.xls,.xlsx,.txt,.zip,.rar',
                                    'multiple' => true,
                                    'autoUpload' => false,
                                ],
                                [
                                    'type' => 'input-text',
                                    'name' => 'group_id',
                                    'label' => '分组',
                                    'source' => $this->getGroupsPath(),
                                    'clearable' => true,
                                    'description' => '选择文件所属分组（可选）',
                                ],
                            ],
                        ],
                    ],
                ],
                // 删除按钮
                [
                    'type' => 'button',
                    'label' => '删除',
                    'icon' => 'fa fa-trash',
                    'level' => 'danger',
                    'hiddenOn' => '!${__super.__super.selectedItems || selectedItems}',
                ],
                // 移动按钮
                [
                    'type' => 'button',
                    'label' => '移动',
                    'icon' => 'fa fa-folder',
                    'hiddenOn' => '!${__super.__super.selectedItems || selectedItems}',
                ],
                // 视图切换（列表/网格）
                [
                    'type' => 'button-group',
                    'buttons' => [
                        [
                            'type' => 'button',
                            'icon' => 'fa fa-th',
                            'tooltip' => '网格视图',
                            'activeOn' => '${' . $viewModeVar . ' === "cards"}',
                            'onEvent' => [
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
                            ],
                        ],
                        [
                            'type' => 'button',
                            'icon' => 'fa fa-list',
                            'tooltip' => '列表视图',
                            'activeOn' => '${' . $viewModeVar . ' === "table"}',
                            'onEvent' => [
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
                            ],
                        ],
                    ],
                ],
                // 搜索框
                [
                    'type' => 'input-text',
                    'name' => 'origin_name',
                    'placeholder' => '请输入名称',
                    'clearable' => true,
                    'addOn' => [
                        'type' => 'button',
                        'label' => '搜索',
                        'actionType' => 'submit',
                        'icon' => 'fa fa-search',
                    ],
                ],
                // 文件来源选择
                [
                    'type' => 'select',
                    'name' => 'storage_mode',
                    'placeholder' => '请选择文件来源',
                    'clearable' => true,
                    'options' => SystemFile::STORAGE_MODE,
                            'onEvent' => [
                                'change' => [
                                    'actions' => [
                                        [
                                            'actionType' => 'reload',
                                            'componentId' => $crudId,
                                        ],
                                    ],
                                ],
                            ],
                ],
                // 刷新按钮
                [
                    'type' => 'button',
                    'icon' => 'fa fa-refresh',
                    'tooltip' => '刷新',
                    'actionType' => 'reload',
                    'target' => $crudId,
                ],
            ])
            ->footerToolbar([
                'statistics',
                [
                    'type' => 'checkbox',
                    'label' => '当页全选',
                    'onEvent' => [
                        'change' => [
                            'actions' => [
                                [
                                    'actionType' => 'toggleAllSelections',
                                    'componentId' => $crudId,
                                ],
                            ],
                        ],
                    ],
                ],
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
    protected function buildCrudTableConfig(string $crudId, string $fileType, string $viewModeVar, string $pageId): array
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
            ->columns([
                [
                    'name' => 'id',
                    'label' => 'ID',
                    'width' => 80,
                ],
                [
                    'name' => 'origin_name',
                    'label' => '文件名',
                    'type' => 'tpl',
                    'tpl' => '<% if (data.file_type === "image") { %>'
                        . '<img src="<%- data.url %>" style="width: 40px; height: 40px; object-fit: cover; border-radius: 4px; margin-right: 8px;" />'
                        . '<% } else if (data.file_type === "video") { %>'
                        . '<div style="width: 40px; height: 40px; display: flex; align-items: center; justify-content: center; background: #f5f5f5; border-radius: 4px; margin-right: 8px;">'
                        . '<i class="fa fa-play-circle" style="color: #999;"></i>'
                        . '</div>'
                        . '<% } else { %>'
                        . '<div style="width: 40px; height: 40px; display: flex; align-items: center; justify-content: center; background: #f5f5f5; border-radius: 4px; margin-right: 8px;">'
                        . '<i class="fa fa-file" style="color: #999;"></i>'
                        . '</div>'
                        . '<% } %>'
                        . '<span><%- data.origin_name %></span>',
                ],
                [
                    'name' => 'file_size',
                    'label' => '大小',
                    'type' => 'tpl',
                    'tpl' => '${file_size | round:2} KB',
                    'width' => 100,
                ],
                [
                    'name' => 'storage_mode',
                    'label' => '来源',
                    'type' => 'mapping',
                    'map' => SystemFile::STORAGE_MODE,
                    'width' => 100,
                ],
                [
                    'name' => 'created_at',
                    'label' => '创建时间',
                    'type' => 'datetime',
                    'width' => 180,
                ],
            ])
            ->bulkActions([
                $this->bulkDeleteButton(),
                [
                    'type' => 'button',
                    'label' => '移动',
                    'icon' => 'fa fa-folder',
                    'actionType' => 'dialog',
                    'dialog' => [
                        'title' => '移动到分组',
                        'body' => [
                            'type' => 'form',
                            'api' => $this->getMovePath(),
                            'body' => [
                                [
                                    'type' => 'input-text',
                                    'name' => 'ids',
                                    'hidden' => true,
                                    'value' => '${ids|json}',
                                ],
                                [
                                    'type' => 'select',
                                    'name' => 'group_id',
                                    'label' => '目标分组',
                                    'source' => $this->getGroupsPath(),
                                    'labelField' => 'name',
                                    'valueField' => 'id',
                                    'clearable' => true,
                                    'placeholder' => '选择分组（留空为未分组）',
                                ],
                            ],
                        ],
                    ],
                ],
            ])
            ->headerToolbar([
                // 本地上传按钮
                [
                    'type' => 'button',
                    'label' => '本地上传',
                    'icon' => 'fa fa-upload',
                    'level' => 'primary',
                    'actionType' => 'dialog',
                    'dialog' => [
                        'title' => '上传文件',
                        'size' => 'lg',
                        'body' => [
                            'type' => 'form',
                            'api' => $this->getUploadPath(),
                            'body' => [
                                [
                                    'type' => 'input-file',
                                    'name' => 'file',
                                    'label' => '选择文件',
                                    'required' => true,
                                    'accept' => 'image/*,video/*,.pdf,.doc,.docx,.xls,.xlsx,.txt,.zip,.rar',
                                    'multiple' => true,
                                    'autoUpload' => false,
                                ],
                                [
                                    'type' => 'input-text',
                                    'name' => 'group_id',
                                    'label' => '分组',
                                    'source' => $this->getGroupsPath(),
                                    'clearable' => true,
                                    'description' => '选择文件所属分组（可选）',
                                ],
                            ],
                        ],
                    ],
                ],
                // 删除按钮
                [
                    'type' => 'button',
                    'label' => '删除',
                    'icon' => 'fa fa-trash',
                    'level' => 'danger',
                    'hiddenOn' => '!${__super.__super.selectedItems || selectedItems}',
                ],
                // 移动按钮
                [
                    'type' => 'button',
                    'label' => '移动',
                    'icon' => 'fa fa-folder',
                    'hiddenOn' => '!${__super.__super.selectedItems || selectedItems}',
                ],
                // 视图切换（列表/网格）
                [
                    'type' => 'button-group',
                    'buttons' => [
                        [
                            'type' => 'button',
                            'icon' => 'fa fa-th',
                            'tooltip' => '网格视图',
                            'activeOn' => '${' . $viewModeVar . ' === "cards"}',
                            'onEvent' => [
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
                            ],
                        ],
                        [
                            'type' => 'button',
                            'icon' => 'fa fa-list',
                            'tooltip' => '列表视图',
                            'activeOn' => '${' . $viewModeVar . ' === "table"}',
                            'onEvent' => [
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
                            ],
                        ],
                    ],
                ],
                // 搜索框
                [
                    'type' => 'input-text',
                    'name' => 'origin_name',
                    'placeholder' => '请输入名称',
                    'clearable' => true,
                    'addOn' => [
                        'type' => 'button',
                        'label' => '搜索',
                        'actionType' => 'submit',
                        'icon' => 'fa fa-search',
                    ],
                ],
                // 文件来源选择
                [
                    'type' => 'select',
                    'name' => 'storage_mode',
                    'placeholder' => '请选择文件来源',
                    'clearable' => true,
                    'options' => SystemFile::STORAGE_MODE,
                    'onEvent' => [
                        'change' => [
                            'actions' => [
                                [
                                    'actionType' => 'reload',
                                    'componentId' => $crudId,
                                ],
                            ],
                        ],
                    ],
                ],
                // 刷新按钮
                [
                    'type' => 'button',
                    'icon' => 'fa fa-refresh',
                    'tooltip' => '刷新',
                    'actionType' => 'reload',
                    'target' => $crudId,
                ],
            ])
            ->footerToolbar([
                'statistics',
                [
                    'type' => 'checkbox',
                    'label' => '当页全选',
                    'onEvent' => [
                        'change' => [
                            'actions' => [
                                [
                                    'actionType' => 'toggleAllSelections',
                                    'componentId' => $crudId,
                                ],
                            ],
                        ],
                    ],
                ],
                'pagination',
            ])
            ->itemActions([
                [
                    'type' => 'button',
                    'level' => 'link',
                    'icon' => 'fa fa-download',
                    'tooltip' => '下载',
                    'actionType' => 'url',
                    'url' => $this->getDownloadPath() . '?id=${id}',
                ],
                [
                    'type' => 'button',
                    'level' => 'link',
                    'icon' => 'fa fa-edit',
                    'tooltip' => '重命名',
                    'actionType' => 'dialog',
                    'dialog' => [
                        'title' => '重命名文件',
                        'body' => [
                            'type' => 'form',
                            'api' => $this->getRenamePath(),
                            'body' => [
                                [
                                    'type' => 'input-text',
                                    'name' => 'id',
                                    'hidden' => true,
                                    'value' => '${id}',
                                ],
                                [
                                    'type' => 'input-text',
                                    'name' => 'name',
                                    'label' => '文件名',
                                    'required' => true,
                                    'value' => '${origin_name}',
                                ],
                            ],
                        ],
                    ],
                ],
                [
                    'type' => 'button',
                    'level' => 'link',
                    'icon' => 'fa fa-trash',
                    'tooltip' => '删除',
                    'actionType' => 'ajax',
                    'api' => $this->getDeletePath(),
                    'confirmText' => '确定要删除这个文件吗？',
                    'data' => ['ids' => '${id}'],
                ],
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
        return $this->response()->success(['items' => $groups]);
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
            $content = $storage->get($file->storage_path);
            
            return \response($content)
                ->header('Content-Type', $file->mime_type)
                ->header('Content-Disposition', 'attachment; filename="' . $file->origin_name . '"');
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
        if (!$name) {
            return $this->response()->fail('分组名称不能为空');
        }

        $result = $this->service->createGroup($name);
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
