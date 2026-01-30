# Warm - Project AI Prompt

本文档提供了 Warm 项目的全面概述，包括架构、开发规范和核心概念。旨在帮助 AI 编辑器和开发者理解代码库。

## 1. 项目概述

Warm 是一个基于 Webman 框架的后台管理系统插件，提供了完整的后台管理功能。

- **后端框架**: Webman 2.1
- **前端框架**: Amis（低代码前端框架）
- **ORM**: Laravel Eloquent (Illuminate Database)
- **认证**: jizhi/webman-auth
- **插件系统**: 支持插件开发、安装、卸载和启用/禁用

## 2. 技术栈

### 后端
- **框架**: Webman 2.1 (`workerman/webman-framework`)
- **语言**: PHP >= 8.1
- **数据库**: MySQL >= 5.7
- **缓存**: Redis (webman/redis, webman/cache)
- **ORM**: Laravel Eloquent (`illuminate/database`)
- **关键依赖**:
  - `illuminate/database`: Eloquent ORM
  - `illuminate/pagination`: 分页
  - `illuminate/events`: 事件系统
  - `illuminate/hashing`: 密码哈希
  - `jizhi/webman-auth`: 认证系统
  - `w7corp/easywechat`: 微信 SDK
  - `overtrue/easy-sms`: 短信服务
  - `intervention/image`: 图片处理
  - `rap2hpoutre/fast-excel`: Excel 导出
  - `topthink/think-validate`: 数据验证

### 前端
- **框架**: Amis（低代码前端框架）
- **资源位置**: `public/admin-assets/` 或独立部署
- **前后端分离**: 支持前后端独立部署

### 存储适配器
支持多种存储适配器（可选安装）：
- 本地存储: `league/flysystem-local`
- 阿里云 OSS: `iidestiny/flysystem-oss`
- AWS S3: `league/flysystem-aws-s3-v3`
- 七牛云: `overtrue/flysystem-qiniu`
- 腾讯云 COS: `overtrue/flysystem-cos`
- 内存存储: `league/flysystem-memory`

## 3. 目录结构

### 项目根目录
```
.
├── config/                    # 配置文件目录
│   ├── plugin/               # 插件配置目录
│   │   └── jizhi/warm/      # Warm 插件配置
│   ├── app.php              # 应用配置
│   ├── database.php         # 数据库配置
│   ├── redis.php            # Redis 配置
│   └── ...
├── plugin/                   # 插件目录
├── public/                   # 公共资源目录
│   └── admin-assets/        # 后台资源文件
├── resource/                 # 资源目录
│   └── translations/        # 多语言文件
├── vendor/                   # Composer 依赖
│   └── jizhi/warm/          # Warm 插件源码
└── webman                    # Webman 入口文件
```

### Warm 插件源码结构 (`vendor/jizhi/warm/src/`)
```
src/
├── admin/                    # 后台管理模块
│   ├── controller/          # 控制器（AdminController 基类）
│   ├── middleware/          # 中间件
│   ├── model/               # 模型（AdminUser, AdminRole, AdminPermission, AdminMenu）
│   ├── service/             # 服务类
│   ├── renderer/            # Amis 渲染器
│   ├── support/             # 支持类（JsonResponse, Menu, Permission）
│   └── trait/               # 特性（ExportTrait, UploadTrait, ElementTrait）
├── command/                 # 命令行工具
├── common/                  # 公共模块
│   ├── model/              # 基础模型（BaseModel）
│   ├── service/            # 公共服务（SystemConfigService, StorageService）
│   └── enum/               # 枚举类
├── config/                  # 配置文件
│   └── plugin/jizhi/warm/  # 插件配置
├── framework/              # 框架扩展
│   ├── cache/              # 缓存门面
│   ├── filesystem/         # 文件系统
│   └── hash/               # 哈希门面
├── resource/               # 资源文件
│   └── translations/       # 多语言翻译
├── admin-assets/           # 前端资源
└── helpers.php             # 辅助函数
```

## 4. 开发规范

### PHP 命名规范

#### 类命名
- **类名**: 使用大驼峰命名法（PascalCase），每个单词首字母大写
  - 示例: `AdminController`, `WechatReplyService`, `SystemConfigService`
- **抽象类**: 以 `Abstract` 开头
  - 示例: `AbstractBaseModel`, `AbstractService`
- **接口**: 以 `Interface` 结尾或使用 `I` 前缀
  - 示例: `FilesystemAdapterInterface`, `IStorageAdapter`
- **特性（Trait）**: 以 `Trait` 结尾
  - 示例: `ExportTrait`, `UploadTrait`, `ElementTrait`
- **异常类**: 以 `Exception` 结尾
  - 示例: `ValidationException`, `PermissionException`

#### 方法命名
- **方法名**: 使用小驼峰命名法（camelCase），首字母小写
  - 示例: `getUserInfo()`, `saveData()`, `beforeSave()`
- **私有/受保护方法**: 使用小驼峰命名法，通常以动词开头
  - 示例: `validateData()`, `formatResponse()`, `processUpload()`
- **布尔方法**: 使用 `is`, `has`, `can` 等前缀
  - 示例: `isAdmin()`, `hasPermission()`, `canEdit()`

#### 变量命名
- **变量名**: 使用小驼峰命名法（camelCase）
  - 示例: `$userId`, `$userInfo`, `$configData`
- **常量**: 使用全大写下划线分隔（UPPER_SNAKE_CASE）
  - 示例: `MAX_FILE_SIZE`, `DEFAULT_PAGE_SIZE`, `STATUS_ACTIVE`
- **私有/受保护属性**: 使用小驼峰命名法，通常以 `$` 开头
  - 示例: `protected $serviceName`, `private $cacheKey`

#### 函数命名
- **函数名**: 使用小写下划线分隔（snake_case）
  - 示例: `translator()`, `validate()`, `storage()`
- **全局函数**: 遵循 PSR 标准，使用小写下划线分隔

#### 命名空间
- **命名空间**: 使用小写字母，与目录结构对应
  - 示例: `warm\admin\controller`, `warm\common\service`
- **命名空间层级**: 遵循 PSR-4 自动加载规范
  - 根命名空间对应 `src/` 目录

#### 文件命名
- **类文件**: 文件名与类名一致，使用大驼峰命名法
  - 示例: `AdminController.php`, `WechatReplyService.php`
- **配置文件**: 使用小写下划线分隔
  - 示例: `app.php`, `database.php`, `route.php`
- **辅助函数文件**: 使用小写下划线分隔
  - 示例: `helpers.php`, `functions.php`

### 数据库规范

#### 表命名
- **表名**: 使用小写下划线分隔（snake_case），使用复数形式
  - 示例: `admin_users`, `system_configs`, `wechat_replies`
- **表前缀**: 根据模块使用适当的前缀
  - 后台管理: `admin_` 前缀（如 `admin_users`, `admin_roles`）
  - 系统配置: `system_` 前缀（如 `system_configs`）
  - 业务模块: 使用模块名作为前缀（如 `wechat_replies`, `wechat_menus`）

#### 字段命名
- **字段名**: 使用小写下划线分隔（snake_case）
  - 示例: `user_id`, `created_at`, `is_active`, `config_value`
- **主键**: 统一使用 `id`，类型为 `bigint unsigned` 或 `int unsigned`
- **外键**: 使用 `表名单数_id` 格式
  - 示例: `user_id`, `role_id`, `menu_id`
- **布尔字段**: 使用 `is_` 或 `has_` 前缀
  - 示例: `is_active`, `is_deleted`, `has_permission`
- **时间字段**: 统一使用 `created_at`, `updated_at`, `deleted_at`
  - 使用 `timestamp` 或 `datetime` 类型
  - `deleted_at` 用于软删除

#### 索引命名
- **主键索引**: `PRIMARY`
- **唯一索引**: `uk_表名_字段名`（uk = unique key）
  - 示例: `uk_users_email`, `uk_configs_group_name`
- **普通索引**: `idx_表名_字段名`（idx = index）
  - 示例: `idx_users_status`, `idx_posts_user_id`
- **外键索引**: `fk_表名_字段名`（fk = foreign key）
  - 示例: `fk_posts_user_id`, `fk_comments_post_id`
- **复合索引**: `idx_表名_字段1_字段2`
  - 示例: `idx_users_status_created_at`

#### 字段类型规范
- **整数类型**: 
  - 主键/外键: `bigint unsigned` 或 `int unsigned`
  - 状态码/枚举: `tinyint` 或 `smallint`
  - 数量/计数: `int` 或 `bigint`
- **字符串类型**:
  - 短字符串（< 255）: `varchar(长度)`
  - 长文本: `text` 或 `longtext`
  - 固定长度: `char(长度)`
- **数值类型**:
  - 金额/价格: `decimal(10, 2)` 或 `decimal(15, 2)`
  - 百分比: `decimal(5, 2)`
- **时间类型**:
  - 时间戳: `timestamp` 或 `datetime`
  - 日期: `date`
  - 时间: `time`

#### 表结构规范
- **必须字段**: 所有表应包含以下字段（除非特殊需求）
  - `id`: 主键，自增
  - `created_at`: 创建时间
  - `updated_at`: 更新时间
- **软删除**: 需要软删除的表添加 `deleted_at` 字段（`timestamp nullable`）
- **排序字段**: 需要排序的表添加 `sort` 字段（`int default 0`）
- **状态字段**: 需要状态管理的表添加 `status` 或 `is_active` 字段

#### 注释规范
- **表注释**: 每个表必须有注释，说明表的用途
  ```sql
  CREATE TABLE `admin_users` (
    ...
  ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='管理员用户表';
  ```
- **字段注释**: 重要字段必须有注释，说明字段用途和取值范围
  ```sql
  `status` tinyint(1) NOT NULL DEFAULT 1 COMMENT '状态：0=禁用，1=启用',
  `type` varchar(20) NOT NULL COMMENT '类型：admin=管理员，user=普通用户',
  ```

#### 数据库操作规范
- **使用 ORM**: 优先使用 Laravel Eloquent ORM，避免直接写 SQL
- **查询构建**: 使用 Eloquent 查询构建器，保持代码可读性
- **事务处理**: 涉及多表操作时使用数据库事务
- **批量操作**: 使用批量插入/更新方法，提高性能
- **避免 N+1 查询**: 使用 `with()` 或 `load()` 预加载关联数据

### 后端架构

#### Controller -> Service -> Model 架构
- **Controller**: 处理 HTTP 请求，参数验证，调用 Service
  - 所有后台控制器继承自 `warm\admin\controller\AdminController`
  - 使用 `$serviceName` 属性指定对应的 Service 类
  - 定义 `$noNeedLogin` 和 `$noNeedAuth` 数组控制权限
- **Service**: 业务逻辑实现
  - 继承自 `warm\admin\service\AdminService`
  - 实现 `list()`, `form()`, `detail()` 等方法
  - 使用钩子方法（`beforeSave`, `afterSave` 等）扩展功能
- **Model**: 数据库交互
  - 继承自 `warm\common\model\BaseModel`
  - 使用 Laravel Eloquent ORM

#### 路由配置
- 路由定义在 `config/plugin/jizhi/warm/route.php`
- 支持自动路由生成：`php webman warm-gen:route`
- 管理后台路由前缀可通过配置修改

#### 响应格式
- 使用 `Admin::response()->success($data, $msg)` 返回成功响应
- 使用 `Admin::response()->fail($msg, $code)` 返回失败响应
- 标准 JSON 结构：`{code, msg, data}`

#### 认证系统
- 使用 `jizhi/webman-auth` 进行认证
- 通过 `Admin::guard()` 获取认证守卫
- 通过 `Admin::user()` 获取当前登录用户
- 中间件自动处理认证和权限验证

### 核心类和方法

#### Admin 类 (`warm\admin\Admin`)
```php
// 创建响应对象
Admin::response()->success($data, $msg);

// 获取当前用户
Admin::user();

// 获取配置服务
Admin::config();

// 获取菜单管理对象
Admin::menu();

// 获取权限管理对象
Admin::permission();

// 获取 Warm 配置
Admin::warmConfig('key', 'default');
```

#### AdminController 基类
```php
// 核心属性
protected array $noNeedLogin = [];      // 不需要登录的方法
protected array $noNeedAuth = [];       // 不需要权限验证的方法
protected string $serviceName = '';     // 服务类名称
protected object $service;              // 服务类实例

// 核心方法
public function index();                // 列表页
public function create();                // 新增页
public function edit();                  // 编辑页
public function detail();                // 详情页
public function save();                  // 保存数据
public function delete();                // 删除数据
```

#### AdminService 基类
```php
// 核心方法
public function list();                  // 获取列表数据
public function form();                  // 获取表单数据
public function detail();                // 获取详情数据
public function save($data);            // 保存数据

// 钩子方法
protected function beforeSave($data);   // 保存前钩子
protected function afterSave($model);   // 保存后钩子
protected function beforeDelete($id);   // 删除前钩子
protected function afterDelete($id);    // 删除后钩子
```

### 数据验证
- 使用 `topthink/think-validate` 进行数据验证
- 验证器类继承自 `think\Validate`
- 在 Controller 中使用 `validate()` 辅助函数进行验证

### 模型使用
- 所有模型继承自 `warm\common\model\BaseModel`
- 使用 Laravel Eloquent 语法进行数据库操作
- 支持软删除、时间戳等特性

### 文件上传
- 使用 `Storage` 门面进行文件操作
- `Storage::disk('public')->putFile($path, $file)` 上传文件
- `Storage::disk('public')->url($path)` 获取文件 URL
- 支持多种存储驱动（本地、OSS、S3、七牛云、COS等）

### 多语言
- 使用 `translator()` 函数进行多语言翻译
- 翻译文件位于 `resource/translations/` 目录
- 格式：`translator('插件名::文件名.键', [], 'locale')`

## 5. 配置管理

### 环境配置
- 复制 `.example.env` 为 `.env`
- 配置数据库连接：`DB_HOST`, `DB_NAME`, `DB_USER`, `DB_PASSWORD`
- 配置 Redis：`REDIS_HOST`, `REDIS_PORT`, `REDIS_PASSWORD`

### Warm 配置
- 配置文件位于 `config/plugin/jizhi/warm/app.php`
- 通过 `Admin::warmConfig('key', 'default')` 获取配置
- 主要配置项：
  - `app.route.prefix`: 管理后台路由前缀
  - `app.auth.guard`: 认证守卫名称
  - `app.models.*`: 模型类映射

### 系统配置
- 使用 `SystemConfigService` 管理系统配置
- `Admin::config()->get('group', 'name')` 获取配置值
- 配置存储在 `system_config` 数据表中

## 6. 插件系统

### 插件特性
- 独立的目录结构
- 独立的配置文件
- 独立的路由配置
- 可动态启用/禁用
- 支持安装和卸载

### 创建插件
```bash
php webman warm-plugin:create 插件名 插件标题 作者名
```

### 插件结构
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
    │   └── functions.php
    ├── config/
    │   ├── app.php
    │   ├── menu.php
    │   └── route.php
    ├── public/
    ├── resource/
    │   └── translations/
    ├── install.sql
    └── uninstall.sql
```

### 插件配置
插件核心配置文件 `config/app.php`：
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

## 7. 权限系统

### RBAC 权限模型
- **用户（AdminUser）**: 系统用户
- **角色（AdminRole）**: 用户角色
- **权限（AdminPermission）**: 具体权限点
- **菜单（AdminMenu）**: 后台菜单项

### 权限控制流程
1. 用户关联角色
2. 角色关联权限
3. 请求时检查用户是否有访问权限

### 权限中间件
- `Authenticate`: 认证中间件，检查用户是否登录
- `Permission`: 权限中间件，检查用户是否有权限访问

### 权限配置
- 在 Controller 中定义 `$noNeedLogin` 和 `$noNeedAuth` 数组
- 通过 `Admin::permission()` 管理权限
- 通过 `Admin::menu()` 管理菜单

## 8. 命令行工具

```bash
# 安装 Warm
php webman warm:install

# 创建插件
php webman warm-plugin:create 插件名 插件标题 作者名

# 生成路由配置
php webman warm-gen:route

# 数据库迁移
php webman migrate
php webman migrate:make 迁移名称
php webman migrate:refresh
php webman migrate:rollback
```

## 9. 常用模式和最佳实践

### 配置获取
- **系统配置**: `Admin::config()->get('group', 'name')`
- **Warm 配置**: `Admin::warmConfig('key', 'default')`
- **环境变量**: `env('KEY', 'default')`

### 文件处理
- **上传文件**: `Storage::disk('public')->putFile($path, $file)`
- **获取文件 URL**: `Storage::disk('public')->url($path)`
- **删除文件**: `Storage::disk('public')->delete($path)`

### 认证和授权
- **获取当前用户**: `Admin::user()`
- **获取认证守卫**: `Admin::guard()`
- **检查权限**: 通过中间件自动处理

### 响应处理
- **成功响应**: `Admin::response()->success($data, $msg)`
- **失败响应**: `Admin::response()->fail($msg, $code)`
- **分页响应**: Service 的 `list()` 方法自动处理分页

### 数据导出
- 使用 `ExportTrait` 特性
- 在 Controller 中调用 `export()` 方法
- 支持 Excel 格式导出

### 快速编辑
- 使用 `ElementTrait` 特性
- 在 Service 中定义快速编辑字段
- 前端自动生成快速编辑功能

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
