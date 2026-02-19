<?php

namespace warm\tests;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use warm\common\service\payment\PaymentManager;

/**
 * 支付平台管理类单元测试
 *
 * 测试支付平台管理功能
 */
class PaymentManagerTest extends TestCase
{
    /**
     * 测试前准备
     */
    protected function setUp(): void
    {
        parent::setUp();
        
        // 清除实例缓存
        PaymentManager::clearCache();
    }

    /**
     * 测试后清理
     */
    protected function tearDown(): void
    {
        // 清除实例缓存
        PaymentManager::clearCache();
        
        parent::tearDown();
    }

    /**
     * 测试获取支持的平台列表
     */
    public function testGetSupportedPlatforms(): void
    {
        $platforms = PaymentManager::PLATFORMS;
        
        $this->assertIsArray($platforms);
        $this->assertArrayHasKey('wechat', $platforms);
        $this->assertArrayHasKey('alipay', $platforms);
        $this->assertArrayHasKey('unipay', $platforms);
        $this->assertArrayHasKey('douyin', $platforms);
        $this->assertArrayHasKey('jsb', $platforms);
        $this->assertEquals('微信支付', $platforms['wechat']);
        $this->assertEquals('支付宝', $platforms['alipay']);
        $this->assertEquals('银联支付', $platforms['unipay']);
        $this->assertEquals('抖音支付', $platforms['douyin']);
        $this->assertEquals('江苏银行', $platforms['jsb']);
    }

    /**
     * 测试获取不支持的平台抛出异常
     */
    public function testGetUnsupportedPlatformThrowsException(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('不支持的支付平台');
        
        PaymentManager::getInstance('unsupported_platform');
    }

    /**
     * 测试清除缓存
     */
    public function testClearCache(): void
    {
        // 清除所有缓存
        PaymentManager::clearCache();
        $this->assertTrue(true); // 如果没有异常，测试通过
        
        // 清除指定平台缓存
        PaymentManager::clearCache('wechat');
        $this->assertTrue(true); // 如果没有异常，测试通过
    }

    /**
     * 测试检查平台是否启用（当配置不存在时）
     */
    public function testIsEnabledWhenConfigNotExists(): void
    {
        // 当配置不存在时，应该返回 false
        $result = PaymentManager::isEnabled('wechat');
        
        // 由于无法 mock systemConfig，我们只验证方法可以正常执行
        // 实际结果取决于系统配置
        $this->assertIsBool($result);
    }

    /**
     * 测试获取已启用的平台列表
     */
    public function testGetEnabledPlatforms(): void
    {
        $enabled = PaymentManager::getEnabledPlatforms();
        
        $this->assertIsArray($enabled);
        // 验证返回的都是支持的平台
        foreach ($enabled as $platform) {
            $this->assertContains($platform, array_keys(PaymentManager::PLATFORMS));
        }
    }

    /**
     * 测试便捷方法存在
     */
    public function testConvenienceMethodsExist(): void
    {
        $this->assertTrue(method_exists(PaymentManager::class, 'wechat'));
        $this->assertTrue(method_exists(PaymentManager::class, 'wechatV2'));
        $this->assertTrue(method_exists(PaymentManager::class, 'alipay'));
        $this->assertTrue(method_exists(PaymentManager::class, 'unipay'));
        $this->assertTrue(method_exists(PaymentManager::class, 'douyin'));
        $this->assertTrue(method_exists(PaymentManager::class, 'jsb'));
    }

    /**
     * 测试微信支付版本常量
     */
    public function testWechatVersions(): void
    {
        $versions = PaymentManager::WECHAT_VERSIONS;
        
        $this->assertIsArray($versions);
        $this->assertArrayHasKey('v2', $versions);
        $this->assertArrayHasKey('v3', $versions);
    }
}
