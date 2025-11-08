<?php

namespace warm\support\Facades;

use Illuminate\Support\Facades\Facade;
use EasyWeChat\OfficialAccount\Application as OfficialAccount;
use EasyWeChat\MiniApp\Application as MiniApp;
use EasyWeChat\Work\Application as WorkApp;
use EasyWeChat\OpenPlatform\Application as OpenPlatform;
use EasyWeChat\OpenWork\Application as OpenWork;

/**
 * @method static OfficialAccount officialAccount()
 * @method static MiniApp miniProgram()
 * @method static WorkApp work()
 * @method static OpenPlatform openPlatform()
 * @method static OpenWork openWork()
 */
class Wechat extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'wechat.manager';
    }
}
