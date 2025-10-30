# Warm 框架

> 申明：本项目是基于 [Owl Admin](https://github.com/slowlyo/owl-admin) 修改后的版本

Warm 是一个基于 Webman 框架的后台管理系统，它提供了完整的后台管理功能，包括用户管理、权限控制、插件系统等。Warm 专注于提供简洁、易用且功能强大的后台管理解决方案。

*后期不会再跟进 Owl Admin 更新*

## 主要特性

- 基于 Webman 高性能框架
- 完整的 RBAC 权限控制系统
- 灵活的插件系统
- 丰富的后台组件
- 多语言支持
- 命令行工具支持

## 安装

首先创建一个新的 Webman 项目：

```bash
composer create-project workerman/webman
```

进入项目目录并安装 Warm 扩展包：

```bash
cd webman
composer require jizhi/warm
php webman warm:install
```

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

## 文档

有关 Warm 框架的详细使用说明，请查看 [Warm 使用指南](docs/warm-guide.md)。

## 多语言

使用 `translator(插件名::文件名.键……)` 进行多语言翻译。

访问 `http://localhost:8787` 即可进入后台登录页面，默认账号密码为 admin/admin。