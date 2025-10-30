# 插件系统使用指南

## 目录

- [简介](#简介)
- [插件创建](#插件创建)
  - [通过命令行创建](#通过命令行创建)
  - [插件目录结构](#插件目录结构)
- [插件安装](#插件安装)
  - [本地安装](#本地安装)
- [插件管理](#插件管理)
  - [启用/禁用插件](#启用禁用插件)
  - [卸载插件](#卸载插件)
- [插件开发](#插件开发)
  - [配置文件](#配置文件)
  - [控制器](#控制器)
  - [服务类](#服务类)
  - [数据库](#数据库)
  - [菜单](#菜单)
- [插件示例](#插件示例)

## 简介

插件系统是本框架的核心功能之一，允许开发者扩展应用程序的功能而无需修改核心代码。插件可以添加新的功能模块、修改现有功能或提供额外的服务。

更多功能详情，请参阅[webman应用插件文档](https://www.workerman.net/doc/webman/app/app.html)。

插件系统具有以下特点：
- 独立性：每个插件都是一个独立的模块，拥有自己的目录结构和配置
- 可插拔性：插件可以随时启用或禁用，不影响其他插件和主程序
- 易于管理：提供统一的插件管理界面，方便安装、卸载和配置插件

## 插件创建

### 通过命令行创建

使用命令行工具可以快速创建一个新的插件：

```bash
php think warm-plugin:create 插件名称 [插件别名] [作者名称]
```

例如，创建一个名为`blog`的插件：

```bash
php think warm-plugin:create blog 博客插件 极智科技
```

该命令会自动创建插件目录结构和必要的配置文件。

### 插件目录结构

创建插件后，会生成以下目录结构：

```
plugin/
└── {插件名称}/
    ├── api/
    │   └── Install.php              # 插件安装、卸载、更新逻辑
    ├── app/
    │   ├── controller/              # 控制器目录
    │   │   └── IndexController.php  # 默认控制器
    │   ├── middleware/              # 中间件目录
    │   ├── model/                   # 模型目录
    │   ├── service/                 # 服务类目录
    │   │   └── {插件名称}Service.php # 插件服务类
    │   ├── view/                    # 视图目录
    │   │   └── index/
    │   │       └── index.html       # 默认视图文件
    │   └── functions.php            # 插件自定义函数
    ├── config/                      # 配置目录
    │   ├── app.php                  # 插件基础配置
    │   ├── autoload.php             # 自动加载配置
    │   ├── container.php            # 容器配置
    │   ├── database.php             # 数据库配置
    │   ├── exception.php            # 异常处理配置
    │   ├── log.php                  # 日志配置
    │   ├── menu.php                 # 菜单配置
    │   ├── middleware.php           # 中间件配置
    │   ├── process.php              # 进程配置
    │   ├── redis.php                # Redis配置
    │   ├── route.php                # 路由配置
    │   ├── static.php               # 静态资源配置
    │   ├── translation.php          # 多语言配置
    │   ├── view.php                 # 视图配置
    │   └── thinkorm.php             # ThinkORM配置
    ├── public/                      # 公共资源目录
    ├── resource/                    # 资源目录
    │   └── translations/            # 多语言文件目录
    ├── install.sql                  # 插件安装SQL文件
    └── uninstall.sql                # 插件卸载SQL文件（可选）
```

## 插件安装

### 本地安装

除了通过命令行创建插件，还可以通过上传插件压缩包的方式安装插件：

1. 将插件打包成zip格式的压缩包
2. 进入管理后台的插件管理页面
3. 点击"本地安装"按钮，选择上传的插件压缩包
4. 系统会自动解压并验证插件的合规性
5. 安装完成后，插件会出现在插件列表中，默认为禁用状态

## 插件管理

### 启用/禁用插件

在插件列表中，可以通过点击"启用"或"禁用"按钮来控制插件的状态：

- 启用插件：插件功能生效，可以正常访问插件提供的功能
- 禁用插件：插件功能失效，无法访问插件提供的功能

注意：启用或禁用插件后，系统会自动更新插件配置文件和数据库记录。

### 卸载插件

如果不再需要某个插件，可以选择卸载：

1. 确保插件处于禁用状态
2. 点击插件操作列的"卸载"按钮
3. 确认卸载操作

卸载过程会执行以下操作：
1. 调用插件的卸载方法清理数据
2. 删除插件目录
3. 从数据库中移除插件记录

## 插件开发

### 配置文件

插件的核心配置文件是`config/app.php`，包含了插件的基本信息：

```php
<?php
return [
    'enable' => false,           // 是否启用
    'debug' => true,             // 是否开启调试模式
    'version' => '1.0.0',        // 插件版本号
    'description' => '插件描述信息', // 插件描述
    'key' => '插件标识符',         // 插件唯一标识符
    'name' => '插件名称',          // 插件显示名称
    'authors' => [               // 插件作者信息
        'name' => '作者姓名',
        'email' => '作者邮箱',
    ],
];
```

### 控制器

插件控制器位于`app/controller/`目录下，默认包含一个[IndexController.php](file:///D:/develop/project/composer-packge/jizhi/warm/src/warm/command/AppPluginCreateCommand.php#L263-L277)文件：

```php
<?php
namespace plugin\{插件名称}\app\controller;

use support\Request;
use warm\admin\controller\AdminController;
use warm\admin\service\AdminApiService;
use support\Response;

class IndexController extends AdminController
{
    public string $serviceName = AdminApiService::class;
    
    public function index(): Response
    {
        return view('index/index', ['name' => '{插件名称}']);
    }
}
```

### 服务类

每个插件都有一个服务类，位于`app/service/`目录下，类名格式为`{插件名称}Service.php`：

```php
<?php
namespace plugin\{插件名称}\app\service;

use warm\admin\plugin\PluginService;

class BlogService extends PluginService
{
    // 在这里添加插件特有的服务方法
}
```

服务类继承自[PluginService](file:///D:/develop/project/composer-packge/jizhi/warm/src/warm/admin/plugin/PluginService.php#L17-L783)，可以使用父类提供的各种插件管理方法。

### 数据库

插件的数据库操作通过以下文件管理：

1. `install.sql`：插件安装时执行的SQL语句
2. `uninstall.sql`：插件卸载时执行的SQL语句（可选）
3. `api/Install.php`：包含安装、卸载、更新等方法

在`Install.php`中，可以定义插件的安装逻辑：

```php
public static function install($version)
{
    // 安装数据库
    static::installSql();
    // 导入菜单
    if($menus = static::getMenus()) {
        Menu::import($menus);
    }
}
```

### 菜单
菜单具体的导入需要自行解决

插件菜单配置在`config/menu.php`文件中：

```php
<?php
return [
    [
        'key' => '插件唯一标识',
        'name' => '菜单名称',
        'icon' => '菜单图标',
        'href' => '/插件名称',
        'children' => [
            [
                'key' => '子菜单唯一标识',
                'name' => '子菜单名称',
                'href' => '/插件名称/控制器/方法',
            ]
        ]
    ]
];
```

## 插件示例

下面是一个简单的博客插件示例：

1. 创建插件：
```bash
php think warm-plugin:create blog 博客插件
```

2. 修改控制器`app/controller/IndexController.php`：
```php
<?php
namespace plugin\blog\app\controller;

use support\Request;
use warm\admin\controller\AdminController;
use warm\admin\service\AdminApiService;
use support\Response;

class IndexController extends AdminController
{
    public string $serviceName = AdminApiService::class;
    
    public function index(): Response
    {
        return view('index/index', [
            'title' => '我的博客',
            'posts' => [
                ['title' => '第一篇博客', 'content' => '这是第一篇博客的内容'],
                ['title' => '第二篇博客', 'content' => '这是第二篇博客的内容'],
            ]
        ]);
    }
}
```

3. 修改视图文件`app/view/index/index.html`：
```html
<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title><?=htmlspecialchars($title)?></title>
</head>
<body>
    <h1><?=htmlspecialchars($title)?></h1>
    <?php foreach($posts as $post): ?>
    <div>
        <h2><?=htmlspecialchars($post['title'])?></h2>
        <p><?=htmlspecialchars($post['content'])?></p>
    </div>
    <?php endforeach; ?>
</body>
</html>
```

4. 启用插件后，访问`/blog`即可看到博客页面。

通过以上指南，您可以快速创建、安装和管理插件，以及进行插件开发。插件系统为应用程序提供了强大的扩展能力，使您可以根据需要灵活地添加各种功能。