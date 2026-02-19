<?php

namespace warm\tests\admin\controller\system;

use PHPUnit\Framework\TestCase;
use warm\admin\controller\system\CacheController;
use warm\admin\controller\system\SystemStorageController;
use warm\admin\controller\system\PaymentConfigController;
use Mockery;

// Mock support classes if they don't exist
if (!class_exists('support\Translation')) {
    class_alias('warm\tests\mocks\Translation', 'support\Translation');
}
if (!class_exists('support\Request')) {
    class_alias('warm\tests\mocks\Request', 'support\Request');
}
if (!class_exists('support\Response')) {
    class_alias('warm\tests\mocks\Response', 'support\Response');
}
if (!class_exists('support\Container')) {
    class_alias('warm\tests\mocks\Container', 'support\Container');
}

// Mock global functions if they don't exist
if (!function_exists('translator')) {
    function translator($key, $replace = [], $locale = null) {
        return \support\Translation::trans($key, $replace, null, $locale);
    }
}
if (!function_exists('admin_url')) {
    function admin_url($path = '', $needPrefix = false) {
        return '/admin/' . ltrim($path, '/');
    }
}
if (!function_exists('amis')) {
    function amis($type = null) {
        return new \warm\admin\renderer\expand\Component(); // Simplified mock
    }
}
if (!function_exists('request')) {
    function request() {
        return new \support\Request();
    }
}
if (!function_exists('config')) {
    function config($key = null, $default = null) {
        return $default;
    }
}
if (!function_exists('base_path')) {
    function base_path($path = '') {
        return __DIR__ . '/../../../../../../' . $path;
    }
}

namespace warm\tests\mocks;

class Translation {
    public static function trans($key, $replace = [], $domain = null, $locale = null) {
        // Return a predictable string to verify translation was called
        return "TRANS_($key)";
    }
    public static function instance($domain) {
        return new self();
    }
}

class Request {
    public function path() { return '/test/path'; }
    public function input($key = null) { return null; }
    public function all() { return []; }
    public function file($key) { return null; }
    public function route() { return null; }
}

class Response {
    public function success($data) { return $data; } // Return data directly for inspection
    public function fail($msg) { return $msg; }
    public function successMessage($msg) { return $msg; }
}

class Container {
    public static function get($name) { return null; }
    public static function make($name) { return new $name(); }
}

namespace warm\tests\admin\controller\system;

use PHPUnit\Framework\TestCase;
use Mockery;

class ControllerTranslationTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    public function testTranslationFilesExist()
    {
        $zhPath = __DIR__ . '/../../../../src/resource/translations/zh_CN/system.php';
        $enPath = __DIR__ . '/../../../../src/resource/translations/en/system.php';

        $this->assertFileExists($zhPath);
        $this->assertFileExists($enPath);

        $zh = require $zhPath;
        $en = require $enPath;

        $this->assertIsArray($zh);
        $this->assertIsArray($en);
        
        // Ensure structure similarity
        $this->assertEquals(array_keys($zh), array_keys($en));
        $this->assertEquals(array_keys($zh['storage']), array_keys($en['storage']));
        $this->assertEquals(array_keys($zh['payment']), array_keys($en['payment']));
        $this->assertEquals(array_keys($zh['cache']), array_keys($en['cache']));
    }

    /**
     * Note: To fully test controller output, we would need to mock the entire Amis component chain.
     * Since Amis components use fluent interface and return themselves, mocking them is complex.
     * Here we verify the Translation files contain the keys used in the controllers.
     */
    public function testKeysUsedInControllersExistInLanguageFiles()
    {
        $zh = require __DIR__ . '/../../../../src/resource/translations/zh_CN/system.php';
        
        // Flatten the array for easier searching
        $flattened = $this->flatten($zh, 'system');
        
        // Sample check for keys we know we used
        $this->assertArrayHasKey('system.cache.title', $flattened);
        $this->assertArrayHasKey('system.storage.warning', $flattened);
        $this->assertArrayHasKey('system.payment.platform', $flattened);
        $this->assertArrayHasKey('system.payment.alipay.title', $flattened);
    }

    private function flatten(array $array, string $prefix = ''): array
    {
        $result = [];
        foreach ($array as $key => $value) {
            $newKey = $prefix ? $prefix . '.' . $key : $key;
            if (is_array($value)) {
                $result = array_merge($result, $this->flatten($value, $newKey));
            } else {
                $result[$newKey] = $value;
            }
        }
        return $result;
    }
}
