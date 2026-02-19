# amis PHP SDK

一个基于 PHP 的 amis 组件库 SDK，用于快速构建动态表单和页面。

## 简介

amis PHP SDK 是一个用于在 PHP 项目中快速创建 amis 组件的库。它提供了面向对象的接口来构建 amis JSON 配置，使得在 PHP 中创建复杂的表单和页面变得更加容易。

## 特性

- 面向对象的组件API
- 支持大多数 amis 组件
- 完整的表单组件支持
- 链式调用语法
- 类型安全的配置

## 安装

使用 Composer 安装：

```bash
composer require jizhi/amis-php-sdk
```

## 快速开始

以下是一个简单的示例，展示如何使用 SDK 创建一个页面：

```php
<?php

require_once 'vendor/autoload.php';

use Jizhi\Amis\Page;
use Jizhi\Amis\Tpl;
use Jizhi\Amis\Form\InputText;
use Jizhi\Amis\Form\Select;
use Jizhi\Amis\Action;
use Jizhi\Amis\Form;

// 创建页面
$page = new Page();
$page->title('用户信息表单');

// 创建表单
$form = new Form([
    (new InputText('name', '姓名'))
        ->placeholder('请输入您的姓名')
        ->required(true),
    (new InputText('email', '邮箱'))
        ->type('email')
        ->placeholder('请输入您的邮箱'),
    (new Select('city', '城市'))
        ->options([
            ['label' => '北京', 'value' => 'beijing'],
            ['label' => '上海', 'value' => 'shanghai'],
            ['label' => '广州', 'value' => 'guangzhou']
        ])
], [
    (new Action('primary', '提交'))
        ->actionType('submit')
        ->api('/api/submit')
]);

$page->body([$form]);

// 输出 JSON 配置
header('Content-Type: application/json');
echo json_encode($page->toArray(), JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
```

## 组件列表

目前支持的组件包括：

### 基础组件
- [x] Action - 操作按钮
- [x] Avatar - 头像
- [x] Badge - 徽章
- [x] Divider - 分割线
- [x] Tpl - 模板
- [x] Link - 链接
- [x] Html - HTML组件
- [x] Image - 图片
- [x] Alert - 警告提示
- [x] Card - 卡片
- [x] Tabs - 选项卡
- [x] Progress - 进度条
- [x] Spinner - 加载动画
- [x] Color - 颜色显示
- [x] Wrapper - 包装器
- [x] Service - 服务组件
- [x] Pagination - 分页
- [x] Breadcrumb - 面包屑
- [x] Tag - 标签
- [x] Steps - 步骤条
- [x] Timeline - 时间线
- [x] Toast - 轻提示
- [x] Nav - 导航
- [x] Grid - 网格
- [x] Hbox - 水平布局
- [x] Flex - 弹性布局

### 高级组件
- [x] Table - 表格
- [x] Form - 表单
- [x] AnchorNav - 锚点导航
- [x] Audio - 音频
- [x] Barcode - 条形码
- [x] Calendar - 日历
- [x] Carousel - 轮播图
- [x] Chart - 图表
- [x] Collapse - 折叠面板
- [x] Date - 日期显示
- [x] Dialog - 对话框
- [x] Drawer - 抽屉
- [x] DropdownButton - 下拉按钮
- [x] Each - 循环组件
- [x] Icon - 图标
- [x] Iframe - 内嵌框架
- [x] Images - 图片集
- [x] List - 列表
- [x] Log - 日志
- [x] Mapping - 映射
- [x] Markdown - Markdown
- [x] Number - 数字显示
- [x] Panel - 面板
- [x] Qrcode - 二维码
- [x] Status - 状态
- [x] Video - 视频
- [x] ButtonGroup - 按钮组

### 表单组件
- [x] InputText - 文本输入
- [x] Checkbox - 复选框
- [x] Checkboxes - 多选框组
- [x] InputDate - 日期输入
- [x] InputNumber - 数字输入
- [x] Select - 选择器
- [x] Switch - 开关
- [x] Textarea - 文本域

## API 参考

### 基础组件

所有组件都继承自 [Component](amis-components/Component.php) 类，提供基础的配置方法：

- `set($key, $value)` - 设置组件属性
- `toArray()` - 获取组件的数组表示
- `toJson()` - 获取组件的JSON表示

### 页面组件

[Page](amis-components/Page.php) 类是所有页面的基础：

```php
$page = new Page();
$page->title('页面标题');
$page->remark('页面备注');
$page->body([/* 组件数组 */]);
```

### 表单组件

[Form](amis-components/Form.php) 类用于创建表单：

```php
$form = new Form([
    // 表单项数组
], [
    // 按钮数组
]);
```
## 10. 辅助函数

Warm 提供了丰富的辅助函数（`src/helpers.php`）：

- `app($abstract = null)`: 获取容器实例
- `cache()`: 获取缓存实例
- `config($key, $default = null)`: 获取配置
- `db()`: 获取数据库连接
- `env($key, $default = null)`: 获取环境变量
- `storage($disk = null)`: 获取存储实例
- `translator($key, $replace = [], $locale = null)`: 多语言翻译
- `validate($data, $validate, $message = [], $batch = false)`: 数据验证


## 11. 相关文档

- [Warm 使用指南](docs/warm-guide.md) - 框架详细使用说明
- [AdminController 详细指南](docs/admin-controller-guide.md) - AdminController 使用方法和核心概念
- [辅助函数详细指南](docs/helpers-guide.md) - 所有辅助函数的详细说明
- [插件系统使用指南](docs/plugin-guide.md) - 插件系统详细使用说明和开发指南
- [Amis 方法类映射](docs/amis-method-class-mapping.md) - Amis 组件与 PHP 类的映射关系


## 13. 注意事项

1. **命名空间**: 所有 Warm 相关类都在 `warm\` 命名空间下
2. **模型继承**: 所有模型必须继承自 `warm\common\model\BaseModel`
3. **控制器继承**: 所有后台控制器必须继承自 `warm\admin\controller\AdminController`
4. **服务类**: Service 类必须实现 `make()` 静态方法用于实例化
5. **权限控制**: 使用 `$noNeedLogin` 和 `$noNeedAuth` 数组控制权限，不要手动检查
6. **响应格式**: 统一使用 `Admin::response()` 返回响应
7. **多语言**: 使用 `translator()` 函数，不要硬编码文本
8. **文件存储**: 使用 `Storage` 门面，不要直接操作文件系统
9. **配置获取**: 使用 `Admin::warmConfig()` 获取 Warm 配置，使用 `Admin::config()` 获取系统配置
10. **数据库操作**: 使用 Eloquent ORM，不要使用原生 SQL（除非必要）

## 12. 常见问题

### 如何修改后台路径？
修改 `config/plugin/jizhi/warm/app.php` 中的 `route.prefix` 配置。

### 如何扩展用户模型？
通过配置文件覆盖默认模型：
```php
// config/plugin/jizhi/warm/app.php
return [
    'models' => [
        'admin_user' => App\Models\CustomUser::class,
    ],
];
```

### 如何自定义权限验证？
可以通过中间件或在控制器中手动验证权限。使用 `Admin::permission()->check($permission)` 检查权限。

### 如何前后端分离部署？
1. 将 `public/admin-assets/` 目录复制到前端服务器
2. 修改 `index.html` 中的 `window.$adminApiPrefix` 为后端 API 地址
3. 前端服务器指向包含 `index.html` 的目录
4. Webman 服务器独立部署，处理 API 请求

### 如何添加自定义存储驱动？
1. 实现 `warm\framework\filesystem\FilesystemAdapter` 接口
2. 在 `config/filesystems.php` 中注册驱动
3. 使用 `Storage::disk('custom')` 访问

## 贡献

欢迎提交 Issue 和 Pull Request 来改进这个项目。

## 许可证

MIT License