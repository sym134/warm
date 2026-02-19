<?php

namespace warm\admin\service\system;

use warm\admin\service\AdminService;
use warm\common\config\ConfigDefaults;
use warm\common\service\payment\PaymentConfigEncryptionService;

/**
 * 支付配置服务类
 *
 * 提供支付配置管理功能，支持敏感配置的加密存储
 * 独立于通知配置服务
 */
class PaymentConfigService extends AdminService
{
    /**
     * 资源路由主键字段名
     *
     * 该服务不绑定数据库模型，列表数据为自定义数组，
     * 但 CRUD 行操作（编辑/删除等）依赖主键字段生成路径（如 /xxx/${id}/edit）。
     *
     * @return string
     */
    public function primaryKey(): string
    {
        return 'id';
    }

    /**
     * 获取支付配置
     *
     * 自动解密敏感字段
     *
     * @return array
     */
    public function get(): array
    {
        $config = systemConfig()->get(ConfigDefaults::KEY_PAYMENT_CONFIG, ConfigDefaults::getPaymentConfigDefault());

        return PaymentConfigEncryptionService::decryptConfig(
            $config,
            ConfigDefaults::getPaymentConfigSensitiveFields()
        );
    }

    /**
     * 保存支付配置（兼容方法）
     *
     * 自动加密敏感字段后存储
     *
     * @param array $data 配置数据
     * @return bool
     */
    public function save(array $data): bool
    {
        // 如果数据中包含平台ID，使用update方法
        if (isset($data['id'])) {
            return $this->update($data['id'], $data);
        }
        // 否则更新整个配置
        return $this->update('payment', $data);
    }

    /** 支付平台列表（id => 名称） */
    private const PLATFORMS = [
        'alipay' => '支付宝',
        'wechat' => '微信支付',
        'unipay' => '银联支付',
        'douyin' => '抖音支付',
        'jsb' => '江苏银行',
    ];

    /**
     * 获取支付平台列表
     *
     * 返回所有支付平台的列表数据
     *
     * @return array 支付平台列表
     */
    public function list(): array
    {
        $config = $this->get();
        $platforms = [];

        foreach (self::PLATFORMS as $id => $name) {
            $plat = $config[$id] ?? [];
            $enable = (int) ($plat['enable'] ?? 0);
            $def = $plat['default'] ?? [];
            $platforms[] = [
                'id' => $id,
                'name' => $name,
                'enable' => $enable,
                'status' => $enable ? '已启用' : '未启用',
                'mch_id' => $def['mch_id'] ?? '',
                'app_id' => $def['app_id'] ?? $def['mini_app_id'] ?? '',
            ];
        }

        return [
            'items' => $platforms,
            'total' => count($platforms),
        ];
    }

    /**
     * 根据平台ID获取编辑数据
     *
     * @param mixed $id 平台ID（alipay|wechat|unipay|douyin|jsb）
     * @return array 平台配置数据
     */
    public function getEditData(mixed $id): array
    {
        $config = $this->get();
        $data = ['id' => $id];

        if (!isset(self::PLATFORMS[$id])) {
            return $data;
        }

        $plat = $config[$id] ?? [];
        $def = array_merge(
            ConfigDefaults::getPaymentConfigDefault()[$id]['default'] ?? [],
            $plat['default'] ?? []
        );
        if ($id === 'wechat' && isset($def['wechat_public_cert_path']) && is_array($def['wechat_public_cert_path'])) {
            $def['wechat_public_cert_path'] = json_encode($def['wechat_public_cert_path'], JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
        }
        $data['payment'] = [
            $id => [
                'enable' => (int) ($plat['enable'] ?? 0),
                'default' => $def,
            ],
        ];
        return $data;
    }

    /**
     * 更新指定支付平台配置
     *
     * @param mixed $id 平台ID（alipay|wechat|unipay|douyin|jsb）
     * @param array $data 配置数据
     * @return bool 是否更新成功
     */
    public function update(mixed $id, array $data): bool
    {
        if (!isset(self::PLATFORMS[$id])) {
            return false;
        }

        $config = systemConfig()->get(ConfigDefaults::KEY_PAYMENT_CONFIG, ConfigDefaults::getPaymentConfigDefault());
        $decryptedConfig = PaymentConfigEncryptionService::decryptConfig(
            $config,
            ConfigDefaults::getPaymentConfigSensitiveFields()
        );

        $platformData = $data['payment'][$id] ?? $data[$id] ?? $data;
        $defaultUpdate = is_array($platformData['default'] ?? null) ? $platformData['default'] : [];
        $enable = array_key_exists('enable', $platformData) ? (int) $platformData['enable'] : null;

        if ($id === 'wechat' && isset($defaultUpdate['wechat_public_cert_path']) && is_string($defaultUpdate['wechat_public_cert_path'])) {
            $dec = json_decode(trim($defaultUpdate['wechat_public_cert_path']), true);
            $defaultUpdate['wechat_public_cert_path'] = is_array($dec) ? $dec : [];
        }

        $plat = $decryptedConfig[$id] ?? [];
        $def = array_merge($plat['default'] ?? [], $defaultUpdate);
        $decryptedConfig[$id] = array_merge($plat, ['default' => $def]);
        if ($enable !== null) {
            $decryptedConfig[$id]['enable'] = $enable;
        }

        $encryptedData = PaymentConfigEncryptionService::encryptConfig(
            $decryptedConfig,
            ConfigDefaults::getPaymentConfigSensitiveFields()
        );
        return systemConfig()->set(ConfigDefaults::KEY_PAYMENT_CONFIG, $encryptedData);
    }

    /**
     * 获取需要加密的字段列表
     *
     * @return array<int, string>
     */
    public static function getSensitiveFields(): array
    {
        return ConfigDefaults::getPaymentConfigSensitiveFields();
    }
}