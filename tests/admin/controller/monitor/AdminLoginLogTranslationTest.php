<?php

namespace warm\tests\admin\controller\monitor;

use PHPUnit\Framework\TestCase;

class AdminLoginLogTranslationTest extends TestCase
{
    public function testTranslationFilesExist()
    {
        $zhPath = __DIR__ . '/../../../../src/resource/translations/zh_CN/monitor.php';
        $enPath = __DIR__ . '/../../../../src/resource/translations/en/monitor.php';

        $this->assertFileExists($zhPath);
        $this->assertFileExists($enPath);

        $zh = require $zhPath;
        $en = require $enPath;

        $this->assertIsArray($zh);
        $this->assertIsArray($en);

        $this->assertArrayHasKey('login_log', $zh);
        $this->assertArrayHasKey('login_log', $en);

        $keys = [
            'id', 'username', 'ip', 'ip_location', 'os', 'browser',
            'status', 'message', 'login_time', 'remark',
            'status_success', 'status_failed', 'status_disabled'
        ];

        foreach ($keys as $key) {
            $this->assertArrayHasKey($key, $zh['login_log'], "Missing key: $key in zh_CN");
            $this->assertArrayHasKey($key, $en['login_log'], "Missing key: $key in en");
        }
    }
}
