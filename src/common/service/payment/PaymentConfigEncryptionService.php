<?php

namespace warm\common\service;

use Exception;
use RuntimeException;

/**
 * 支付配置加密服务类
 *
 * 提供支付配置的加密和解密功能，确保敏感信息（如API密钥、私钥等）的安全存储
 * 使用 OpenSSL AES-256-CBC 加密算法
 */
class PaymentConfigEncryptionService
{
    /**
     * 加密方法
     *
     * @var string
     */
    private const CIPHER = 'AES-256-CBC';

    /**
     * 获取加密密钥
     *
     * 从环境变量或配置中获取加密密钥，如果不存在则生成并保存
     *
     * @return string 加密密钥
     * @throws RuntimeException 当无法生成或获取密钥时抛出异常
     */
    private static function getEncryptionKey(): string
    {
        // 优先从环境变量获取
        $key = env('PAYMENT_ENCRYPTION_KEY');
        
        if (empty($key)) {
            // 从系统配置获取
            $key = systemConfig()->get('payment_encryption_key');
            
            if (empty($key)) {
                // 生成新的密钥
                $key = self::generateKey();
                // 保存到系统配置
                systemConfig()->set('payment_encryption_key', $key);
            }
        }
        
        // 确保密钥长度为 32 字节（AES-256 需要）
        if (strlen($key) !== 32) {
            $key = hash('sha256', $key, true);
        }
        
        return $key;
    }

    /**
     * 生成加密密钥
     *
     * 生成一个安全的随机加密密钥
     *
     * @return string 生成的密钥（base64 编码）
     */
    private static function generateKey(): string
    {
        return base64_encode(random_bytes(32));
    }

    /**
     * 加密数据
     *
     * 使用 AES-256-CBC 算法加密敏感配置数据
     *
     * @param string $value 需要加密的原始数据
     * @return string 加密后的数据（格式：iv:encrypted_data）
     * @throws RuntimeException 当加密失败时抛出异常
     */
    public static function encrypt(string $value): string
    {
        if (empty($value)) {
            return '';
        }

        try {
            $key = self::getEncryptionKey();
            $iv = random_bytes(openssl_cipher_iv_length(self::CIPHER));
            
            $encrypted = openssl_encrypt(
                $value,
                self::CIPHER,
                $key,
                OPENSSL_RAW_DATA,
                $iv
            );

            if ($encrypted === false) {
                throw new RuntimeException('加密失败');
            }

            // 返回 base64 编码的 iv 和加密数据
            return base64_encode($iv . $encrypted);
        } catch (Exception $e) {
            throw new RuntimeException('加密配置失败: ' . $e->getMessage(), 0, $e);
        }
    }

    /**
     * 解密数据
     *
     * 解密之前加密的配置数据
     *
     * @param string $encryptedValue 加密后的数据
     * @return string 解密后的原始数据
     * @throws RuntimeException 当解密失败时抛出异常
     */
    public static function decrypt(string $encryptedValue): string
    {
        if (empty($encryptedValue)) {
            return '';
        }

        try {
            $key = self::getEncryptionKey();
            $data = base64_decode($encryptedValue, true);
            
            if ($data === false) {
                throw new RuntimeException('无效的加密数据格式');
            }

            $ivLength = openssl_cipher_iv_length(self::CIPHER);
            $iv = substr($data, 0, $ivLength);
            $encrypted = substr($data, $ivLength);

            $decrypted = openssl_decrypt(
                $encrypted,
                self::CIPHER,
                $key,
                OPENSSL_RAW_DATA,
                $iv
            );

            if ($decrypted === false) {
                throw new RuntimeException('解密失败');
            }

            return $decrypted;
        } catch (Exception $e) {
            throw new RuntimeException('解密配置失败: ' . $e->getMessage(), 0, $e);
        }
    }

    /**
     * 批量加密配置数组
     *
     * 对配置数组中指定的敏感字段进行加密
     *
     * @param array $config 配置数组
     * @param array $sensitiveFields 需要加密的字段路径列表（支持点号分隔的嵌套路径）
     * @return array 加密后的配置数组
     */
    public static function encryptConfig(array $config, array $sensitiveFields): array
    {
        foreach ($sensitiveFields as $field) {
            $value = self::getNestedValue($config, $field);
            if ($value !== null && $value !== '') {
                self::setNestedValue($config, $field, self::encrypt($value));
            }
        }
        
        return $config;
    }

    /**
     * 批量解密配置数组
     *
     * 对配置数组中指定的敏感字段进行解密
     *
     * @param array $config 配置数组
     * @param array $sensitiveFields 需要解密的字段路径列表（支持点号分隔的嵌套路径）
     * @return array 解密后的配置数组
     */
    public static function decryptConfig(array $config, array $sensitiveFields): array
    {
        foreach ($sensitiveFields as $field) {
            $value = self::getNestedValue($config, $field);
            if ($value !== null && $value !== '') {
                try {
                    self::setNestedValue($config, $field, self::decrypt($value));
                } catch (Exception $e) {
                    // 如果解密失败，可能是未加密的数据，保持原值
                    continue;
                }
            }
        }
        
        return $config;
    }

    /**
     * 获取嵌套数组的值
     *
     * 支持点号分隔的路径，如 'wechat_pay.v3.private_key'
     *
     * @param array $array 数组
     * @param string $path 路径
     * @return mixed|null 值或 null
     */
    private static function getNestedValue(array $array, string $path): mixed
    {
        $keys = explode('.', $path);
        $value = $array;
        
        foreach ($keys as $key) {
            if (!isset($value[$key])) {
                return null;
            }
            $value = $value[$key];
        }
        
        return $value;
    }

    /**
     * 设置嵌套数组的值
     *
     * 支持点号分隔的路径，如 'wechat_pay.v3.private_key'
     *
     * @param array $array 数组引用
     * @param string $path 路径
     * @param mixed $value 值
     * @return void
     */
    private static function setNestedValue(array &$array, string $path, mixed $value): void
    {
        $keys = explode('.', $path);
        $lastKey = array_pop($keys);
        
        $current = &$array;
        foreach ($keys as $key) {
            if (!isset($current[$key]) || !is_array($current[$key])) {
                $current[$key] = [];
            }
            $current = &$current[$key];
        }
        
        $current[$lastKey] = $value;
    }
}
