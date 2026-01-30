<?php

namespace warm\common\controller;

use support\Request;
use support\Response;
use warm\common\service\PaymentCallbackHandler;

/**
 * 支付回调控制器
 *
 * 处理所有支付平台的异步通知回调
 */
class PaymentCallbackController
{
    /**
     * 处理支付回调
     *
     * 自动识别支付平台并处理回调
     *
     * @param Request $request HTTP 请求对象
     * @return Response HTTP 响应对象
     */
    public function handle(Request $request): Response
    {
        return PaymentCallbackHandler::handle($request);
    }

    /**
     * 处理微信支付回调
     *
     * @param Request $request HTTP 请求对象
     * @return Response HTTP 响应对象
     */
    public function wechat(Request $request): Response
    {
        return PaymentCallbackHandler::handle($request, 'wechat');
    }

    /**
     * 处理支付宝回调
     *
     * @param Request $request HTTP 请求对象
     * @return Response HTTP 响应对象
     */
    public function alipay(Request $request): Response
    {
        return PaymentCallbackHandler::handle($request, 'alipay');
    }

    /**
     * 处理银联支付回调
     *
     * @param Request $request HTTP 请求对象
     * @return Response HTTP 响应对象
     */
    public function unipay(Request $request): Response
    {
        return PaymentCallbackHandler::handle($request, 'unipay');
    }

    /**
     * 处理抖音支付回调
     *
     * @param Request $request HTTP 请求对象
     * @return Response HTTP 响应对象
     */
    public function douyin(Request $request): Response
    {
        return PaymentCallbackHandler::handle($request, 'douyin');
    }

    /**
     * 处理江苏银行回调
     *
     * @param Request $request HTTP 请求对象
     * @return Response HTTP 响应对象
     */
    public function jsb(Request $request): Response
    {
        return PaymentCallbackHandler::handle($request, 'jsb');
    }
}
