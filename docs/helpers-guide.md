# Warm 框架辅助函数详细指南

## 目录

- [简介](#简介)
- [认证相关函数](#认证相关函数)
  - [admin_user()](#admin_user)
  - [admin_abort() 系列](#admin_abort-系列)
- [URL 和路径相关函数](#url-和路径相关函数)
  - [admin_url()](#admin_url)
  - [admin_path()](#admin_path)
  - [admin_extension_path()](#admin_extension_path)
  - [plugin_path()](#plugin_path)
  - [database_path()](#database_path)
- [Amis 相关函数](#amis-相关函数)
  - [amis()](#amis)
  - [amisMake()](#amisMake)
- [数据处理函数](#数据处理函数)
  - [array2tree()](#array2tree)
  - [map2options()](#map2options)
  - [table_columns()](#table_columns)
  - [safe_explode()](#safe_explode)
- [文件上传处理函数](#文件上传处理函数)
  - [file_upload_handle()](#file_upload_handle)
  - [file_upload_handle_multi()](#file_upload_handle_multi)
  - [admin_resource_full_path()](#admin_resource_full_path)
- [加密和哈希函数](#加密和哈希函数)
  - [bcrypt()](#bcrypt)
- [配置和系统函数](#配置和系统函数)
  - [systemConfig()](#systemConfig)
  - [admin_pages()](#admin_pages)
  - [cache()](#cache)
  - [appw()](#appw)
- [异常处理函数](#异常处理函数)
  - [admin_abort()](#admin_abort)
  - [amis_abort()](#amis_abort)
  - [admin_abort_if()](#admin_abort_if)
  - [amis_abort_if()](#amis_abort_if)
- [流程控制函数](#流程控制函数)
  - [admin_pipeline()](#admin_pipeline)
- [其他实用函数](#其他实用函数)
  - [translator()](#translator)
  - [is_json()](#is_json)
  - [runCommand()](#runCommand)
  - [url()](#url)
  - [abort()](#abort)

## 简介

Warm 框架提供了一套丰富的辅助函数，用于简化开发过程中的常见任务。这些函数涵盖了认证、URL 生成、数据处理、文件上传、加密、配置管理等多个方面。通过使用这些辅助函数，开发者可以更高效地编写代码，提高开发效率。

本文档将详细介绍每个辅助函数的用途、参数和使用示例。

## 认证相关函数

### admin_user()

获取当前登录的管理员用户信息。

```php
function admin_user(): AdminUser|Authenticatable|null
```

**返回值：**
- 成功时返回 [AdminUser](file:///D:/develop/project/composer-packge/jizhi/warm/src/warm/admin/model/AdminUser.php#L13-L230) 对象或认证用户对象
- 未登录时返回 null

**使用示例：**
```php
$user = admin_user();
if ($user) {
    echo "当前用户: " . $user->username;
} else {
    echo "用户未登录";
}
```

### admin_abort() 系列

管理后台异常处理函数，包括：
- `admin_abort()`: 抛出管理后台异常
- `amis_abort()`: 抛出 Amis 异常（不显示提示）
- `admin_abort_if()`: 条件异常抛出
- `amis_abort_if()`: 条件抛出 Amis 异常（不显示提示）

```php
function admin_abort(string $message = '', array $data = [], int $doNotDisplayToast = 0): mixed
function amis_abort(string $message = '', array $data = []): void
function admin_abort_if(bool $flag, string $message = '', array $data = [], int $doNotDisplayToast = 0): void
function amis_abort_if(bool $flag, string $message = '', array $data = []): void
```

**参数说明：**
- `$message`: 异常信息
- `$data`: 异常数据
- `$doNotDisplayToast`: 是否显示提示
- `$flag`: 条件判断

**使用示例：**
```php
// 抛出异常
admin_abort('操作失败');

// 条件抛出异常
admin_abort_if($user->role !== 'admin', '权限不足');

// 抛出 Amis 异常
amis_abort('表单验证失败', ['field' => 'username']);
```

## URL 和路径相关函数

### admin_url()

生成管理后台 URL。

```php
function admin_url($path = null, $needPrefix = false): string
```

**参数说明：**
- `$path`: 路径
- `$needPrefix`: 是否需要添加前缀

**使用示例：**
```php
// 生成不带前缀的 URL
$url = admin_url('users');

// 生成带前缀的 URL
$url = admin_url('users', true);
```

### admin_path()

获取管理后台相关文件的完整路径。

```php
function admin_path($path = ''): string
```

**参数说明：**
- `$path`: 相对路径

**使用示例：**
```php
// 获取控制器路径
$path = admin_path('controller/UserController.php');
```

### admin_extension_path()

获取管理后台扩展的路径。

```php
function admin_extension_path(?string $path = ''): string
```

**参数说明：**
- `$path`: 相对路径

**使用示例：**
```php
// 获取扩展目录
$extensionPath = admin_extension_path();

// 获取扩展下的文件路径
$filePath = admin_extension_path('MyExtension/config.php');
```

### plugin_path()

获取插件目录的完整路径。

```php
function plugin_path(string $path = ''): string
```

**参数说明：**
- `$path`: 相对路径

**使用示例：**
```php
// 获取插件目录
$pluginPath = plugin_path();

// 获取特定插件路径
$myPluginPath = plugin_path('myplugin');
```

### database_path()

获取数据库相关文件的路径。

```php
function database_path($name): string
```

**参数说明：**
- `$name`: 文件名

**使用示例：**
```php
// 获取迁移文件路径
$migrationPath = database_path('migrations');
```

## Amis 相关函数

### amis()

创建 Amis 组件实例，用于构建 Amis 界面。

```php
function amis($type = null): Amis|Component
```

**参数说明：**
- `$type`: 组件类型

**使用示例：**
```php
// 创建 Amis 实例
$amis = amis();

// 创建特定类型的组件
$button = amis('button');
```

### amisMake()

创建并返回 Amis 实例（已弃用，建议使用 [amis()](file:///D:/develop/project/composer-packge/jizhi/warm/src/helpers.php#L85-L96) 函数）。

```php
function amisMake(): Amis
```

## 数据处理函数

### array2tree()

将扁平的数组结构转换为树形结构。

```php
function array2tree(array $list, int $parentId = 0): array
```

**参数说明：**
- `$list`: 扁平的数组列表
- `$parentId`: 父级 ID

**使用示例：**
```php
$menuItems = [
    ['id' => 1, 'parent_id' => 0, 'name' => '首页'],
    ['id' => 2, 'parent_id' => 0, 'name' => '系统管理'],
    ['id' => 3, 'parent_id' => 2, 'name' => '用户管理'],
    ['id' => 4, 'parent_id' => 2, 'name' => '角色管理'],
];

$tree = array2tree($menuItems);
```

### map2options()

将键值对映射转换为选项数组格式。

```php
function map2options($map): array
```

**参数说明：**
- `$map`: 键值对映射

**使用示例：**
```php
$statusMap = [
    1 => '启用',
    0 => '禁用'
];

$options = map2options($statusMap);
// 结果: [['label' => '启用', 'value' => 1], ['label' => '禁用', 'value' => 0]]
```

### table_columns()

获取指定数据表的所有字段名称。

```php
function table_columns($tableName): array
```

**参数说明：**
- `$tableName`: 数据表名

**使用示例：**
```php
$columns = table_columns('users');
// 获取 users 表的所有字段名
```

### safe_explode()

可安全处理数组的分割函数。

```php
function safe_explode($delimiter, $string): array|bool
```

**参数说明：**
- `$delimiter`: 分隔符
- `$string`: 待分割的字符串或数组

**使用示例：**
```php
$result = safe_explode(',', 'a,b,c');
// 结果: ['a', 'b', 'c']

// 如果传入数组，直接返回
$result = safe_explode(',', ['a', 'b', 'c']);
// 结果: ['a', 'b', 'c']
```

## 文件上传处理函数

### file_upload_handle()

处理文件上传的显示和存储问题。

```php
function file_upload_handle(): \Illuminate\Database\Eloquent\Casts\Attribute
```

**使用示例：**
```php
// 在模型中使用
class User extends Model
{
    protected function avatar(): Attribute
    {
        return file_upload_handle();
    }
}
```

### file_upload_handle_multi()

处理多个文件上传的显示和存储问题。

```php
function file_upload_handle_multi(): \Illuminate\Database\Eloquent\Casts\Attribute
```

**使用示例：**
```php
// 在模型中使用
class Product extends Model
{
    protected function images(): Attribute
    {
        return file_upload_handle_multi();
    }
}
```

### admin_resource_full_path()

根据路径和服务器信息生成资源的完整访问路径。

```php
function admin_resource_full_path($path, $server = null): array|string|null
```

**参数说明：**
- `$path`: 资源路径
- `$server`: 服务器地址

**使用示例：**
```php
$fullPath = admin_resource_full_path('uploads/avatar.jpg');
```

## 加密和哈希函数

### bcrypt()

对给定值进行 bcrypt 哈希处理。

```php
function bcrypt(string $value, array $options = []): string
```

**参数说明：**
- `$value`: 需要哈希的值
- `$options`: 哈希选项

**使用示例：**
```php
$hashedPassword = bcrypt('mysecretpassword');
```

## 配置和系统函数

### systemConfig()

创建并返回配置服务实例。

```php
function systemConfig(): ConfigService
```

**使用示例：**
```php
$config = systemConfig();
$value = $config->get('app.name');
```

### admin_pages()

根据标识符获取页面结构数据。

```php
function admin_pages($sign)
```

**参数说明：**
- `$sign`: 页面标识符

**使用示例：**
```php
$pageData = admin_pages('dashboard');
```

### cache()

获取缓存实例。

```php
function cache(): Cache
```

**使用示例：**
```php
$cache = cache();
$cache->put('key', 'value', 3600);
$value = $cache->get('key');
```

### appw()

获取容器实例或从容器中解析依赖。

```php
function appw(string|null $abstract = null, array $parameters = []): mixed
```

**参数说明：**
- `$abstract`: 要解析的依赖标识
- `$parameters`: 解析时的参数

**使用示例：**
```php
// 获取容器实例
$container = appw();

// 解析服务
$service = appw(UserService::class);
```

## 异常处理函数

### admin_abort()

抛出管理后台异常。

```php
function admin_abort(string $message = '', array $data = [], int $doNotDisplayToast = 0): mixed
```

### amis_abort()

抛出 Amis 异常（不显示提示）。

```php
function amis_abort(string $message = '', array $data = []): void
```

### admin_abort_if()

条件异常抛出。

```php
function admin_abort_if(bool $flag, string $message = '', array $data = [], int $doNotDisplayToast = 0): void
```

### amis_abort_if()

条件抛出 Amis 异常（不显示提示）。

```php
function amis_abort_if(bool $flag, string $message = '', array $data = []): void
```

## 其他实用函数

### translator()

语言翻译函数。

```php
function translator(string $key, array $replace = [], string|null $locale = null): ?string
```

**参数说明：**
- `$key`: 翻译键名
- `$replace`: 替换参数
- `$locale`: 语言标识

**使用示例：**
```php
// 翻译默认语言
$text = translator('admin.user');

// 带参数替换
$text = translator('admin.welcome', ['name' => 'John']);

// 指定语言
$text = translator('admin.user', [], 'en');
```

### is_json()

检查给定字符串是否为有效的 JSON 格式。

```php
function is_json($string): bool
```

**参数说明：**
- `$string`: 待检查的字符串

**使用示例：**
```php
if (is_json($data)) {
    $json = json_decode($data, true);
}
```

### runCommand()

执行指定的控制台命令。

```php
function runCommand(string $commandName, array $arguments = []): array
```

**参数说明：**
- `$commandName`: 命令名称
- `$arguments`: 命令参数

**返回值：**
- 第一个元素为是否成功
- 第二个为输出内容

**使用示例：**
```php
[$success, $output] = runCommand('warm-plugin:create', ['myplugin']);
```

### url()

根据路由名称生成 URL。

```php
function url($val): string
```

**参数说明：**
- `$val`: 路由名称

**使用示例：**
```php
$url = url('admin.users.index');
```

### abort()

抛出带有指定代码和消息的异常。

```php
function abort($code, $message)
```

**参数说明：**
- `$code`: 错误代码
- `$message`: 错误消息

**使用示例：**
```php
abort(404, '页面未找到');
```

通过以上详细指南，您可以更好地理解和使用 Warm 框架提供的各种辅助函数，提高开发效率和代码质量。