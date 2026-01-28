# 文件管理系统部署说明

## 概述

本文档说明如何使用 Warm 框架开发的完整文件管理系统。该系统提供了文件上传、下载、删除、重命名、移动、分组管理等完整功能。

## 功能特性

### 核心功能
1. **文件列表展示**
   - 支持卡片视图（网格模式）
   - 支持列表视图（表格模式）
   - 支持两种视图模式切换

2. **文件操作**
   - 文件上传（支持多文件上传）
   - 文件下载
   - 文件删除（单个/批量）
   - 文件重命名
   - 文件移动（移动到分组）

3. **分组管理**
   - 创建分组
   - 删除分组
   - 分组列表展示（左侧边栏）
   - 按分组筛选文件

4. **搜索和筛选**
   - 按文件名称搜索
   - 按文件类型筛选（图片/视频/文件）
   - 按存储来源筛选（本地/七牛/阿里云/腾讯云）

## 数据库结构

### SystemFile 模型字段

文件管理系统使用 `system_files` 数据表，主要字段包括：

- `id`: 文件ID（主键）
- `storage_mode`: 存储模式（local/qiniu/aliyun/qcloud）
- `origin_name`: 原始文件名
- `new_name`: 存储后的文件名
- `hash`: 文件哈希值
- `file_type`: 文件类型（image/video/audio/file）
- `mime_type`: MIME类型
- `storage_path`: 存储路径
- `size_byte`: 文件大小（字节）
- `file_size`: 文件大小（KB）
- `url`: 文件访问URL
- `remark`: 备注（用于存储分组名称）
- `group_id`: 分组ID
- `created_by`: 创建者ID
- `created_at`: 创建时间

### 分组管理

分组信息存储在 `group_id` 字段中。系统使用以下方式管理分组：

1. **创建分组**: 生成唯一的 `group_id`（格式：`group_时间戳_随机数`）
2. **分组名称**: 存储在某个标记文件的 `remark` 字段中（origin_name 为 `.group`）
3. **分组文件**: 所有属于该分组的文件的 `group_id` 字段设置为相同的值

## 部署步骤

### 1. 确保数据库表存在

确保 `system_files` 数据表已创建。如果没有，请运行迁移命令：

```bash
php webman migrate
```

### 2. 检查路由配置

确保路由配置文件中包含文件管理相关的路由。路由定义通常在 `config/plugin/jizhi/warm/route.php` 中：

```php
// 文件管理路由
Route::group('/system/files', function () {
    Route::get('/groups', [SystemFileController::class, 'groups']);
    Route::post('/upload', [SystemFileController::class, 'upload']);
    Route::get('/download', [SystemFileController::class, 'download']);
    Route::post('/rename', [SystemFileController::class, 'rename']);
    Route::post('/move', [SystemFileController::class, 'move']);
    Route::post('/createGroup', [SystemFileController::class, 'createGroup']);
    Route::delete('/deleteGroup', [SystemFileController::class, 'deleteGroup']);
});
```

### 3. 配置存储驱动

在 `config/filesystems.php` 中配置文件存储驱动：

```php
return [
    'default' => 'public',
    'disks' => [
        'public' => [
            'driver' => 'local',
            'root' => runtime_path('storage'),
            'url' => '/storage',
        ],
        // 其他存储驱动配置...
    ],
];
```

### 4. 设置文件权限

确保存储目录具有写入权限：

```bash
chmod -R 755 runtime/storage
chown -R www-data:www-data runtime/storage  # 根据实际情况调整用户和组
```

### 5. 访问文件管理页面

在浏览器中访问：
```
http://your-domain.com/admin/system/files
```

## API 接口说明

### 1. 获取分组列表

**请求**: `GET /admin/system/files/groups`

**响应**:
```json
{
    "code": 0,
    "msg": "success",
    "data": {
        "items": [
            {
                "id": null,
                "name": "全部",
                "count": 100,
                "to": "?group_id="
            },
            {
                "id": "ungrouped",
                "name": "未分组",
                "count": 20,
                "to": "?group_id=ungrouped"
            },
            {
                "id": "group_1234567890_1234",
                "name": "分组名称",
                "count": 30,
                "to": "?group_id=group_1234567890_1234"
            }
        ]
    }
}
```

### 2. 文件上传

**请求**: `POST /admin/system/files/upload`

**参数**:
- `file`: 文件（multipart/form-data）
- `group_id`: 分组ID（可选）

**响应**:
```json
{
    "code": 0,
    "msg": "上传成功",
    "data": {
        "value": "文件路径",
        "id": 文件ID
    }
}
```

### 3. 文件下载

**请求**: `GET /admin/system/files/download?id=文件ID`

**响应**: 文件内容（二进制流）

### 4. 文件重命名

**请求**: `POST /admin/system/files/rename`

**参数**:
- `id`: 文件ID
- `name`: 新文件名

**响应**:
```json
{
    "code": 0,
    "msg": "重命名成功"
}
```

### 5. 文件移动

**请求**: `POST /admin/system/files/move`

**参数**:
- `ids`: 文件ID列表（数组或逗号分隔的字符串）
- `group_id`: 目标分组ID（可选，留空表示未分组）

**响应**:
```json
{
    "code": 0,
    "msg": "移动成功"
}
```

### 6. 创建分组

**请求**: `POST /admin/system/files/createGroup`

**参数**:
- `name`: 分组名称

**响应**:
```json
{
    "code": 0,
    "msg": "创建分组成功",
    "data": {
        "groupId": "group_1234567890_1234"
    }
}
```

## 前端界面说明

### 主要区域

1. **顶部工具栏**
   - 文件类型标签切换（图片/视频/文件）
   - 本地上传按钮
   - 删除/移动按钮（批量操作时显示）
   - 视图切换（网格/列表）
   - 搜索框
   - 文件来源筛选
   - 刷新按钮

2. **左侧边栏**
   - 分组列表导航
   - 添加分组按钮

3. **主内容区**
   - 文件卡片/列表展示
   - 文件预览
   - 文件操作按钮（下载/重命名/删除）

4. **底部工具栏**
   - 统计信息
   - 全选复选框
   - 分页控件

### 卡片视图

每个文件卡片包含：
- 文件预览（图片显示缩略图，视频/文件显示图标）
- 文件名（可截断显示，鼠标悬停显示完整名称）
- 文件大小和创建日期
- 操作按钮（下载/重命名/删除）

### 列表视图

表格模式显示，包含以下列：
- 预览
- 文件名
- 文件类型
- 文件大小
- 创建时间
- 操作

## 自定义配置

### 修改每页显示数量

在 `SystemFileController::list()` 方法中修改：

```php
->perPage(18)  // 修改为你需要的数量
```

### 修改卡片列数

在 `SystemFileController::list()` 方法中修改：

```php
->columnsCount(6)  // 修改为你需要的列数
```

### 修改支持的文件类型

文件类型自动识别，基于 MIME 类型。如需修改验证规则，请查看 `StorageService` 类。

## 注意事项

1. **文件存储**: 确保存储驱动配置正确，且有足够的磁盘空间
2. **文件权限**: 确保 Web 服务器对存储目录有读写权限
3. **大文件上传**: 如需支持大文件上传，请调整 PHP 配置：
   - `upload_max_filesize`
   - `post_max_size`
   - `max_execution_time`
4. **分组管理**: 删除分组时，分组下的文件会自动移动到未分组状态
5. **物理文件删除**: 删除文件时，会同时删除数据库记录和物理文件（如果文件存在）

## 故障排查

### 文件上传失败

1. 检查存储目录权限
2. 检查 PHP 上传配置
3. 检查磁盘空间
4. 查看错误日志

### 分组列表不显示

1. 检查数据库连接
2. 检查 `group_id` 字段是否存在
3. 查看 Service 日志

### 文件预览不显示

1. 检查文件 URL 是否正确
2. 检查存储驱动配置
3. 检查文件是否实际存在

## 扩展开发

### 添加自定义文件类型

1. 在 `StorageService::upload()` 方法中添加新的 MIME 类型判断
2. 在 `SystemFile::FILE_TYPE` 常量中添加新的类型
3. 在前端卡片视图中添加对应的图标显示

### 添加文件预览功能

可以在文件卡片中添加预览功能，使用 Amis 的 Dialog 组件：

```php
[
    'type' => 'button',
    'icon' => 'fa fa-eye',
    'tooltip' => '预览',
    'actionType' => 'dialog',
    'dialog' => [
        'title' => '文件预览',
        'body' => [
            'type' => 'image',
            'src' => '${url}',
        ],
    ],
]
```

## 版本历史

- v1.0.0 (2024-xx-xx): 初始版本
  - 文件上传/下载/删除
  - 文件重命名/移动
  - 分组管理
  - 卡片视图和列表视图
  - 搜索和筛选功能

## 技术支持

如有问题，请参考：
- [Warm 框架文档](docs/warm-guide.md)
- [AdminController 指南](docs/admin-controller-guide.md)
- [Amis 组件文档](https://aisuda.bce.baidu.com/amis/zh-CN/components/page)
