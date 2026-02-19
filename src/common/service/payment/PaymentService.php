<?php

namespace warm\common\service\payment;

use InvalidArgumentException;
use warm\common\config\ConfigDefaults;
use Yansongda\Pay\Pay;

/**
 * 支付服务类
 *
 * 提供统一支付配置（yansongda/pay 结构），支持 alipay、wechat、unipay、douyin、jsb
 * 证书路径若为 resource/app/ 相对路径，会自动解析为 base_path 绝对路径
 */
class PaymentService
{
    /**
     * 获取支付配置（供 Pay SDK 使用）
     *
     * 仅包含已启用平台的 default 配置，及 logger、http
     * 证书相对路径（resource/app/...）会解析为绝对路径
     *
     * @return array
     */
    public static function getConfig(): array
    {
        $raw = systemConfig()->get(ConfigDefaults::KEY_PAYMENT_CONFIG, ConfigDefaults::getPaymentConfigDefault());
        $config = PaymentConfigEncryptionService::decryptConfig(
            $raw,
            ConfigDefaults::getPaymentConfigSensitiveFields()
        );

        $out = [
            'http' => $config['http'] ?? ConfigDefaults::getPaymentConfigDefault()['http'],
        ];

        $platforms = ['alipay', 'wechat', 'unipay', 'douyin', 'jsb'];
        foreach ($platforms as $id) {
            $plat = $config[$id] ?? [];
            if (empty($plat['enable'])) {
                continue;
            }
            $def = $plat['default'] ?? [];
            if (empty($def)) {
                continue;
            }
            $out[$id] = ['default' => self::resolveCertPaths($def)];
        }

        if (!empty($config['logger'])) {
            $out['logger'] = $config['logger'];
        }

        return $out;
    }

    /**
     * 将 default 中的证书相对路径（resource/app/...）解析为 base_path 绝对路径
     *
     * @param array<string, mixed> $def
     * @return array<string, mixed>
     */
    private static function resolveCertPaths(array $def): array
    {
        $resolved = [];
        foreach ($def as $k => $v) {
            if ($k === 'wechat_public_cert_path' && is_array($v)) {
                $resolved[$k] = [];
                foreach ($v as $key => $path) {
                    $resolved[$k][$key] = is_string($path) ? self::absPath($path) : $path;
                }
            } elseif (is_string($v) && $v !== '') {
                $resolved[$k] = self::absPath($v);
            } else {
                $resolved[$k] = $v;
            }
        }
        return $resolved;
    }

    private static function absPath(string $path): string
    {
        $path = trim($path);
        if ($path === '') {
            return $path;
        }
        if (str_starts_with($path, 'resource/app/')) {
            return base_path($path);
        }
        return $path;
    }

    /**
     * 创建微信支付实例（V3）
     *
     * @param string $version 微信支付版本 (v2|v3)，默认 v3
     * @return \Yansongda\Pay\Provider\Wechat
     */
    public static function wechat(string $version = 'v3')
    {
        $config = self::getConfig();
        if (!isset($config['wechat']['default'])) {
            throw new InvalidArgumentException('微信支付未配置或未启用');
        }

        if ($version === 'v2') {
            return Pay::wechat($config)->v2();
        }

        return Pay::wechat($config);
    }

    /**
     * 创建支付宝支付实例
     *
     * @return \Yansongda\Pay\Provider\Alipay
     */
    public static function alipay()
    {
        $config = self::getConfig();
        if (!isset($config['alipay']['default'])) {
            throw new InvalidArgumentException('支付宝未配置或未启用');
        }

        return Pay::alipay($config);
    }
}
