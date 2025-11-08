<?php

namespace warm\bootstrap;

use EasyWeChat\OfficialAccount\Application as OfficialAccount;
use EasyWeChat\MiniApp\Application as MiniApp;
use EasyWeChat\Work\Application as WorkApp;
use EasyWeChat\OpenPlatform\Application as OpenPlatform;
use EasyWeChat\OpenWork\Application as OpenWork;
use warm\common\service\SystemConfigService;
use warm\common\config\ConfigDefaults;
use warm\support\WechatManager;
use Webman\Bootstrap;
use Illuminate\Container\Container;
use Illuminate\Support\Facades\Facade;

/**
 * Webman 启动时注册 EasyWeChat
 */
class WechatBootstrap implements Bootstrap
{
    protected static ?Container $container = null;

    public static function start($worker): void
    {
        /** @var Container|null $container */
        $container = LaravelBootstrap::app();
        if (!$container) {
            return;
        }

        self::$container = $container;

        // 注册 WechatManager
        $container->singleton('wechat.manager', fn($app) => new WechatManager($app));

        // 加载微信配置
        self::loadWechatApps($container);

        // 注册 Facade
        Facade::setFacadeApplication($container);
        if (!class_exists('Wechat')) {
            class_alias(\warm\support\Facades\Wechat::class, 'Wechat');
        }

        echo "[WechatBootstrap] WeChat services loaded\n";
    }

    /**
     * 从数据库加载 WeChat 配置
     */
    protected static function loadWechatApps(Container $container): void
    {
        $wechatConfig = [
            'official_account' => SystemConfigService::get(ConfigDefaults::KEY_WECHAT_OFFICIAL_ACCOUNT_CONFIG)
                ?? ConfigDefaults::getWechatOfficialAccountConfigDefault(),
            'mini_program' => SystemConfigService::get(ConfigDefaults::KEY_WECHAT_MINI_PROGRAM_CONFIG)
                ?? ConfigDefaults::getWechatMiniProgramConfigDefault(),
            'work' => SystemConfigService::get(ConfigDefaults::KEY_WECHAT_WORK_CONFIG)
                ?? ConfigDefaults::getWechatWorkConfigDefault(),
            'open_platform' => SystemConfigService::get(ConfigDefaults::KEY_WECHAT_OPEN_PLATFORM_CONFIG)
                ?? ConfigDefaults::getWechatOpenPlatformConfigDefault(),
            'open_work' => SystemConfigService::get(ConfigDefaults::KEY_WECHAT_OPEN_WORK_CONFIG)
                ?? ConfigDefaults::getWechatOpenWorkConfigDefault(),
        ];

        // 清理旧绑定
        foreach ([
                     'wechat.official_account',
                     'wechat.mini_program',
                     'wechat.work',
                     'wechat.open_platform',
                     'wechat.open_work',
                 ] as $key) {
            if ($container->bound($key)) {
                $container->forgetInstance($key);
            }
        }

        // 公众号
        if (!empty($wechatConfig['official_account'])) {
            $cfg = $wechatConfig['official_account'];
            $container->instance('wechat.official_account', new OfficialAccount([
                'app_id' => $cfg['app_id'] ?? '',
                'secret' => $cfg['secret'] ?? '',
                'token' => $cfg['token'] ?? '',
                'aes_key' => $cfg['aes_key'] ?? '',
                'response_type' => 'array',
                'http' => ['timeout' => 5.0, 'retry' => true],
            ]));
        }

        // 小程序
        if (!empty($wechatConfig['mini_program'])) {
            $cfg = $wechatConfig['mini_program'];
            $container->instance('wechat.mini_program', new MiniApp([
                'app_id' => $cfg['app_id'] ?? '',
                'secret' => $cfg['secret'] ?? '',
                'response_type' => 'array',
            ]));
        }

        // 企业微信
        if (!empty($wechatConfig['work'])) {
            $cfg = $wechatConfig['work'];
            $container->instance('wechat.work', new WorkApp([
                'corp_id' => $cfg['corp_id'] ?? '',
                'secret' => $cfg['secret'] ?? '',
                'response_type' => 'array',
            ]));
        }

        // 开放平台（绑定第三方平台）
        if (!empty($wechatConfig['open_platform'])) {
            $cfg = $wechatConfig['open_platform'];
            $container->instance('wechat.open_platform', new OpenPlatform([
                'app_id' => $cfg['app_id'] ?? '',
                'secret' => $cfg['secret'] ?? '',
                'token' => $cfg['token'] ?? '',
                'aes_key' => $cfg['aes_key'] ?? '',
                'response_type' => 'array',
            ]));
        }

        // 企业微信开放平台
        if (!empty($wechatConfig['open_work'])) {
            $cfg = $wechatConfig['open_work'];
            $container->instance('wechat.open_work', new OpenWork([
                'corp_id' => $cfg['corp_id'] ?? '',
                'provider_secret' => $cfg['provider_secret'] ?? '',
                'response_type' => 'array',
            ]));
        }
    }

    /**
     * 热重载微信配置（无需重启worker）
     */
    public static function reload(): void
    {
        if (!self::$container) {
            return;
        }
        self::loadWechatApps(self::$container);
        echo "[WechatBootstrap] ✅ Reloaded WeChat configuration\n";
    }
}
