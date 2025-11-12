<?php

namespace warm\admin\service\system;

use warm\common\config\ConfigDefaults;

/**
 * 支付配置服务类
 *
 * 提供支付配置管理功能，独立于通知配置服务
 */
class PaymentConfigService
{
    /**
     * 获取支付配置
     *
     * @return array
     */
    public function get(): array
    {
        return systemConfig()->get(ConfigDefaults::KEY_PAYMENT_CONFIG, ConfigDefaults::getPaymentConfigDefault());
    }

    /**
     * 保存支付配置
     *
     * @param array $data 配置数据
     * @return bool
     */
    public function save(array $data): bool
    {
        return systemConfig()->set(ConfigDefaults::KEY_PAYMENT_CONFIG, $data);
    }
}