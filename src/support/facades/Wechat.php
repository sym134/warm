<?php

namespace warm\support\facades;

use EasyWeChat\MiniApp\Application as MiniApp;
use EasyWeChat\OfficialAccount\Application as OfficialAccount;
use EasyWeChat\OpenPlatform\Application as OpenPlatform;
use EasyWeChat\OpenWork\Application as OpenWork;
use EasyWeChat\Work\Application as WorkApp;
use Illuminate\Support\Facades\Facade;

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
