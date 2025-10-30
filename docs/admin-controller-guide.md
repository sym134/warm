# AdminController 详细指南

## 目录

- [简介](#简介)
- [AdminController 核心概念](#admincontroller-核心概念)
  - [基础功能](#基础功能)
  - [核心属性](#核心属性)
  - [核心方法](#核心方法)
- [AdminService 服务类](#adminservice-服务类)
  - [基础功能](#基础功能-1)
  - [核心方法](#核心方法-1)
  - [钩子方法](#钩子方法)
- [使用示例](#使用示例)
  - [创建控制器](#创建控制器)
  - [创建服务类](#创建服务类)
  - [实现列表页](#实现列表页)
  - [实现表单页](#实现表单页)
  - [实现详情页](#实现详情页)
- [高级功能](#高级功能)
  - [权限控制](#权限控制)
  - [数据导出](#数据导出)
  - [文件上传](#文件上传)
  - [快速编辑](#快速编辑)

## 简介

AdminController 是 Warm 框架中用于构建后台管理系统的基类控制器。它提供了一套完整的增删改查（CRUD）功能，以及权限控制、数据导出、文件上传等常用后台功能。通过继承 AdminController，开发者可以快速构建功能完善的后台管理模块。

AdminController 采用了"控制器-服务"的设计模式，将业务逻辑从控制器中分离到服务类中，使代码更加清晰和易于维护。

## AdminController 核心概念

### 基础功能

AdminController 提供了以下核心功能：

1. **增删改查操作**：内置了完整的 CRUD 操作方法
2. **权限控制**：支持不需要登录和不需要权限验证的方法配置
3. **数据导出**：支持将列表数据导出为 Excel 文件
4. **文件上传**：提供统一的文件上传处理方法
5. **页面渲染**：基于 Amis 前端框架的页面渲染功能
6. **响应处理**：统一的 JSON 响应格式

### 核心属性

AdminController 定义了几个重要的属性：

```php
// 定义不需要登录的方法
protected array $noNeedLogin = [];

// 定义不需要权限验证的方法（但仍需要登录）
protected array $noNeedAuth = [];

// 服务类实例
protected object $service;

// 服务类名称
protected string $serviceName = '';

// 当前请求路径（不包含管理前缀）
protected string $queryPath;

// 管理后台路由前缀
protected string $adminPrefix;

// 是否是新增页面
protected bool $isCreate = false;

// 是否是编辑页面
protected bool $isEdit = false;
```

### 核心方法

AdminController 提供了以下核心方法：

#### index()
处理列表页请求，支持数据获取和导出功能：
```php
public function index()
{
    // 如果是获取数据的操作，返回列表数据
    if ($this->actionOfGetData()) {
        return $this->response()->success($this->service->list());
    }

    // 如果是导出操作，执行导出逻辑
    if ($this->actionOfExport()) {
        return $this->export();
    }

    // 默认返回列表页面
    return $this->response()->success($this->list());
}
```

#### create()
获取新增页面：
```php
public function create()
{
    // 设置当前为创建页面状态
    $this->isCreate = true;

    // 构建表单页面结构
    $form = amis()
        ->Card()
        ->header(['title' => translator('admin.create'), 'className' => 'border-b'])
        ->toolbar([$this->backButton()])
        ->body($this->form(false)->api($this->getStorePath()));

    $page = $this->basePage()->body($form);

    return $this->response()->success($page);
}
```

#### store()
处理新增数据保存：
```php
public function store(Request $request)
{
    $response = fn($result) => $this->autoResponse($result, translator('admin.save'));

    // 快速编辑处理
    if ($this->actionOfQuickEdit()) {
        return $response($this->service->quickEdit($request->all()));
    }

    // 单项快速编辑处理
    if ($this->actionOfQuickEditItem()) {
        return $response($this->service->quickEditItem($request->all()));
    }

    // 常规新增处理
    return $response($this->service->store($request->all()));
}
```

#### show()
显示详情页面：
```php
public function show($id)
{
    // 如果是获取数据操作，返回详情数据
    if ($this->actionOfGetData()) {
        return $this->response()->success($this->service->getDetail($id));
    }

    // 构建详情页面结构
    $detail = amis()
        ->Card()
        ->header(['title' => translator('admin.detail'), 'className' => 'border-b'])
        ->body($this->detail())
        ->toolbar([$this->backButton()]);

    $page = $this->basePage()->body($detail);

    return $this->response()->success($page);
}
```

#### edit()
获取编辑页面：
```php
public function edit($id)
{
    // 设置当前为编辑页面状态
    $this->isEdit = true;

    // 如果是获取数据操作，返回编辑所需数据
    if ($this->actionOfGetData()) {
        return $this->response()->success($this->service->getEditData($id));
    }

    // 构建编辑表单结构
    $form = amis()
        ->Card()
        ->header(['title' => translator('admin.edit'), 'className' => 'border-b'])
        ->toolbar([$this->backButton()])
        ->body($this->form(true)->api($this->getUpdatePath())->initApi($this->getEditGetDataPath()));

    $page = $this->basePage()->body($form);

    return $this->response()->success($page);
}
```

#### update()
处理数据更新：
```php
public function update(Request $request, $id)
{
    // 获取主键值
    $primaryKey = $this->getPrimaryValue($request) ?: $id;
    
    // 执行更新操作
    $result = $this->service->update($primaryKey, $request->all());

    // 返回自动响应结果
    return $this->autoResponse($result, translator('admin.save'));
}
```

#### destroy()
处理数据删除：
```php
public function destroy($id)
{
    // 执行删除操作
    $rows = $this->service->delete($id);

    // 返回自动响应结果
    return $this->autoResponse($rows, translator('admin.delete'));
}
```

## AdminService 服务类

AdminService 是 AdminController 对应的服务类基类，负责处理具体的业务逻辑。

### 基础功能

1. **模型操作**：封装了模型的增删改查操作
2. **数据查询**：提供了灵活的数据查询方法
3. **关联关系**：支持模型关联关系的处理
4. **钩子方法**：提供多个钩子方法供子类重写

### 核心方法

#### list()
获取列表数据：
```php
public function list(): array
{
    $query = $this->listQuery();

    $list = $query->paginate(request()->input('perPage', 20));
    $items = $list->items();
    $total = $list->total();

    return compact('items', 'total');
}
```

#### store()
新增数据：
```php
public function store(array $data): bool
{
    Db::beginTransaction();
    try {
        $this->saving($data);

        $model = $this->getModel();
        foreach ($data as $k => $v) {
            if (!$this->hasColumn($k)) {
                continue;
            }

            $model->setAttribute($k, $v);
        }

        $result = $model->save();
        if ($result) {
            $this->saved($model);
        }

        Db::commit();
    } catch (\Throwable $e) {
        Db::rollBack();

        admin_abort($e->getMessage());
    }

    return $result;
}
```

#### update()
更新数据：
```php
public function update(mixed $primaryKey, array $data): bool
{
    Db::beginTransaction();
    try {
        $this->saving($data, $primaryKey);

        $model = $this->query()->whereKey($primaryKey)->first();

        foreach ($data as $k => $v) {
            if (!$this->hasColumn($k)) {
                continue;
            }
            $model->setAttribute($k, $v);
        }

        $result = $model->save();
        if ($result) {
            $this->saved($model, true);
        }

        Db::commit();
    } catch (\Throwable $e) {
        Db::rollBack();

        admin_abort($e->getMessage());
    }

    return $result;
}
```

#### delete()
删除数据：
```php
public function delete(string $ids): bool
{
    Db::beginTransaction();
    try {
        $result = $this->query()->whereIn($this->primaryKey(), explode(',', $ids))->delete();
        if ($result) {
            $this->deleted($ids);
        }

        Db::commit();
    } catch (\Throwable $e) {
        Db::rollBack();
        admin_abort($e->getMessage());
    }

    return $result;
}
```

### 钩子方法

AdminService 提供了多个钩子方法，允许子类在特定时机插入自定义逻辑：

1. **saving()**：在数据保存前调用
2. **saved()**：在数据保存后调用
3. **deleted()**：在数据删除后调用
4. **sortable()**：处理排序逻辑
5. **searchable()**：处理搜索逻辑
6. **addRelations()**：添加关联关系

## 使用示例

### 创建控制器

创建一个用户管理控制器：

```php
<?php

namespace plugin\admin\app\controller;

use support\Request;
use support\Response;
use plugin\admin\app\service\UserService;

class UserController extends AdminController
{
    // 指定服务类
    public string $serviceName = UserService::class;
    
    // 不需要权限验证的方法
    protected array $noNeedAuth = ['index'];
    
    // 实现列表页结构
    public function list()
    {
        return $this->baseList()
            ->header(['title' => '用户管理', 'className' => 'border-b'])
            ->filter($this->baseFilter()->body([
                $this->inputText('username', '用户名'),
                $this->inputText('email', '邮箱'),
            ]))
            ->body(
                $this->table()
                    ->api($this->getIndexDataPath())
                    ->perPage(20)
                    ->columns([
                        $this->text('id', 'ID'),
                        $this->text('username', '用户名'),
                        $this->text('email', '邮箱'),
                        $this->datetime('created_at', '创建时间'),
                        $this->datetime('updated_at', '更新时间'),
                        $this->fixedColumn()->buttons([
                            $this->rowButton('edit'),
                            $this->rowButton('delete'),
                        ])
                    ])
            );
    }
    
    // 实现表单页结构
    public function form(bool $isEdit = false)
    {
        return $this->baseForm()->body([
            $this->inputText('username', '用户名')->required(),
            $this->inputEmail('email', '邮箱')->required(),
            $this->inputPassword('password', '密码')->required(!$isEdit),
        ]);
    }
    
    // 实现详情页结构
    public function detail()
    {
        return $this->baseDetail()->body([
            $this->detailText('id', 'ID'),
            $this->detailText('username', '用户名'),
            $this->detailText('email', '邮箱'),
            $this->detailDate('created_at', '创建时间'),
            $this->detailDate('updated_at', '更新时间'),
        ]);
    }
}
```

### 创建服务类

创建对应的用户服务类：

```php
<?php

namespace plugin\admin\app\service;

use plugin\admin\app\model\User;
use warm\admin\service\AdminService;

class UserService extends AdminService
{
    // 指定模型类
    protected string $modelName = User::class;
    
    // 保存前处理
    public function saving(array &$data, string $primaryKey = '')
    {
        // 如果是新增且密码不为空，进行加密处理
        if (!$primaryKey && !empty($data['password'])) {
            $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
        }
        
        // 如果是更新且密码为空，移除密码字段
        if ($primaryKey && empty($data['password'])) {
            unset($data['password']);
        }
    }
    
    // 保存后处理
    public function saved(mixed $model, bool $isEdit = false)
    {
        // 可以在这里处理保存后的逻辑，如发送通知等
    }
}
```

### 实现列表页

在控制器中实现列表页结构：

```php
public function list()
{
    return $this->baseList()
        ->header(['title' => '用户管理', 'className' => 'border-b'])
        ->filter($this->baseFilter()->body([
            $this->inputText('username', '用户名'),
            $this->inputText('email', '邮箱'),
        ]))
        ->body(
            $this->table()
                ->api($this->getIndexDataPath())
                ->perPage(20)
                ->columns([
                    $this->text('id', 'ID'),
                    $this->text('username', '用户名'),
                    $this->text('email', '邮箱'),
                    $this->datetime('created_at', '创建时间'),
                    $this->datetime('updated_at', '更新时间'),
                    $this->fixedColumn()->buttons([
                        $this->rowButton('edit'),
                        $this->rowButton('delete'),
                    ])
                ])
        );
}
```

### 实现表单页

在控制器中实现表单页结构：

```php
public function form(bool $isEdit = false)
{
    return $this->baseForm()->body([
        $this->inputText('username', '用户名')->required(),
        $this->inputEmail('email', '邮箱')->required(),
        $this->inputPassword('password', '密码')->required(!$isEdit),
    ]);
}
```

### 实现详情页

在控制器中实现详情页结构：

```php
public function detail()
{
    return $this->baseDetail()->body([
        $this->detailText('id', 'ID'),
        $this->detailText('username', '用户名'),
        $this->detailText('email', '邮箱'),
        $this->detailDate('created_at', '创建时间'),
        $this->detailDate('updated_at', '更新时间'),
    ]);
}
```

## 高级功能

### 权限控制

AdminController 提供了两种权限控制属性：

1. `$noNeedLogin`：定义不需要登录即可访问的方法
2. `$noNeedAuth`：定义不需要权限验证但需要登录的方法

示例：
```php
class UserController extends AdminController
{
    // 不需要登录的方法
    protected array $noNeedLogin = ['login'];
    
    // 不需要权限验证的方法
    protected array $noNeedAuth = ['index', 'show'];
}
```

### 数据导出

AdminController 内置了数据导出功能，通过 `export()` 方法实现。导出功能基于请求参数中的 `export` 字段判断是否需要导出。

在服务类中可以通过重写 `exportMap()` 方法自定义导出字段映射：

```php
public function exportMap(): array
{
    return [
        'id' => 'ID',
        'username' => '用户名',
        'email' => '邮箱',
        'created_at' => '创建时间',
    ];
}
```

### 文件上传

AdminController 提供了统一的文件上传处理方法，通过 `upload()` 方法实现。支持多种存储适配器（本地、OSS、七牛等）。

使用示例：
```php
public function uploadImage(Request $request)
{
    return $this->upload($request, 'image');
}
```

### 快速编辑

AdminController 支持快速编辑功能，允许在列表页直接编辑数据。通过 `quickEdit()` 和 `quickEditItem()` 方法实现。

在列表页中配置可编辑字段：
```php
$this->text('username', '用户名')->quickEdit(true)
```

通过以上详细指南，您可以全面了解 AdminController 的使用方法，并能够基于它快速开发功能完善的后台管理系统。