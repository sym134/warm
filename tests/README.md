# StorageService 单元测试

## 概述

本目录包含 `StorageService` 类的单元测试。

## 运行测试

### 基本命令

```bash
# 运行所有测试
vendor/bin/phpunit tests/

# 运行特定测试文件
vendor/bin/phpunit tests/StorageServiceTest.php

# 运行特定测试方法
vendor/bin/phpunit tests/StorageServiceTest.php --filter testGenerateFilename

# 生成代码覆盖率报告
vendor/bin/phpunit --coverage-html coverage/ tests/
```

## 测试覆盖

### 已覆盖的功能

1. **配置管理**
   - `testInitUploadConfig()` - 测试配置初始化
   - `testConfigCache()` - 测试配置缓存机制
   - `testForceInitConfig()` - 测试强制重新初始化配置

2. **文件名生成**
   - `testGenerateFilename()` - 测试基本文件名生成
   - `testGenerateFilenameForDifferentMimeTypes()` - 测试不同MIME类型的文件名生成

3. **文件验证**
   - `testValidateImageSuccess()` - 测试图片验证成功
   - `testValidateImageNonImageFile()` - 测试非图片文件验证失败
   - `testValidateImageExceedsMaxSize()` - 测试文件大小超限
   - `testValidateImageDisallowedExtension()` - 测试不允许的扩展名
   - `testValidateFileSuccess()` - 测试普通文件验证成功
   - `testValidateFileRejectsImage()` - 测试普通文件验证拒绝图片

4. **文件上传**
   - `testUploadImage()` - 测试图片上传
   - `testUploadVideo()` - 测试视频上传
   - `testUploadAudio()` - 测试音频上传
   - `testUploadFile()` - 测试普通文件上传
   - `testUploadWithCustomFilename()` - 测试自定义文件名上传

## 注意事项

### 依赖 Mock

由于 `StorageService` 使用了以下依赖，在测试中需要进行 Mock：

1. **systemConfig()** - 全局配置函数
   - 需要使用 `uopz` 扩展或通过依赖注入来模拟
   - 当前测试中使用全局变量作为临时方案

2. **Storage Facade** - 静态文件存储 Facade
   - 需要在测试环境中配置容器绑定
   - 或使用真实的文件系统适配器进行集成测试

### 推荐测试策略

1. **单元测试** - 测试独立的方法逻辑
   - 使用 Mock 对象隔离依赖
   - 测试边界条件和异常情况

2. **集成测试** - 测试完整的上传流程
   - 使用真实的文件系统
   - 验证文件实际保存和路径生成

3. **功能测试** - 测试端到端功能
   - 模拟 HTTP 请求
   - 验证整个上传流程

## 环境要求

- PHP >= 8.1
- PHPUnit ^11.5
- 可选：uopz 扩展（用于更好的函数 Mock 支持）

## 运行前准备

确保以下配置正确：

1. `phpunit.xml` 配置正确
2. 测试目录具有写权限
3. 如果使用 uopz 扩展，需要安装并启用

## 故障排除

### 问题：systemConfig() 无法 Mock

**解决方案：**
- 安装 uopz 扩展：`pecl install uopz`
- 或在测试中使用真实的配置服务

### 问题：Storage Facade 无法 Mock

**解决方案：**
- 在测试引导文件中配置容器绑定
- 或创建测试专用的 Storage 实现

### 问题：临时文件无法创建

**解决方案：**
- 检查系统临时目录权限
- 确保 `sys_get_temp_dir()` 返回的目录可写

## 贡献

添加新测试时，请遵循以下规范：

1. 测试方法命名：`test{MethodName}{Scenario}()`
2. 每个测试应该独立，不依赖其他测试的执行顺序
3. 使用 `setUp()` 和 `tearDown()` 管理测试环境
4. 为每个测试添加清晰的注释说明测试目的

