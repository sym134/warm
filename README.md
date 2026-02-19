# Warm for Webman 2.1

基于 Webman 2.1、Amis 前端和 Laravel Eloquent 的后台插件。专注“开箱即用”的增删改查、权限与配置管理，用最少代码搭建稳定后台。

## 特性

- Amis 渲染器与 PHP 链式 API，快速拼装页面
- Eloquent ORM 数据访问，内置软删除与分页能力
- 后台鉴权与权限中间件，统一 JSON 响应
- 多语言、文件存储、导出上传等常用能力

## 要求

- PHP ≥ 8.1
- Webman 2.1
- Composer

## 安装

```bash
composer require jizhi/warm
```

## 启动

- Windows: `php windows.php`
- Linux/macOS: `php webman start`

## 10 分钟上手示例

在后台控制器中返回一个最简 Amis 页面：

```php
use warm\admin\controller\AdminController;
use warm\admin\Admin;

class DemoController extends AdminController
{
    public array $noNeedAuth = ['index'];

    public function index()
    {
        $page = amis()->Page()
            ->title('Hello Warm')
            ->body([
                amis()->Tpl()->tpl('欢迎使用 Warm')
            ])
            ->toArray();

        return Admin::response()->success(['page' => $page]);
    }
}
```

## 常用规范

- 控制器继承 `warm\admin\controller\AdminController`
- 服务继承 `warm\admin\service\AdminService`
- 模型继承 `warm\common\model\BaseModel`
- 统一使用 `Admin::response()->success()/fail()` 返回
- 严禁原生 SQL，全部走 Eloquent

## 相关

- 辅助函数：见 `src/helpers.php`
- 配置覆盖：`config/plugin/jizhi/warm/*.php`
- 多语言：`resource/translations/`

## 许可证

MIT
