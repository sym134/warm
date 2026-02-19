<?php

namespace warm\tests;

use PHPUnit\Framework\TestCase;
use warm\common\service\payment\PaymentCallbackHandler;

/**
 * 支付回调处理类单元测试
 *
 * 测试支付回调处理功能
 */
class PaymentCallbackHandlerTest extends TestCase
{
    /**
     * 测试回调处理状态常量
     */
    public function testCallbackStatusConstants(): void
    {
        $this->assertEquals('success', PaymentCallbackHandler::STATUS_SUCCESS);
        $this->assertEquals('fail', PaymentCallbackHandler::STATUS_FAIL);
        $this->assertEquals('processing', PaymentCallbackHandler::STATUS_PROCESSING);
    }

    /**
     * 测试获取唯一标识 - 微信支付
     */
    public function testGetUniqueIdForWechat(): void
    {
        $callbackData = [
            'out_trade_no' => 'ORDER123456',
            'transaction_id' => 'WX123456789',
        ];

        // 使用反射测试私有方法
        $reflection = new \ReflectionClass(PaymentCallbackHandler::class);
        $method = $reflection->getMethod('getUniqueId');
        $method->setAccessible(true);

        $uniqueId = $method->invokeArgs(null, [$callbackData, 'wechat']);

        $this->assertEquals('ORDER123456', $uniqueId);
    }

    /**
     * 测试获取唯一标识 - 支付宝
     */
    public function testGetUniqueIdForAlipay(): void
    {
        $callbackData = [
            'out_trade_no' => 'ORDER123456',
            'trade_no' => 'ALIPAY123456789',
        ];

        $reflection = new \ReflectionClass(PaymentCallbackHandler::class);
        $method = $reflection->getMethod('getUniqueId');
        $method->setAccessible(true);

        $uniqueId = $method->invokeArgs(null, [$callbackData, 'alipay']);

        $this->assertEquals('ORDER123456', $uniqueId);
    }

    /**
     * 测试获取唯一标识 - 没有订单号时使用交易号
     */
    public function testGetUniqueIdUsesTransactionIdWhenOrderNoMissing(): void
    {
        $callbackData = [
            'transaction_id' => 'WX123456789',
        ];

        $reflection = new \ReflectionClass(PaymentCallbackHandler::class);
        $method = $reflection->getMethod('getUniqueId');
        $method->setAccessible(true);

        $uniqueId = $method->invokeArgs(null, [$callbackData, 'wechat']);

        $this->assertEquals('WX123456789', $uniqueId);
    }

    /**
     * 测试判断是否为微信支付回调 - V3特征
     */
    public function testIsWechatCallbackV3(): void
    {
        $headers = [
            'wechatpay-signature' => 'signature_value',
        ];
        $params = [];
        $body = '';

        $reflection = new \ReflectionClass(PaymentCallbackHandler::class);
        $method = $reflection->getMethod('isWechatCallback');
        $method->setAccessible(true);

        $result = $method->invokeArgs(null, [$headers, $params, $body]);

        $this->assertTrue($result);
    }

    /**
     * 测试判断是否为微信支付回调 - V2特征
     */
    public function testIsWechatCallbackV2(): void
    {
        $headers = [];
        $params = [
            'return_code' => 'SUCCESS',
            'result_code' => 'SUCCESS',
        ];
        $body = '';

        $reflection = new \ReflectionClass(PaymentCallbackHandler::class);
        $method = $reflection->getMethod('isWechatCallback');
        $method->setAccessible(true);

        $result = $method->invokeArgs(null, [$headers, $params, $body]);

        $this->assertTrue($result);
    }

    /**
     * 测试判断是否为支付宝回调
     */
    public function testIsAlipayCallback(): void
    {
        $headers = [];
        $params = [
            'sign' => 'signature_value',
            'sign_type' => 'RSA2',
        ];
        $body = '';

        $reflection = new \ReflectionClass(PaymentCallbackHandler::class);
        $method = $reflection->getMethod('isAlipayCallback');
        $method->setAccessible(true);

        $result = $method->invokeArgs(null, [$headers, $params, $body]);

        $this->assertTrue($result);
    }

    /**
     * 测试判断不是支付回调
     */
    public function testIsNotPaymentCallback(): void
    {
        $headers = [];
        $params = [];
        $body = '';

        $reflection = new \ReflectionClass(PaymentCallbackHandler::class);
        $wechatMethod = $reflection->getMethod('isWechatCallback');
        $wechatMethod->setAccessible(true);
        $alipayMethod = $reflection->getMethod('isAlipayCallback');
        $alipayMethod->setAccessible(true);

        $isWechat = $wechatMethod->invokeArgs(null, [$headers, $params, $body]);
        $isAlipay = $alipayMethod->invokeArgs(null, [$headers, $params, $body]);

        $this->assertFalse($isWechat);
        $this->assertFalse($isAlipay);
    }
}
