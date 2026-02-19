<?php

namespace warm\tests;

use PHPUnit\Framework\TestCase;
use RuntimeException;
use warm\common\service\payment\PaymentConfigEncryptionService;

/**
 * 支付配置加密服务单元测试
 *
 * 测试配置加密和解密功能
 */
class PaymentConfigEncryptionServiceTest extends TestCase
{
    /**
     * 测试前准备
     */
    protected function setUp(): void
    {
        parent::setUp();
        
        // 设置测试用的加密密钥（从环境变量或生成）
        if (!getenv('PAYMENT_ENCRYPTION_KEY')) {
            putenv('PAYMENT_ENCRYPTION_KEY=' . base64_encode(random_bytes(32)));
        }
    }

    /**
     * 测试加密和解密基本功能
     */
    public function testEncryptAndDecrypt(): void
    {
        $originalValue = 'test_secret_key_12345';
        
        // 加密
        $encrypted = PaymentConfigEncryptionService::encrypt($originalValue);
        
        // 验证加密后的值不等于原始值
        $this->assertNotEquals($originalValue, $encrypted);
        $this->assertNotEmpty($encrypted);
        
        // 解密
        $decrypted = PaymentConfigEncryptionService::decrypt($encrypted);
        
        // 验证解密后的值等于原始值
        $this->assertEquals($originalValue, $decrypted);
    }

    /**
     * 测试空字符串加密解密
     */
    public function testEncryptDecryptEmptyString(): void
    {
        $encrypted = PaymentConfigEncryptionService::encrypt('');
        $this->assertEquals('', $encrypted);
        
        $decrypted = PaymentConfigEncryptionService::decrypt('');
        $this->assertEquals('', $decrypted);
    }

    /**
     * 测试加密不同长度的字符串
     */
    public function testEncryptDecryptDifferentLengths(): void
    {
        $testCases = [
            'a',
            'short',
            'medium_length_string_123',
            str_repeat('x', 100),
            str_repeat('y', 1000),
        ];

        foreach ($testCases as $original) {
            $encrypted = PaymentConfigEncryptionService::encrypt($original);
            $decrypted = PaymentConfigEncryptionService::decrypt($encrypted);
            
            $this->assertEquals($original, $decrypted, "Failed for string of length " . strlen($original));
        }
    }

    /**
     * 测试解密无效数据抛出异常
     */
    public function testDecryptInvalidData(): void
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('解密');
        
        PaymentConfigEncryptionService::decrypt('invalid_encrypted_data');
    }

    /**
     * 测试批量加密配置数组
     */
    public function testEncryptConfig(): void
    {
        $config = [
            'wechat_pay' => [
                'v3' => [
                    'private_key' => 'secret_key_123',
                    'serial_no' => 'serial_456',
                    'mch_id' => 'mch_id_789', // 不需要加密
                ],
            ],
            'alipay' => [
                'private_key' => 'alipay_secret',
                'public_key' => 'alipay_public',
            ],
        ];

        $sensitiveFields = [
            'wechat_pay.v3.private_key',
            'wechat_pay.v3.serial_no',
            'alipay.private_key',
            'alipay.public_key',
        ];

        $encrypted = PaymentConfigEncryptionService::encryptConfig($config, $sensitiveFields);

        // 验证敏感字段已加密
        $this->assertNotEquals('secret_key_123', $encrypted['wechat_pay']['v3']['private_key']);
        $this->assertNotEquals('serial_456', $encrypted['wechat_pay']['v3']['serial_no']);
        $this->assertNotEquals('alipay_secret', $encrypted['alipay']['private_key']);
        $this->assertNotEquals('alipay_public', $encrypted['alipay']['public_key']);

        // 验证非敏感字段未加密
        $this->assertEquals('mch_id_789', $encrypted['wechat_pay']['v3']['mch_id']);
    }

    /**
     * 测试批量解密配置数组
     */
    public function testDecryptConfig(): void
    {
        $originalConfig = [
            'wechat_pay' => [
                'v3' => [
                    'private_key' => 'secret_key_123',
                    'serial_no' => 'serial_456',
                ],
            ],
            'alipay' => [
                'private_key' => 'alipay_secret',
            ],
        ];

        $sensitiveFields = [
            'wechat_pay.v3.private_key',
            'wechat_pay.v3.serial_no',
            'alipay.private_key',
        ];

        // 先加密
        $encrypted = PaymentConfigEncryptionService::encryptConfig($originalConfig, $sensitiveFields);

        // 再解密
        $decrypted = PaymentConfigEncryptionService::decryptConfig($encrypted, $sensitiveFields);

        // 验证解密后的值等于原始值
        $this->assertEquals('secret_key_123', $decrypted['wechat_pay']['v3']['private_key']);
        $this->assertEquals('serial_456', $decrypted['wechat_pay']['v3']['serial_no']);
        $this->assertEquals('alipay_secret', $decrypted['alipay']['private_key']);
    }

    /**
     * 测试加密配置后解密还原
     */
    public function testEncryptDecryptConfigRoundTrip(): void
    {
        $originalConfig = [
            'wechat_pay' => [
                'enable' => 1,
                'version' => 'v3',
                'v3' => [
                    'mch_id' => '1234567890',
                    'private_key' => 'my_secret_private_key',
                    'serial_no' => 'SERIAL123456',
                    'cert_path' => '/path/to/cert.pem',
                ],
            ],
            'alipay' => [
                'enable' => 1,
                'app_id' => '2021001234567890',
                'private_key' => 'alipay_private_key',
                'public_key' => 'alipay_public_key',
            ],
        ];

        $sensitiveFields = [
            'wechat_pay.v3.private_key',
            'wechat_pay.v3.serial_no',
            'alipay.private_key',
            'alipay.public_key',
        ];

        // 加密
        $encrypted = PaymentConfigEncryptionService::encryptConfig($originalConfig, $sensitiveFields);

        // 解密
        $decrypted = PaymentConfigEncryptionService::decryptConfig($encrypted, $sensitiveFields);

        // 验证所有字段都正确还原
        $this->assertEquals($originalConfig, $decrypted);
    }

    /**
     * 测试加密后的值每次不同（由于使用了随机IV）
     */
    public function testEncryptProducesDifferentValues(): void
    {
        $value = 'same_value_to_encrypt';
        
        $encrypted1 = PaymentConfigEncryptionService::encrypt($value);
        $encrypted2 = PaymentConfigEncryptionService::encrypt($value);
        
        // 由于使用了随机IV，每次加密的结果应该不同
        $this->assertNotEquals($encrypted1, $encrypted2);
        
        // 但解密后应该得到相同的值
        $this->assertEquals($value, PaymentConfigEncryptionService::decrypt($encrypted1));
        $this->assertEquals($value, PaymentConfigEncryptionService::decrypt($encrypted2));
    }
}
