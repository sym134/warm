<?php

namespace warm\admin\controller;

use Illuminate\Support\Str;
use support\Db;
use warm\admin\Admin;
use warm\admin\renderer\Form;
use warm\admin\renderer\Page;
use warm\admin\renderer\Tag;
use warm\admin\service\AdminMenuService;
use warm\admin\service\AdminPermissionService;
use Webman\Route;

/**
 * 管理权限控制器
 * 
 * 用于管理系统权限的增删改查操作
 * 提供权限自动生成、HTTP方法和路径配置等功能
 * 
 * @property AdminPermissionService $service 管理权限服务类实例
 */
class AdminPermissionController extends AdminController
{
    /**
     * @var string $serviceName 服务类名称
     * 指定当前控制器使用的服务类
     */
    protected string $serviceName = AdminPermissionService::class;

    /**
     * 权限列表页面
     * 
     * 展示系统权限列表，支持自动生成功能
     * 
     * @return Page 返回权限列表页面
     */
    public function list(): Page
    {
        $autoBtn = '';
        if (Admin::warmConfig('app.show_auto_generate_permission_button')) {
            $autoBtn = amis()->AjaxAction()
                ->label(translator('admin.admin_permission.auto_generate'))
                ->level('success')
                ->confirmText(translator('admin.admin_permission.auto_generate_confirm'))
                ->api(admin_url('system/_admin_permissions_auto_generate'));
        }

        $crud = $this->baseCRUD()
            ->loadDataOnce()
            ->filterTogglable(false)
            ->footerToolbar([])
            ->headerToolbar([
                $this->createButton(true, 'lg'),
                'bulkActions',
                $autoBtn,
                amis('reload')->set('align', 'right'),
                amis('filter-toggler')->set('align', 'right'),
            ])
            ->columns([
                amis()->TableColumn('id', 'ID')->sortable(),
                amis()->TableColumn('name', translator('admin.admin_permission.name')),
                amis()->TableColumn('slug', translator('admin.admin_permission.slug')),
                amis()->TableColumn('http_method', translator('admin.admin_permission.http_method'))
                    ->type('each')
                    ->items(
                        Tag::make()->label('${item}')->className('my-1')
                    )
                    ->placeholder(Tag::make()->label('ANY')),
                amis()->TableColumn('http_path', translator('admin.admin_permission.http_path'))
                    ->type('each')
                    ->items(
                        Tag::make()->label('${item}')->className('my-1')
                    ),
                $this->rowActions([
                    $this->rowEditButton(true, 'lg'),
                    $this->rowDeleteButton(),
                ]),
            ]);

        return $this->baseList($crud);
    }

    /**
     * 权限表单页面
     * 
     * 定义权限新增/编辑表单结构，包含名称、标识、HTTP方法、路径等字段
     * 
     * @return Form 返回权限表单
     */
    public function form(): Form
    {
        return $this->baseForm()->body([
            amis()->InputText('name', translator('admin.admin_permission.name'))->required(),
            amis()->InputText('slug', translator('admin.admin_permission.slug'))->required(),
            amis()->TreeSelect('parent_id', translator('admin.parent'))
                ->labelField('name')
                ->valueField('id')
                ->value(0)
                ->options($this->service->getTree()),
            amis()->Checkboxes('http_method', translator('admin.admin_permission.http_method'))
                ->options($this->getHttpMethods())
                ->description(translator('admin.admin_permission.http_method_description'))
                ->joinValues(false)
                ->extractValue(),
            amis()->InputNumber('order', translator('admin.order'))
                ->required()
                ->labelRemark(translator('admin.order_asc'))
                ->displayMode('enhance')
                ->min(0)
                ->value(0),
            amis()->InputArray('http_path', translator('admin.admin_permission.http_path'))
                ->items(amis()->InputText()->options($this->getRoutes())->required()),
            amis()->TreeSelect('menus', translator('admin.menus'))
                ->searchable()
                ->multiple()
                ->showIcon(false)
                ->options(AdminMenuService::make()->getTree())
                ->labelField('title')
                ->valueField('id')
                ->autoCheckChildren(false)
                ->joinValues(false)
                ->extractValue(),
        ]);
    }

    /**
     * 权限详情页面
     * 
     * 展示权限详细信息
     * 
     * @return Form 返回权限详情表单
     */
    public function detail(): Form
    {
        return $this->baseDetail()->body([]);
    }

    /**
     * 获取HTTP方法选项
     * 
     * 从权限模型中获取支持的HTTP方法列表
     * 
     * @return array HTTP方法选项数组
     */
    private function getHttpMethods(): array
    {
        return collect(Admin::adminPermissionModel()::$httpMethods)->map(fn($method) => [
            'value' => $method,
            'label' => $method,
        ])->toArray();
    }

    /**
     * 获取路由路径选项
     * 
     * 获取系统中已注册的路由路径作为选项
     * 
     * @return array 路由路径选项数组
     */
    public function getRoutes(): array
    {
        $prefix = (string)Admin::warmConfig('app.route.prefix');

        $container = collect();
        return collect(Route::getRoutes())->map(function ($route) use ($prefix, $container) {
            if (!Str::startsWith($uri = $route->getPath(), $prefix) && $prefix && $prefix !== '/') {
                return null;
            }
            if (!Str::contains($uri, '{')) {
                if ($prefix !== '/') {
                    $route = Str::replaceFirst($prefix, '', $uri . '*');
                } else {
                    $route = $uri . '*';
                }

                $route !== '*' && $container->push($route);
            }
            $path = preg_replace('/{.*}+/', '*', $uri);
            $prefix !== '/' && $path = Str::replaceFirst($prefix, '', $path);

            return $path;
        })->merge($container)->filter()->unique()->map(function ($method) {
            return [
                'value' => $method,
                'label' => $method,
            ];
        })->values()->all();
    }

    /**
     * 自动生成权限
     * 
     * 根据系统菜单自动生成对应的权限数据
     * 
     * @return mixed 返回操作结果
     */
    public function autoGenerate()
    {
        $menus       = Admin::adminMenuModel()::query()->get()->toArray();
        $slugMap     = Admin::adminPermissionModel()::query()->get(['id', 'slug'])->keyBy('id')->toArray();
        $slugCache   = [];
        $permissions = [];
        foreach ($menus as $menu) {
            $_httpPath =
                $menu['url_type'] == Admin::adminMenuModel()::TYPE_ROUTE ? $this->getHttpPath($menu['url']) : '';

            $menuTitle = $menu['title'];

            // 避免名称重复
            if (in_array($menuTitle, data_get($permissions, '*.name', []))) {
                $menuTitle = sprintf('%s(%s)', $menuTitle, $menu['id']);
            }

            if ($_httpPath) {
                $slug = Str::of(explode('?', $_httpPath)[0])->trim('/')->replace('/', '.')->replace('*', '')->value();
            } else {
                $slug = Str::uuid();
            }

            if (in_array($slug, $slugCache)) {
                $slug = $slug . '.' . $menu['id'];
            }
            $slugCache[] = $slug;

            $permissions[] = [
                'id'         => $menu['id'],
                'name'       => $menuTitle,
                'slug'       => data_get($slugMap, $menu['id'] . '.slug') ?: $slug,
                'http_path'  => json_encode($_httpPath ? [$_httpPath] : ''),
                'order'      => $menu['order'],
                'parent_id'  => $menu['parent_id'],
                'created_at' => $menu['created_at'],
                'updated_at' => $menu['updated_at'],
            ];
        }

        Admin::adminPermissionModel()::query()->truncate();
        Admin::adminPermissionModel()::query()->insert($permissions);

        $permissionClass = Admin::adminPermissionModel();
        $pivotTable      = (new $permissionClass)->menus()->getTable();

        Db::table($pivotTable)->truncate();
        foreach ($permissions as $item) {
            $query = Db::table($pivotTable);
            $query->insert([
                'permission_id' => $item['id'],
                'menu_id'       => $item['id'],
            ]);

            $_id = $item['id'];
            while (data_get($item, 'parent_id', 0) != 0) {
                $query->clone()->insert([
                    'permission_id' => $_id,
                    'menu_id'       => $item['parent_id'],
                ]);

                $item = Admin::adminMenuModel()::query()->find($item['parent_id']);
            }
        }

        return $this->response()->successMessage(
            translator('admin.successfully_message', ['attribute' => translator('admin.admin_permission.auto_generate')])
        );
    }

    /**
     * 获取HTTP路径
     * 
     * 处理并格式化URI路径，添加通配符
     * 
     * @param string $uri 原始URI
     * @return string 处理后的路径
     */
    private function getHttpPath($uri)
    {
        $excepts = ['/', '', '-'];
        if (in_array($uri, $excepts)) {
            return '';
        }

        if (!str_starts_with($uri, '/')) {
            $uri = '/' . $uri;
        }

        return $uri . '*';
    }
}
