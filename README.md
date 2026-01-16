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

## 贡献

欢迎提交 Issue 和 Pull Request 来改进这个项目。

## 许可证

MIT License