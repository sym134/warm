# 支付功能使用说明

## 目录

- [简介](#简介)
- [支持平台](#支持平台)
- [配置管理](#配置管理)
  - [后台配置](#后台配置)
  - [证书与密钥](#证书与密钥)
  - [回调地址](#回调地址)
- [发起支付](#发起支付)
  - [获取支付实例](#获取支付实例)
  - [示例：微信 JSAPI](#示例微信-jsapi)
  - [示例：支付宝网页支付](#示例支付宝网页支付)
- [异步回调](#异步回调)
  - [回调路由](#回调路由)
  - [平台识别](#平台识别)
  - [业务处理：监听事件](#业务处理监听事件)
- [注意事项](#注意事项)

---

## 简介

本插件基于 [yansongda/pay](https://github.com/yansongda/pay) 提供统一支付能力，支持多种支付渠道的配置管理、下单与异步通知处理。配置结构对齐 yansongda/pay，敏感信息加密存储，证书文件统一上传至 `/resource/app/{平台}/` 目录。

---

## 支持平台

| 平台 ID | 名称     | 说明                         |
|--------|----------|------------------------------|
| wechat | 微信支付 | 支持 V2 / V3，含公众号、小程序、APP |
| alipay | 支付宝   | 含网页、APP、小程序等           |
| unipay | 银联支付 | 银联条码支付综合前置平台         |
| douyin | 抖音支付 | 抖音小程序支付                  |
| jsb    | 江苏银行 | 江苏银行支付                   |

---

## 配置管理

### 后台配置

1. 登录管理后台，进入 **系统管理 → 支付配置**。
2. 在列表中点击某一平台的 **编辑**，进入该平台配置页。
3. 打开 **启用** 开关，填写该平台所需参数（如 `app_id`、`mch_id`、证书等）。
4. 证书类配置使用 **上传证书**，文件会保存到 `resource/app/{平台}/` 下，配置中存储相对路径。
5. 保存后，该平台即可用于发起支付与接收回调。

未启用的平台不会出现在 `PaymentService::getConfig()` 中，调用对应渠道会抛错。

### 证书与密钥

- **证书路径**：所有证书通过上传组件配置，保存路径形如 `resource/app/alipay/xxx.crt`。构建 Pay 配置时，会转换为 `base_path()` 下的绝对路径。
- **敏感字段**：如应用私钥、商户密钥、证书密码等会加密存储，详见 `ConfigDefaults::getPaymentConfigSensitiveFields()`。

### 回调地址

各平台配置中的 `notify_url` 需填写 **异步通知回调地址**，且必须可被对应支付平台公网访问。推荐用法：

- **微信**：`https://你的域名/payment/callback/wechat`
- **支付宝**：`https://你的域名/payment/callback/alipay`
- **银联 / 抖音 / 江苏银行**：可使用统一入口 `https://你的域名/payment/callback` 自动识别，或使用独立地址：
  - 银联：`https://你的域名/payment/callback/unipay`
  - 抖音：`https://你的域名/payment/callback/douyin`
  - 江苏银行：`https://你的域名/payment/callback/jsb`

---

## 发起支付

### 获取支付实例

通过 `PaymentManager` 获取已配置平台的 Pay 实例（即 yansongda/pay 的 Provider）：

```php
use warm\common\service\payment\PaymentManager;

// 微信支付 V3（默认）
$wechat = PaymentManager::wechat();

// 微信支付 V2
$wechatV2 = PaymentManager::wechatV2();

// 支付宝
$alipay = PaymentManager::alipay();

// 银联、抖音、江苏银行
$unipay = PaymentManager::unipay();
$douyin = PaymentManager::douyin();
$jsb = PaymentManager::jsb();

// 或统一入口（仅微信可传 version）
$instance = PaymentManager::getInstance('alipay');
$instance = PaymentManager::getInstance('wechat', 'v3');
```

获取前请确保对应平台已在后台 **启用** 并配置完整，否则会抛出 `RuntimeException`。

判断平台是否启用：

```php
PaymentManager::isEnabled('wechat'); // true/false
PaymentManager::getEnabledPlatforms(); // ['wechat', 'alipay', ...]
```

### 示例：微信 JSAPI

```php
use warm\common\service\payment\PaymentManager;

$wechat = PaymentManager::wechat();

$params = [
    'description' => '商品描述',
    'out_trade_no' => 'ORD' . date('YmdHis') . mt_rand(1000, 9999),
    'amount' => [
        'total' => 1, // 单位：分
    ],
    'payer' => [
        'openid' => '用户 openid',
    ],
];

$result = $wechat->mp($params);
// $result 为 yansongda/pay 返回结构，含调起支付所需参数等，具体见 SDK 文档
```

其它场景（Native、H5、小程序等）同样通过 `$wechat->xxx($params)` 调用，参数格式见 [yansongda/pay 文档](https://github.com/yansongda/pay)。

### 示例：支付宝网页支付

```php
use warm\common\service\payment\PaymentManager;

$alipay = PaymentManager::alipay();

$params = [
    'subject' => '商品名称',
    'out_trade_no' => 'ORD' . date('YmdHis') . mt_rand(1000, 9999),
    'total_amount' => '0.01',
];

$result = $alipay->web($params);
// 一般返回支付跳转 URL 或表单 HTML，详见 SDK
```

---

## 异步回调

### 回调路由

支付平台的异步通知通过以下路由接收（公共接口，无需登录）：

| 路径 | 说明 |
|------|------|
| `ANY /payment/callback` | 统一入口，根据请求自动识别平台 |
| `ANY /payment/callback/wechat` | 明确指定微信 |
| `ANY /payment/callback/alipay` | 明确指定支付宝 |
| `ANY /payment/callback/unipay` | 明确指定银联 |
| `ANY /payment/callback/douyin` | 明确指定抖音 |
| `ANY /payment/callback/jsb` | 明确指定江苏银行 |

控制器：`warm\common\controller\PaymentCallbackController`，内部调用 `PaymentCallbackHandler::handle()`。

### 平台识别

- 使用 **统一入口** `/payment/callback` 时，会根据请求头、参数、Body 自动识别 **wechat / alipay / unipay / douyin / jsb**。
- 使用 `/payment/callback/wechat`、`/payment/callback/alipay`、`/payment/callback/unipay`、`/payment/callback/douyin`、`/payment/callback/jsb` 时，不再做识别，直接按对应平台处理。

识别到平台后，会获取对应 Pay 实例、验签、幂等校验，再触发业务事件。

### 业务处理：监听事件

回调验签、幂等通过后，会触发 `payment.callback` 事件。业务侧需监听该事件，根据 `order_no`、`platform`、`data` 更新订单状态等。

**方式一：在代码中监听**

```php
use Webman\Event\Event;

Event::on('payment.callback', function ($payload) {
    $platform = $payload['platform'];   // wechat | alipay | unipay | douyin | jsb
    $orderNo  = $payload['order_no'];   // 商户订单号（或平台订单号，视平台解析结果而定）
    $data     = $payload['data'];       // 回调原始数据（已验签）

    // 根据 $orderNo 查询订单，校验金额、状态后更新为已支付等
    // ...
});
```

**方式二：在事件配置中注册**

在 `config/plugin/jizhi/warm/event.php` 中增加 `payment.callback` 监听，例如：

```php
return [
    'user.login' => [[SystemUser::class, 'login']],
    'user.operateLog' => [[SystemUser::class, 'operateLog']],
    // 支付异步回调
    'payment.callback' => [
        [\your\PaymentCallbackListener::class, 'handle'],
    ],
];
```

监听器方法签名示例：`function handle(array $payload): void`，其中 `$payload` 含 `platform`、`order_no`、`data`。确保监听器在进程启动时已加载（随插件或应用启动）。

幂等由回调层基于「平台 + 订单号」缓存 24 小时，重复通知不会再次触发 `payment.callback`，且会直接返回成功，避免支付方重复推送。

---

## 注意事项

1. **yansongda/pay 版本与渠道**  
   本插件按 yansongda/pay 的配置与调用方式封装。请使用兼容的 pay 版本（如 ^4.0），并确认已支持你使用的渠道（如 unipay、douyin、jsb）。若某渠道无对应 Provider，需自行扩展或联系维护方。

2. **notify_url 与域名**  
   `notify_url` 必须使用 **公网可访问的 HTTPS 地址**，且与各支付平台后台配置的商户回调地址一致，否则无法收到异步通知。

3. **证书路径**  
   证书统一上传到 `resource/app/{平台}/`，配置中为相对路径。给 Pay 传参前会解析为绝对路径，无需在业务中再处理。

4. **实例缓存**  
   `PaymentManager` 会对 Pay 实例做进程内缓存。若在运行中修改了支付配置，可调用 `PaymentManager::clearCache()` 或 `PaymentManager::clearCache('wechat')` 等，避免沿用旧配置。

5. **日志与排查**  
   回调接收、验签、幂等、事件触发等会写入日志，便于排查。支付报错或回调异常时，可结合 `logs/` 与 yansongda/pay 文档进行定位。

6. **更多 API**  
   具体下单、退款、查询等接口以 [yansongda/pay](https://github.com/yansongda/pay) 官方文档为准，本说明仅涵盖配置、实例获取、回调接入等与 warm 插件相关的用法。

7. **常驻内存与协程**  
   `PaymentManager` 已适配 webman 常驻进程与 Workerman 协程：有协程时按 **协程** 缓存 Pay 实例（`Workerman\Coroutine\Context`），每协程内复用，避免多协程共享同一实例；无协程时降级为进程内静态缓存。配置更新后请调用 `PaymentManager::clearCache()`，以便后续 `getInstance` 使用新配置。
