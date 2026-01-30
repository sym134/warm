<?php

namespace warm\common\service;

use InvalidArgumentException;
use RuntimeException;
use Workerman\Coroutine\Context;
use Yansongda\Pay\Pay;
use Yansongda\Pay\Provider\Alipay;
use Yansongda\Pay\Provider\Wechat;
use warm\common\config\ConfigDefaults;

/**
 * 统一支付平台管理类
 *
 * 提供统一的支付平台管理接口，集成 wechat、alipay、unipay、douyin、jsb（yansongda/pay 结构）
 *
 * 常驻内存与协程：
 * - 支持 webman 常驻进程；实例按协程缓存，避免多协程共享同一 Pay 实例导致的状态串扰。
 * - 有 Workerman 协程时使用 Context 按协程缓存，每协程内复用；无协程时降级为进程内静态缓存。
 * - 配置变更后请调用 clearCache()，当前协程/进程后续 getInstance 将重新创建实例。
 */
class PaymentManager
{
    /** 协程上下文缓存键 */
    private const CONTEXT_KEY_INSTANCES = 'payment_manager.instances';

    public const PLATFORMS = [
        'wechat' => '微信支付',
        'alipay' => '支付宝',
        'unipay' => '银联支付',
        'douyin' => '抖音支付',
        'jsb' => '江苏银行',
    ];

    public const WECHAT_VERSIONS = [
        'v2' => 'V2版本',
        'v3' => 'V3版本',
    ];

    /** 无协程时的进程级缓存（降级） */
    private static array $instances = [];

    /**
     * 获取支付平台实例
     *
     * @param string $platform wechat|alipay|unipay|douyin|jsb
     * @param string|null $version 微信支付 v2|v3，仅 wechat 需要
     * @return object 支付 Provider 实例（Wechat|Alipay|Unipay|Douyin|Jsb 等）
     */
    public static function getInstance(string $platform, ?string $version = null): object
    {
        if (!isset(self::PLATFORMS[$platform])) {
            throw new InvalidArgumentException(
                sprintf('不支持的支付平台: %s，支持的平台: %s', $platform, implode(', ', array_keys(self::PLATFORMS)))
            );
        }

        $cacheKey = self::cacheKey($platform, $version);

        if (self::useContext()) {
            $bag = Context::get(self::CONTEXT_KEY_INSTANCES);
            if (is_array($bag) && isset($bag[$cacheKey])) {
                return $bag[$cacheKey];
            }
            $bag = is_array($bag) ? $bag : [];
        } else {
            if (isset(self::$instances[$cacheKey])) {
                return self::$instances[$cacheKey];
            }
        }

        $instance = match ($platform) {
            'wechat' => self::createWechatInstance($version ?? 'v3'),
            'alipay' => self::createAlipayInstance(),
            'unipay' => self::createUnipayInstance(),
            'douyin' => self::createDouyinInstance(),
            'jsb' => self::createJsbInstance(),
            default => throw new InvalidArgumentException('不支持的支付平台: ' . $platform),
        };

        if (self::useContext()) {
            $bag[$cacheKey] = $instance;
            Context::set(self::CONTEXT_KEY_INSTANCES, $bag);
        } else {
            self::$instances[$cacheKey] = $instance;
        }

        return $instance;
    }

    private static function cacheKey(string $platform, ?string $version): string
    {
        return $platform . ($version ? '_' . $version : '');
    }

    private static function useContext(): bool
    {
        return class_exists(Context::class, false);
    }

    private static function createWechatInstance(string $version = 'v3'): Wechat
    {
        $config = PaymentService::getConfig();
        if (!isset($config['wechat']['default'])) {
            throw new RuntimeException('微信支付未配置或未启用');
        }
        if (!isset(self::WECHAT_VERSIONS[$version])) {
            throw new InvalidArgumentException(
                sprintf('不支持的微信支付版本: %s', $version)
            );
        }
        if ($version === 'v2') {
            return Pay::wechat($config)->v2();
        }
        return Pay::wechat($config);
    }

    private static function createAlipayInstance(): Alipay
    {
        $config = PaymentService::getConfig();
        if (!isset($config['alipay']['default'])) {
            throw new RuntimeException('支付宝未配置或未启用');
        }
        return Pay::alipay($config);
    }

    private static function createUnipayInstance(): object
    {
        $config = PaymentService::getConfig();
        if (!isset($config['unipay']['default'])) {
            throw new RuntimeException('银联支付未配置或未启用');
        }
        return Pay::unipay($config);
    }

    private static function createDouyinInstance(): object
    {
        $config = PaymentService::getConfig();
        if (!isset($config['douyin']['default'])) {
            throw new RuntimeException('抖音支付未配置或未启用');
        }
        return Pay::douyin($config);
    }

    private static function createJsbInstance(): object
    {
        $config = PaymentService::getConfig();
        if (!isset($config['jsb']['default'])) {
            throw new RuntimeException('江苏银行未配置或未启用');
        }
        return Pay::jsb($config);
    }

    /**
     * 检查支付平台是否已启用
     *
     * @param string $platform wechat|alipay|unipay|douyin|jsb
     * @return bool
     */
    public static function isEnabled(string $platform): bool
    {
        try {
            $config = systemConfig()->get(
                ConfigDefaults::KEY_PAYMENT_CONFIG,
                ConfigDefaults::getPaymentConfigDefault()
            );
            $plat = $config[$platform] ?? [];
            return isset($plat['enable']) && (int) $plat['enable'] === 1;
        } catch (\Throwable $e) {
            return false;
        }
    }

    /**
     * 获取所有已启用的支付平台
     *
     * @return array<string>
     */
    public static function getEnabledPlatforms(): array
    {
        $enabled = [];
        foreach (array_keys(self::PLATFORMS) as $platform) {
            if (self::isEnabled($platform)) {
                $enabled[] = $platform;
            }
        }
        return $enabled;
    }

    /**
     * 清除实例缓存
     *
     * 协程下清除当前协程的 Context 缓存；同时清除进程级降级缓存。
     * 配置更新后调用以便后续 getInstance 使用新配置。
     *
     * @param string|null $platform 指定平台，null 则清除全部
     */
    public static function clearCache(?string $platform = null): void
    {
        if (self::useContext()) {
            $bag = Context::get(self::CONTEXT_KEY_INSTANCES);
            if (is_array($bag)) {
                if ($platform === null) {
                    $bag = [];
                } else {
                    foreach (array_keys($bag) as $key) {
                        if (str_starts_with($key, $platform)) {
                            unset($bag[$key]);
                        }
                    }
                }
                Context::set(self::CONTEXT_KEY_INSTANCES, $bag);
            }
        }

        if ($platform === null) {
            self::$instances = [];
        } else {
            foreach (array_keys(self::$instances) as $key) {
                if (str_starts_with($key, $platform)) {
                    unset(self::$instances[$key]);
                }
            }
        }
    }

    public static function wechat(): Wechat
    {
        return self::getInstance('wechat', 'v3');
    }

    public static function wechatV2(): Wechat
    {
        return self::getInstance('wechat', 'v2');
    }

    public static function alipay(): Alipay
    {
        return self::getInstance('alipay');
    }

    public static function unipay(): object
    {
        return self::getInstance('unipay');
    }

    public static function douyin(): object
    {
        return self::getInstance('douyin');
    }

    public static function jsb(): object
    {
        return self::getInstance('jsb');
    }
}
