<?php

namespace warm\common\service;

use InvalidArgumentException;
use warm\common\config\ConfigDefaults;
use Yansongda\Pay\Pay;

/**
 * 支付服务类
 *
 * 提供统一的支付服务接口，支持微信支付和支付宝
 */
class PaymentService
{
    /**
     * 获取支付配置
     *
     * @return array
     */
    public static function getConfig(): array
    {
        $config = systemConfig()->get(ConfigDefaults::KEY_PAYMENT_CONFIG, ConfigDefaults::getPaymentConfigDefault());
        
        $paymentConfig = [
            'wechat' => [
                'default' => [
                    'app_id' => '', // 公众号 APPID
                    'mch_id' => '', // 商户号
                    'private_key' => '', // 商户私钥
                    'cert_path' => '', // 商户证书
                    'key_path' => '', // 商户证书密钥
                    'serial_no' => '', // 商户证书序列号
                ]
            ],
            'alipay' => [
                'default' => [
                    'app_id' => '',
                    'public_key' => '',
                    'private_key' => '',
                ]
            ],
            'http' => [
                'timeout' => 5.0,
                'connect_timeout' => 5.0,
            ],
        ];
        
        // 处理微信支付配置
        if (isset($config['wechat_pay']) && $config['wechat_pay']['enable']) {
            $wechatConfig = $config['wechat_pay'];
            if ($wechatConfig['version'] === 'v3') {
                $paymentConfig['wechat']['default'] = [
                    'app_id' => '', // 公众号 APPID（需要在具体使用时设置）
                    'mch_id' => $wechatConfig['v3']['mch_id'],
                    'private_key' => $wechatConfig['v3']['private_key'],
                    'cert_path' => $wechatConfig['v3']['cert_path'],
                    'key_path' => $wechatConfig['v3']['key_path'],
                    'serial_no' => $wechatConfig['v3']['serial_no'],
                ];
            } else {
                $paymentConfig['wechat']['default'] = [
                    'app_id' => '', // 公众号 APPID（需要在具体使用时设置）
                    'mch_id' => $wechatConfig['v2']['mch_id'],
                    'private_key' => '', // V2 不需要私钥
                    'cert_path' => $wechatConfig['v2']['cert_path'],
                    'key_path' => $wechatConfig['v2']['key_path'],
                    'serial_no' => '', // V2 不需要证书序列号
                    'key' => $wechatConfig['v2']['key'], // V2 特有
                ];
            }
        }
        
        // 处理支付宝配置
        if (isset($config['alipay']) && $config['alipay']['enable']) {
            $alipayConfig = $config['alipay'];
            $paymentConfig['alipay']['default'] = [
                'app_id' => $alipayConfig['app_id'],
                'public_key' => $alipayConfig['public_key'],
                'private_key' => $alipayConfig['private_key'],
            ];
        }
        
        return $paymentConfig;
    }

    /**
     * 创建微信支付实例
     *
     * @param string $version 微信支付版本 (v2 或 v3)
     * @return \Yansongda\Pay\Provider\Wechat
     */
    public static function wechat(string $version = 'v3')
    {
        $config = self::getConfig();
        if (!isset($config['wechat']['default'])) {
            throw new InvalidArgumentException('微信支付未配置或未启用');
        }
        
        // 根据版本选择配置
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