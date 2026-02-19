---
name: "warm-architect"
description: "Use this agent when developing features for the Warm Webman-based admin system, ensuring compliance with its architecture (Controller->Service->Model), naming conventions, database standards, and the mandatory 4-step execution flow (task breakdown → coding → validation → self-check)."
---

# Warm Architect

This skill enforces the architecture, coding standards, and execution flow for the **Warm** project (a Webman + Amis + Eloquent based admin system).

## 核心架构 (Core Architecture)

- **Framework**: Webman 2.1 (`workerman/webman-framework`)
- **Frontend**: Amis (Low-code JSON UI)
- **ORM**: Laravel Eloquent (`illuminate/database`)
- **Pattern**: Controller -> Service -> Model (MVC/S)
- **Auth**: `jizhi/webman-auth`

## 目录结构 (Directory Structure)

- `src/admin/controller/` - Controllers (Must extend `warm\admin\controller\AdminController`)
- `src/admin/service/` - Business Logic (Must extend `warm\admin\service\AdminService`)
- `src/admin/model/` - Models (Must extend `warm\common\model\BaseModel`)
- `config/plugin/jizhi/warm/route.php` - Routes (Auto-generated via `php webman warm-gen:route`)
- `src/admin/renderer/` - Amis Renderers

## 开发规范 (Development Standards)

### 命名 (Naming)
- **Classes**: PascalCase (`UserParams`)
- **Methods/Variables**: camelCase (`$userId`, `getUserInfo`)
- **Tables**: snake_case (plural), e.g., `admin_users`
- **PK**: `id` (bigint unsigned)
- **Constants**: UPPER_SNAKE_CASE (`MAX_FILE_SIZE`)

### 核心类 (Core Classes)
- **Response**: `Admin::response()->success($data, $msg)` / `Admin::response()->fail($msg, $code)`
- **Auth**: `Admin::user()`, `Admin::guard()`
- **Config**: `Admin::warmConfig('key')` / `Admin::config()->get('group', 'name')`
- **File**: `Storage::disk('public')->putFile($path, $file)`
- **Lang**: `translator('plugin::file.key')`

### 数据库 (Database)
- **Engine**: Eloquent ORM (No raw SQL allowed)
- **Soft Delete**: `deleted_at` (timestamp nullable)
- **Timestamps**: `created_at`, `updated_at` (datetime/timestamp)
- **Validation**: `topthink/think-validate`

## 强制执行流程 (Mandatory Execution Flow)

You MUST follow these 4 steps for every task:

### 1. 任务拆解 (Task Breakdown)
- List files to create/modify (absolute paths).
- Define Controller/Service/Model responsibilities.
- Check dependencies (Avoid adding new ones unless critical).
- Identify "Forbidden Zones" (Core logic that shouldn't be touched).

### 2. 编码执行 (Coding)
- Strict adherence to naming/directory rules.
- Use Eloquent ORM (No raw SQL).
- Add PHPDoc comments to classes and methods.
- Check for Webman specific constraints (e.g., no global state per request).

### 3. 输出验证 (Verification)
- Provide full code with paths.
- Provide run commands:
  - Windows: `php windows.php`
  - Linux/Mac: `php webman start`
- Provide test commands (curl example).
- Explain verification steps (e.g., check database, check response JSON).

### 4. 自检修正 (Self-Check)
- Verify against all rules above.
- Ensure code is runnable.
- Check for hardcoded values or potential SQL injections.
