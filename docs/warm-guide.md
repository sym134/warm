# Warm 框架详细使用指南

## 目录

- [简介](#简介)
- [安装](#安装)
  - [环境要求](#环境要求)
  - [安装步骤](#安装步骤)
- [目录结构](#目录结构)
- [核心概念](#核心概念)
  - [插件系统](#插件系统)
  - [权限系统](#权限系统)
- [配置管理](#配置管理)
- [数据库](#数据库)
- [前端资源](#前端资源)
- [命令行工具](#命令行工具)
- [插件开发](#插件开发)
  - [创建插件](#创建插件)
  - [插件结构](#插件结构)
  - [插件配置](#插件配置)
- [相关文档](#相关文档)
- [常见问题](#常见问题)

## 简介

Warm 是一个基于 Webman 框架的后台管理系统，它基于 [Owl Admin](https://github.com/slowlyo/owl-admin) 修改而来。它提供了完整的后台管理功能，包括用户管理、权限控制、插件系统等。Warm 专注于提供简洁、易用且功能强大的后台管理解决方案。

*后期不会再更进 Owl Admin 更新

主要特性：
- 基于 Webman 高性能框架
- 完整的 RBAC 权限控制系统
- 灵活的插件系统
- 丰富的后台组件
- 多语言支持
- 命令行工具支持

## 安装

### 环境要求

- PHP >= 8.1
- Composer
- MySQL >= 5.7
- Redis
- BCMath PHP 扩展
- Ctype PHP 扩展
- Fileinfo PHP 扩展
- JSON PHP 扩展
- Mbstring PHP 扩展
- OpenSSL PHP 扩展
- PDO PHP 扩展
- Tokenizer PHP 扩展
- XML PHP 扩展
- Zip PHP 扩展

### 安装步骤

1. 使用 Composer 创建 Webman 项目：
```bash
composer create-project workerman/webman
```

2. 进入项目目录：
```bash
cd webman
```

3. 安装 Warm 扩展包：
```bash
composer require jizhi/warm
```

4. 安装 Warm：
```bash
php webman warm:install
```

4. 配置数据库：
   - 复制 [.example.env](file:///D:/develop/project/composer-packge/jizhi/warm/.example.env) 文件为 .env
   - 修改数据库连接配置

5. 运行数据库迁移：
```bash
php webman migrate
```

6. 启动服务：
```bash
php webman start
```

访问 `http://localhost:8787` 即可进入后台登录页面，默认账号密码为 admin/admin。

### 存储适配器（可选）

Warm 支持多种存储适配器，可根据需要安装：

```bash
# 阿里云 OSS
composer require "iidestiny/flysystem-oss:^4"

# AWS S3
composer require "league/flysystem-aws-s3-v3:^3.0"

# 七牛云
composer require "overtrue/flysystem-qiniu:^3.0"

# 内存存储
composer require "league/flysystem-memory:^3.0"

# 腾讯云 COS
composer require "overtrue/flysystem-cos:^5.0"
```

## 目录结构

```
.
├── config/                    # 配置文件目录
│   ├── plugin/               # 插件配置目录
│   │   └── jizhi/warm/       # Warm 插件配置
│   ├── app.php               # 应用配置
│   ├── autoload.php          # 自动加载配置
│   ├── container.php         # 容器配置
│   ├── database.php          # 数据库配置
│   ├── dependence.php        # 依赖配置
│   ├── exception.php         # 异常处理配置
│   ├── log.php               # 日志配置
│   ├── middleware.php        # 中间件配置
│   ├── process.php           # 进程配置
│   ├── redis.php             # Redis 配置
│   ├── route.php             # 路由配置
│   ├── server.php            # 服务配置
│   ├── session.php           # 会话配置
│   ├── static.php            # 静态资源配置
│   ├── thinkorm.php          # ThinkORM 配置
│   └── translation.php       # 多语言配置
├── plugin/                   # 插件目录
├── public/                   # 公共资源目录
│   └── admin-assets/         # 后台资源文件
├── resource/                 # 资源目录
│   └── translations/         # 多语言文件
├── src/                      # 源码目录
│   ├── warm/                 # Warm 核心代码
│   │   ├── admin/            # 后台管理模块
│   │   ├── command/          # 命令行工具
│   │   ├── common/           # 公共模块
│   │   └── framework/        # 框架扩展
│   └── helpers.php           # 辅助函数
├── vendor/                   # Composer 依赖
├── .env                      # 环境配置文件
├── .example.env              # 环境配置示例文件
├── composer.json             # Composer 配置
└── webman                    # Webman 入口文件
```

## 核心概念

### 插件系统

Warm 的核心是插件系统，几乎所有功能都以插件形式实现。插件可以独立开发、安装、卸载和启用/禁用。

插件特性：
- 独立的目录结构
- 独立的配置文件
- 独立的路由配置
- 可动态启用/禁用
- 支持安装和卸载

### 权限系统

Warm 使用 RBAC（基于角色的访问控制）权限系统：

1. 用户（User）：系统用户
2. 角色（Role）：用户角色
3. 权限（Permission）：具体权限点
4. 菜单（Menu）：后台菜单项

权限控制流程：
- 用户关联角色
- 角色关联权限
- 请求时检查用户是否有访问权限

## 配置管理

Warm 的配置文件主要位于 `config/plugin/jizhi/warm/` 目录下：

- [app.php](file:///D:/develop/project/composer-packge/jizhi/warm/src/config/plugin/jizhi/warm/app.php)：应用基础配置
- [route.php](file:///D:/develop/project/composer-packge/jizhi/warm/src/config/plugin/jizhi/warm/route.php)：路由配置

获取配置值：

```php
$value = \warm\admin\Admin::warmConfig('key', 'default');
```

## 数据库

Warm 使用 Laravel 的 Eloquent ORM 作为数据库操作工具。

主要模型：
- [AdminUser](file:///D:/develop/project/composer-packge/jizhi/warm/src/warm/admin/model/AdminUser.php)：管理员用户
- [AdminRole](file:///D:/develop/project/composer-packge/jizhi/warm/src/warm/admin/model/AdminRole.php)：角色
- [AdminPermission](file:///D:/develop/project/composer-packge/jizhi/warm/src/warm/admin/model/AdminPermission.php)：权限
- [AdminMenu](file:///D:/develop/project/composer-packge/jizhi/warm/src/warm/admin/model/AdminMenu.php)：菜单

## 前端资源

Warm 的前端资源位于 `public/admin-assets/` 目录下，主要包括：

- JS 文件：实现后台功能逻辑
- CSS 文件：样式文件
- 图片资源：图标等图片文件
- index.html：后台入口页面
- 前后端独立部署
- 新建一个存放前端代码的文件夹, 内容如下
```angular2html

.
└── frontend                              # 前端目录
├── admin-assets                      # 从 public 目录原样复制过来
│   ├── assets
│   ├── scripts
│   ├── default-avatar.png
│   └── logo.png
└── index.html                        # 从 admin-assets 目录复制出来
```
#调整入口文件
```angular2html
- <script>window.$adminApiPrefix = '/admin-api'</script>
+ <script>window.$adminApiPrefix = 'https://domain.com/admin-api'</script>

```

站点目录指向 frontend 目录即可
webman建议分开部署


## 命令行工具

Warm 提供了丰富的命令行工具：

```bash
# 创建插件
php webman warm-plugin:create 插件名

# 生成路由配置
php webman warm-gen:route

# 安装命令
php webman warm:install
```

## 插件开发
插件实际依然是webman的[应用插件]:https://www.workerman.net/doc/webman/app/app.html
只是创建做了修改，如果使用webman的创建插件命令需要自行解决中间件、翻译文件

### 创建插件

使用命令行工具创建插件：
```bash
php webman warm-plugin:create myplugin 我的插件 作者名
```

### 插件结构

创建插件后会生成以下目录结构：
```
plugin/
└── myplugin/
    ├── api/
    │   └── Install.php
    ├── app/
    │   ├── controller/
    │   ├── middleware/
    │   ├── model/
    │   ├── service/
    │   ├── view/
    │   └── functions.php
    ├── config/
    │   ├── app.php
    │   ├── menu.php
    │   └── ...
    ├── public/
    ├── resource/
    │   └── translations/
    ├── install.sql
    └── uninstall.sql
```

### 插件配置

插件的核心配置文件是 `config/app.php`：

```php
return [
    'enable' => false,           // 是否启用
    'debug' => true,             // 调试模式
    'version' => '1.0.0',        // 版本号
    'description' => '插件描述',  // 插件描述
    'key' => '插件标识',          // 插件标识
    'name' => '插件名称',         // 插件名称
    'authors' => [               // 作者信息
        'name' => '作者姓名',
        'email' => '作者邮箱',
    ],
];
```

插件路由配置在 `/plugin/插件名/config/route.php`：
```php
use Webman\Route;

// 插件路由示例
Route::any('/myplugin/index', [plugin\myplugin\app\controller\IndexController::class, 'index']);
```

## 相关文档

为了更好地使用 Warm 框架，您还可以参考以下相关文档：

- [AdminController 详细指南](admin-controller-guide.md) - 详细介绍 AdminController 的使用方法和核心概念
- [辅助函数详细指南](helpers-guide.md) - Warm 框架提供的所有辅助函数的详细说明
- [插件系统使用指南](plugin-guide.md) - 插件系统的详细使用说明和开发指南

## 常见问题

### 1. 如何修改后台路径？

修改 `config/plugin/jizhi/warm/app.php` 中的route.prefix。

### 2. 如何扩展用户模型？

可以通过配置文件覆盖默认模型：
```php
// config/plugin/jizhi/warm/app.php
return [
    'models' => [
        'admin_user' => App\Models\CustomUser::class,
    ],
];
```

### 3. 如何自定义权限验证？

可以通过中间件或在控制器中手动验证权限。