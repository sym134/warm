<?php

namespace warm\support;

use Illuminate\Container\Container;
use EasyWeChat\OfficialAccount\Application as OfficialAccount;
use EasyWeChat\MiniApp\Application as MiniApp;
use EasyWeChat\Work\Application as WorkApp;
use EasyWeChat\OpenPlatform\Application as OpenPlatform;
use EasyWeChat\OpenWork\Application as OpenWork;

/**
 * 统一管理 EasyWeChat 应用实例
 */
class WechatManager
{
    protected Container $container;

    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    public function officialAccount(): ?OfficialAccount
    {
        return $this->resolve('wechat.official_account');
    }

    public function miniProgram(): ?MiniApp
    {
        return $this->resolve('wechat.mini_program');
    }

    public function work(): ?WorkApp
    {
        return $this->resolve('wechat.work');
    }

    public function openPlatform(): ?OpenPlatform
    {
        return $this->resolve('wechat.open_platform');
    }

    public function openWork(): ?OpenWork
    {
        return $this->resolve('wechat.open_work');
    }

    protected function resolve(string $key)
    {
        return $this->container->bound($key) ? $this->container->make($key) : null;
    }
}
