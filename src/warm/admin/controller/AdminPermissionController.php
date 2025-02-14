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
 * @property AdminPermissionService $service
 */
class AdminPermissionController extends AdminController
{
    protected string $serviceName = AdminPermissionService::class;

    public function list(): Page
    {
        $autoBtn = '';
        if (Admin::config('app.show_auto_generate_permission_button')) {
            $autoBtn = amis()->AjaxAction()
                ->label(admin_trans('admin.admin_permission.auto_generate'))
                ->level('success')
                ->confirmText(admin_trans('admin.admin_permission.auto_generate_confirm'))
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
                amis()->TableColumn('name', admin_trans('admin.admin_permission.name')),
                amis()->TableColumn('slug', admin_trans('admin.admin_permission.slug')),
                amis()->TableColumn('http_method', admin_trans('admin.admin_permission.http_method'))
                    ->type('each')
                    ->items(
                        Tag::make()->label('${item}')->className('my-1')
                    )
                    ->placeholder(Tag::make()->label('ANY')),
                amis()->TableColumn('http_path', admin_trans('admin.admin_permission.http_path'))
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

    public function form(): Form
    {
        return $this->baseForm()->body([
            amis()->TextControl('name', admin_trans('admin.admin_permission.name'))->required(),
            amis()->TextControl('slug', admin_trans('admin.admin_permission.slug'))->required(),
            amis()->TreeSelectControl('parent_id', admin_trans('admin.parent'))
                ->labelField('name')
                ->valueField('id')
                ->value(0)
                ->options($this->service->getTree()),
            amis()->CheckboxesControl('http_method', admin_trans('admin.admin_permission.http_method'))
                ->options($this->getHttpMethods())
                ->description(admin_trans('admin.admin_permission.http_method_description'))
                ->joinValues(false)
                ->extractValue(),
            amis()->NumberControl('order', admin_trans('admin.order'))
                ->required()
                ->labelRemark(admin_trans('admin.order_asc'))
                ->displayMode('enhance')
                ->min(0)
                ->value(0),
            amis()->ArrayControl('http_path', admin_trans('admin.admin_permission.http_path'))
                ->items(amis()->TextControl()->options($this->getRoutes())->required()),
            amis()->TreeSelectControl('menus', admin_trans('admin.menus'))
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

    public function detail(): Form
    {
        return $this->baseDetail()->body([]);
    }

    private function getHttpMethods(): array
    {
        return collect(Admin::adminPermissionModel()::$httpMethods)->map(fn($method) => [
            'value' => $method,
            'label' => $method,
        ])->toArray();
    }

    public function getRoutes(): array
    {
        $prefix = (string)Admin::config('app.route.prefix');

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
            admin_trans('admin.successfully_message', ['attribute' => admin_trans('admin.admin_permission.auto_generate')])
        );
    }

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
