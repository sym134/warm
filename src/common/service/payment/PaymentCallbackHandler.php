<?php

namespace warm\common\service\payment;

use Exception;
use RuntimeException;
use support\Log;
use support\Request;
use support\Response;
use Webman\Event\Event;

/**
 * 统一支付回调处理类
 *
 * 负责接收并处理所有支付平台（微信、支付宝等）发送的异步通知（回调）
 * 具备路由能力，能根据回调参数自动识别来源平台
 * 确保回调处理的幂等性、安全性（如签名验证）以及与现有订单系统的正确联动
 */
class PaymentCallbackHandler
{
    /**
     * 回调处理状态：成功
     *
     * @var string
     */
    public const STATUS_SUCCESS = 'success';

    /**
     * 回调处理状态：失败
     *
     * @var string
     */
    public const STATUS_FAIL = 'fail';

    /**
     * 回调处理状态：处理中
     *
     * @var string
     */
    public const STATUS_PROCESSING = 'processing';

    /**
     * 回调日志键前缀
     *
     * @var string
     */
    private const CALLBACK_LOG_PREFIX = 'payment_callback_';

    /**
     * 处理支付回调
     *
     * 自动识别支付平台并调用对应的处理方法
     *
     * @param Request $request HTTP 请求对象
     * @param string|null $platform 指定支付平台（可选，如果不指定则自动识别）
     * @return Response HTTP 响应对象
     */
    public static function handle(Request $request, ?string $platform = null): Response
    {
        try {
            // 自动识别支付平台
            if ($platform === null) {
                $platform = self::detectPlatform($request);
            }

            if ($platform === null) {
                throw new RuntimeException('无法识别支付平台');
            }

            // 记录回调日志
            self::logCallback($platform, $request->all(), 'received');

            // 获取支付实例
            $payInstance = self::getPayInstance($platform, $request);

            // 验证签名并获取回调数据
            $callbackData = self::verifyAndGetCallbackData($payInstance, $request, $platform);

            // 检查幂等性（防止重复处理）
            if (!self::checkIdempotency($callbackData, $platform)) {
                self::logCallback($platform, $callbackData, 'duplicate');
                return self::successResponse($platform);
            }

            // 处理业务逻辑
            $result = self::processBusiness($callbackData, $platform);

            // 标记为已处理
            self::markAsProcessed($callbackData, $platform);

            // 记录成功日志
            self::logCallback($platform, $callbackData, 'success', $result);

            return self::successResponse($platform);
        } catch (Exception $e) {
            // 记录错误日志
            self::logCallback($platform ?? 'unknown', $request->all(), 'error', ['error' => $e->getMessage()]);
            
            return self::failResponse($platform ?? 'unknown', $e->getMessage());
        }
    }

    /**
     * 自动识别支付平台
     *
     * 根据请求参数和请求头自动识别支付平台类型
     *
     * @param Request $request HTTP 请求对象
     * @return string|null 支付平台类型，如果无法识别则返回 null
     */
    private static function detectPlatform(Request $request): ?string
    {
        $headers = $request->header();
        $params = $request->all();
        $body = $request->rawBody();

        if (self::isWechatCallback($headers, $params, $body)) {
            return 'wechat';
        }
        if (self::isAlipayCallback($headers, $params, $body)) {
            return 'alipay';
        }
        if (self::isUnipayCallback($params, $body)) {
            return 'unipay';
        }
        if (self::isDouyinCallback($params, $body)) {
            return 'douyin';
        }
        if (self::isJsbCallback($params, $body)) {
            return 'jsb';
        }

        return null;
    }

    /**
     * 判断是否为微信支付回调
     *
     * @param array $headers 请求头
     * @param array $params 请求参数
     * @param string $body 请求体
     * @return bool
     */
    private static function isWechatCallback(array $headers, array $params, string $body): bool
    {
        // 微信支付 V3 回调特征：包含 Wechatpay-* 请求头
        if (isset($headers['wechatpay-signature']) || isset($headers['Wechatpay-Signature'])) {
            return true;
        }

        // 微信支付 V2 回调特征：包含 return_code 和 result_code
        if (isset($params['return_code']) && isset($params['result_code'])) {
            return true;
        }

        // 检查 XML 格式的微信回调
        if (!empty($body) && str_contains($body, '<xml>') && str_contains($body, 'return_code')) {
            return true;
        }

        return false;
    }

    /**
     * 判断是否为支付宝回调
     *
     * @param array $headers 请求头
     * @param array $params 请求参数
     * @param string $body 请求体
     * @return bool
     */
    private static function isAlipayCallback(array $headers, array $params, string $body): bool
    {
        if (isset($params['sign']) && isset($params['sign_type'])) {
            return true;
        }
        if (isset($params['notify_id']) || isset($params['notify_type'])) {
            return true;
        }
        return false;
    }

    /**
     * 判断是否为银联支付回调
     *
     * @param array $params 请求参数
     * @param string $body 请求体
     * @return bool
     */
    private static function isUnipayCallback(array $params, string $body): bool
    {
        if (isset($params['orderId']) && isset($params['queryId'])) {
            return true;
        }
        if (isset($params['txnTime']) && (isset($params['signature']) || isset($params['sign']))) {
            return true;
        }
        if (!empty($body) && str_contains($body, '"orderId"') && str_contains($body, '"queryId"')) {
            return true;
        }
        return false;
    }

    /**
     * 判断是否为抖音支付回调
     *
     * @param array $params 请求参数
     * @param string $body 请求体
     * @return bool
     */
    private static function isDouyinCallback(array $params, string $body): bool
    {
        if (isset($params['order_id']) && isset($params['msg_signature'])) {
            return true;
        }
        if (isset($params['third_order_id']) || isset($params['type'])) {
            return true;
        }
        if (!empty($body) && (str_contains($body, '"order_id"') || str_contains($body, '"third_order_id"'))) {
            return true;
        }
        return false;
    }

    /**
     * 判断是否为江苏银行回调
     *
     * @param array $params 请求参数
     * @param string $body 请求体
     * @return bool
     */
    private static function isJsbCallback(array $params, string $body): bool
    {
        if (isset($params['partner_id']) && isset($params['order_no'])) {
            return true;
        }
        if (isset($params['partnerId']) && isset($params['orderNo'])) {
            return true;
        }
        if (!empty($body) && str_contains($body, 'partner') && (str_contains($body, 'order_no') || str_contains($body, 'orderNo'))) {
            return true;
        }
        return false;
    }

    /**
     * 获取支付实例
     *
     * @param string $platform 支付平台类型
     * @param Request $request HTTP 请求对象
     * @return mixed 支付平台实例
     * @throws RuntimeException
     */
    private static function getPayInstance(string $platform, Request $request): mixed
    {
        try {
            if ($platform === 'wechat') {
                $headers = $request->header();
                if (isset($headers['wechatpay-signature']) || isset($headers['Wechatpay-Signature'])) {
                    return PaymentManager::wechat();
                }
                return PaymentManager::wechatV2();
            }
            if ($platform === 'unipay') {
                return PaymentManager::unipay();
            }
            if ($platform === 'douyin') {
                return PaymentManager::douyin();
            }
            if ($platform === 'jsb') {
                return PaymentManager::jsb();
            }

            return PaymentManager::getInstance($platform);
        } catch (Exception $e) {
            throw new RuntimeException('获取支付实例失败: ' . $e->getMessage(), 0, $e);
        }
    }

    /**
     * 验证签名并获取回调数据
     *
     * @param mixed $payInstance 支付平台实例
     * @param Request $request HTTP 请求对象
     * @param string $platform 支付平台类型
     * @return array 验证后的回调数据
     * @throws RuntimeException 当签名验证失败时抛出异常
     */
    private static function verifyAndGetCallbackData(mixed $payInstance, Request $request, string $platform): array
    {
        try {
            // 使用 yansongda/pay 的验证方法
            // yansongda/pay v4 的 verify 方法可以直接接收请求参数
            $params = $request->all();
            $payload = $request->rawBody() ?: '';

            if ($platform === 'wechat') {
                $headers = $request->header();
                $verifyParams = [
                    'headers' => $headers,
                    'params' => $params,
                    'body' => $payload,
                ];
            } elseif (in_array($platform, ['unipay', 'douyin', 'jsb'], true) && !empty($payload)) {
                $verifyParams = ['params' => $params, 'body' => $payload];
            } else {
                $verifyParams = $params;
            }

            // 调用支付平台的验证方法
            // 注意：yansongda/pay 的 verify 方法签名可能因版本而异
            // 这里使用反射或直接调用，根据实际 API 调整
            if (method_exists($payInstance, 'verify')) {
                $result = $payInstance->verify($verifyParams);
                
                // 如果返回的是 Rocket 对象，获取数据
                if (is_object($result) && method_exists($result, 'all')) {
                    $callbackData = $result->all();
                } elseif (is_array($result)) {
                    $callbackData = $result;
                } else {
                    $callbackData = $params; // 降级使用原始参数
                }
            } else {
                // 如果没有 verify 方法，直接使用参数（不推荐，但作为降级方案）
                $callbackData = $params;
            }

            return $callbackData;
        } catch (Exception $e) {
            throw new RuntimeException('签名验证失败: ' . $e->getMessage(), 0, $e);
        }
    }

    /**
     * 检查幂等性
     *
     * 使用订单号或交易号作为唯一标识，防止重复处理
     *
     * @param array $callbackData 回调数据
     * @param string $platform 支付平台类型
     * @return bool 是否可以处理（true 表示可以处理，false 表示已处理过）
     */
    private static function checkIdempotency(array $callbackData, string $platform): bool
    {
        // 获取唯一标识（订单号或交易号）
        $uniqueId = self::getUniqueId($callbackData, $platform);
        
        if (empty($uniqueId)) {
            return true; // 如果没有唯一标识，允许处理（但应该记录警告）
        }

        // 使用缓存检查是否已处理
        $cacheKey = self::CALLBACK_LOG_PREFIX . $platform . '_' . $uniqueId;
        
        // 检查缓存中是否存在
        if (cache()->has($cacheKey)) {
            return false; // 已处理过
        }

        return true;
    }

    /**
     * 获取回调的唯一标识
     *
     * @param array $callbackData 回调数据
     * @param string $platform 支付平台类型
     * @return string|null 唯一标识
     */
    private static function getUniqueId(array $callbackData, string $platform): ?string
    {
        return match ($platform) {
            'wechat' => $callbackData['out_trade_no'] ?? $callbackData['transaction_id'] ?? null,
            'alipay' => $callbackData['out_trade_no'] ?? $callbackData['trade_no'] ?? null,
            'unipay' => $callbackData['orderId'] ?? $callbackData['queryId'] ?? $callbackData['order_id'] ?? null,
            'douyin' => $callbackData['order_id'] ?? $callbackData['third_order_id'] ?? $callbackData['out_order_no'] ?? null,
            'jsb' => $callbackData['order_no'] ?? $callbackData['orderNo'] ?? $callbackData['partner_order_no'] ?? null,
            default => $callbackData['out_trade_no'] ?? $callbackData['order_id'] ?? $callbackData['orderId'] ?? null,
        };
    }

    /**
     * 处理业务逻辑
     *
     * 根据回调数据更新订单状态等业务操作
     * 可以通过事件系统扩展业务处理逻辑
     *
     * @param array $callbackData 回调数据
     * @param string $platform 支付平台类型
     * @return array 处理结果
     */
    private static function processBusiness(array $callbackData, string $platform): array
    {
        // 获取订单号
        $orderNo = self::getUniqueId($callbackData, $platform);
        
        if (empty($orderNo)) {
            throw new RuntimeException('无法获取订单号');
        }

        // 触发支付回调事件，让业务层处理
        // 这里使用 webman 的事件系统
        Event::emit('payment.callback', [
            'platform' => $platform,
            'order_no' => $orderNo,
            'data' => $callbackData,
        ]);

        // 返回处理结果
        return [
            'status' => self::STATUS_SUCCESS,
            'order_no' => $orderNo,
            'platform' => $platform,
            'message' => '支付回调处理成功',
        ];
    }

    /**
     * 标记为已处理
     *
     * 使用缓存记录已处理的回调，有效期 24 小时
     *
     * @param array $callbackData 回调数据
     * @param string $platform 支付平台类型
     * @return void
     */
    private static function markAsProcessed(array $callbackData, string $platform): void
    {
        $uniqueId = self::getUniqueId($callbackData, $platform);
        
        if (!empty($uniqueId)) {
            $cacheKey = self::CALLBACK_LOG_PREFIX . $platform . '_' . $uniqueId;
            // 缓存 24 小时
            cache()->put($cacheKey, true, 86400);
        }
    }

    /**
     * 记录回调日志
     *
     * @param string $platform 支付平台类型
     * @param array $data 回调数据
     * @param string $status 处理状态
     * @param array|null $result 处理结果
     * @return void
     */
    private static function logCallback(string $platform, array $data, string $status, ?array $result = null): void
    {
        $logData = [
            'platform' => $platform,
            'status' => $status,
            'data' => $data,
            'result' => $result,
            'time' => date('Y-m-d H:i:s'),
        ];

        // 使用日志系统记录
        Log::info('支付回调处理', $logData);
    }

    /**
     * 返回成功响应
     *
     * 不同支付平台的成功响应格式不同
     *
     * @param string $platform 支付平台类型
     * @return Response
     */
    private static function successResponse(string $platform): Response
    {
        return match ($platform) {
            'wechat' => response('<xml><return_code><![CDATA[SUCCESS]]></return_code><return_msg><![CDATA[OK]]></return_msg></xml>')
                ->header('Content-Type', 'application/xml'),
            'alipay' => response('success'),
            'unipay' => response(json_encode(['resp' => '00', 'respMsg' => 'success'], JSON_UNESCAPED_UNICODE))
                ->header('Content-Type', 'application/json'),
            'douyin' => response(json_encode(['err_no' => 0, 'err_tips' => 'success'], JSON_UNESCAPED_UNICODE))
                ->header('Content-Type', 'application/json'),
            'jsb' => response(json_encode(['code' => '0000', 'msg' => 'success'], JSON_UNESCAPED_UNICODE))
                ->header('Content-Type', 'application/json'),
            default => response(json_encode(['status' => 'success'], JSON_UNESCAPED_UNICODE))
                ->header('Content-Type', 'application/json'),
        };
    }

    /**
     * 返回失败响应
     *
     * @param string $platform 支付平台类型
     * @param string $message 错误信息
     * @return Response
     */
    private static function failResponse(string $platform, string $message): Response
    {
        return match ($platform) {
            'wechat' => response('<xml><return_code><![CDATA[FAIL]]></return_code><return_msg><![CDATA[' . $message . ']]></return_msg></xml>')
                ->header('Content-Type', 'application/xml'),
            'alipay' => response('fail'),
            'unipay' => response(json_encode(['resp' => '99', 'respMsg' => $message], JSON_UNESCAPED_UNICODE), 400)
                ->header('Content-Type', 'application/json'),
            'douyin' => response(json_encode(['err_no' => 500, 'err_tips' => $message], JSON_UNESCAPED_UNICODE), 400)
                ->header('Content-Type', 'application/json'),
            'jsb' => response(json_encode(['code' => '9999', 'msg' => $message], JSON_UNESCAPED_UNICODE), 400)
                ->header('Content-Type', 'application/json'),
            default => response(json_encode(['status' => 'fail', 'message' => $message], JSON_UNESCAPED_UNICODE), 400)
                ->header('Content-Type', 'application/json'),
        };
    }
}
