# 微信 API 封装类

本目录包含 easywechat6x 版本的微信 API 封装类，分别定义了公众号和小程序的所有 API 调用。

## 目录结构

```
api/
├── BaseApi.php              # 基础 API 类，提供通用功能
├── OfficialAccountApi.php   # 公众号 API 类
├── MiniProgramApi.php       # 小程序 API 类
├── WechatApiEndpoints.php   # API 端点管理类（统一管理所有 API 端点）
└── README.md               # 本文件
```

## 使用说明

### 公众号 API 使用示例

```php
use warm\common\api\OfficialAccountApi;

// 直接创建实例
$api = new OfficialAccountApi();

// 方式1：使用封装好的方法（推荐）
$result = $api->createMenu($buttons);
$userInfo = $api->getUserInfo('openid123');

// 方式2：链式调用（灵活，可调用所有 easywechat API）
$result = $api->app()->getClient()->postJson('/cgi-bin/menu/create', ['button' => $buttons]);
$userInfo = $api->app()->getClient()->get('/cgi-bin/user/info', [
    'query' => ['openid' => 'openid123', 'lang' => 'zh_CN']
]);

// 方式3：获取 Application 实例后多次使用
$app = $api->app();
$menu = $app->getClient()->get('/cgi-bin/menu/get');
$users = $app->getClient()->get('/cgi-bin/user/get');
```

### 小程序 API 使用示例

```php
use warm\common\api\MiniProgramApi;

// 直接创建实例
$api = new MiniProgramApi();

// 方式1：使用封装好的方法（推荐）
$session = $api->codeToSession('code_from_frontend');
$result = $api->sendSubscribeMessage('openid123', 'template_id', $data, 'pages/index/index');

// 方式2：链式调用（灵活，可调用所有 easywechat API）
$session = $api->app()->getClient()->get('/sns/jscode2session', [
    'query' => [
        'appid' => $api->app()->getConfig()->get('app_id'),
        'secret' => $api->app()->getConfig()->get('secret'),
        'js_code' => 'code_from_frontend',
        'grant_type' => 'authorization_code',
    ],
]);

// 方式3：获取 Application 实例后多次使用
$app = $api->app();
$session = $app->getClient()->get('/sns/jscode2session', [...]);
$qrcode = $app->getClient()->postJson('/wxa/getwxacodeunlimit', [...]);
```

## API 列表

### 公众号 API (OfficialAccountApi)

#### 菜单相关
- `createMenu(array $buttons)` - 创建自定义菜单
- `getMenu()` - 获取当前菜单配置
- `deleteMenu()` - 删除所有菜单

#### 用户相关
- `getUserInfo(string $openid, string $lang = 'zh_CN')` - 获取用户信息
- `batchGetUserInfo(array $openids, string $lang = 'zh_CN')` - 批量获取用户信息
- `getUserList(?string $nextOpenid = null)` - 获取用户列表

#### 消息相关
- `sendTemplateMessage(...)` - 发送模板消息
- `sendCustomMessage(...)` - 发送客服消息

#### 素材管理
- `uploadMedia(string $type, string $path)` - 上传临时素材
- `getMedia(string $mediaId)` - 获取临时素材
- `uploadMaterial(string $path)` - 上传永久素材

#### 二维码相关
- `createTemporaryQrcode($sceneValue, int $expireSeconds)` - 创建临时二维码
- `createPermanentQrcode($sceneValue)` - 创建永久二维码
- `getQrcodeUrl(string $ticket)` - 获取二维码图片 URL

#### OAuth 相关
- `getOAuthUrl(...)` - 获取 OAuth 授权 URL
- `getUserByCode(string $code)` - 通过 code 获取用户 openid

#### 服务器相关
- `verifyServer(...)` - 验证服务器配置
- `parseServerMessage()` - 解析服务器消息

### 小程序 API (MiniProgramApi)

#### 用户相关
- `codeToSession(string $code)` - 通过 code 获取用户 openid 和 session_key
- `getPhoneNumber(string $code)` - 获取用户手机号

#### 消息相关
- `sendSubscribeMessage(...)` - 发送订阅消息
- `sendCustomMessage(...)` - 发送客服消息

#### 二维码相关
- `getUnlimitedQrcode(...)` - 获取小程序码（无数量限制）
- `getQrcode(string $path, int $width)` - 获取小程序码（有数量限制）

#### 数据统计
- `getVisitTrend(string $beginDate, string $endDate)` - 获取访问趋势
- `getUserPortrait(string $beginDate, string $endDate)` - 获取用户画像

#### 内容安全
- `msgSecCheck(string $content)` - 文本内容安全检测
- `imgSecCheck(string $mediaPath)` - 图片内容安全检测

#### 其他
- `getAccessToken()` - 获取 Access Token
- `generateUrlScheme(...)` - 生成小程序 URL Scheme
- `generateUrlLink(...)` - 生成小程序 URL Link

## API 端点管理

所有 API 端点都统一管理在 `WechatApiEndpoints` 类中，方便更新和维护。

### 使用方式

```php
use warm\common\api\WechatApiEndpoints;

// 获取公众号 API 端点
$endpoint = WechatApiEndpoints::officialAccount('menu_create');
// 返回: '/cgi-bin/menu/create'

// 获取小程序 API 端点
$endpoint = WechatApiEndpoints::miniProgram('jscode2session');
// 返回: '/sns/jscode2session'

// 获取所有端点
$allOfficialAccountEndpoints = WechatApiEndpoints::getAllOfficialAccountEndpoints();
$allMiniProgramEndpoints = WechatApiEndpoints::getAllMiniProgramEndpoints();
```

### 更新 API 端点

如果需要更新 API 端点，只需修改 `WechatApiEndpoints` 类中的常量定义即可，所有使用该端点的代码都会自动使用新的端点。

例如，如果微信更新了菜单创建接口：

```php
// 在 WechatApiEndpoints.php 中修改
public const OFFICIAL_ACCOUNT = [
    'menu_create' => '/cgi-bin/menu/create/v2', // 更新为新版本
    // ...
];
```

## 注意事项

1. **easywechat6x 不再内置具体 API 逻辑**：所有 API 调用都需要通过 `getClient()` 方法手动构建请求。

2. **配置从数据库实时加载**：
   - 每次调用 API 方法时，都会从数据库读取最新配置并创建应用实例
   - 不依赖容器缓存，确保每次调用都获取最新配置
   - 如果配置不存在或未启用，API 方法会抛出 `RuntimeException` 异常
   - 无需手动重新加载配置，每次调用都是最新的

3. **错误处理**：所有 API 方法都会返回数组格式的响应，包含 `errcode` 和 `errmsg` 字段。可以通过 `BaseApi::isSuccess()` 方法检查是否成功。

4. **应用实例检查**：如果微信应用实例未初始化（配置未设置），API 方法会抛出 `RuntimeException` 异常。

5. **响应处理**：`BaseApi::handleResponse()` 方法会自动处理响应格式，将对象转换为数组。

6. **无容器依赖**：API 类不再依赖容器，每次调用 API 方法时都从数据库获取最新配置，适合配置频繁更新的场景。

7. **链式调用支持**：
   - 所有 API 方法都支持链式调用
   - 可以通过 `app()` 方法获取 Application 实例，然后链式调用所有 easywechat API
   - 每次调用 `app()` 都会从数据库获取最新配置
   - 示例：`$api->app()->getClient()->postJson('/api/path', $data)`

## 在 WechatMenuService 中使用示例

```php
use warm\common\api\OfficialAccountApi;

public function publish(): bool
{
    // ... 构建菜单数据 ...
    
    // 每次创建实例都会从数据库获取最新配置
    $api = new OfficialAccountApi();
    $result = $api->createMenu($wechatMenu);
    
    if (!$api->isSuccess($result)) {
        $this->setError($api->getErrorMessage($result));
        return false;
    }
    
    return true;
}

// 如果配置刚更新，可以重新加载
public function publishWithReload(): bool
{
    $api = new OfficialAccountApi();
    $api->reloadConfig(); // 重新从数据库加载最新配置
    
    // ... 使用 API ...
}
```
