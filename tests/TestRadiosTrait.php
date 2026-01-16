<?php

require_once __DIR__ . '/../src/admin/renderer/BaseRenderer.php';
require_once __DIR__ . '/../src/admin/renderer/form/FormItemTrait.php';
require_once __DIR__ . '/../src/admin/renderer/Radios.php';

use warm\admin\renderer\expand\renderer\expand\expand\expand\expand\expand\expand\expand\expand\expand\expand\expand\expand\Radios;

// Mock admin_user() and data_get() if needed (Radios/BaseRenderer might use them)
if (!function_exists('admin_user')) {
    function admin_user() {
        return new class {
            public function can($perm) { return true; }
        };
    }
}
if (!function_exists('data_get')) {
    function data_get($target, $key, $default = null) {
        return $target[$key] ?? $default;
    }
}

echo "Testing Radios with Trait...\n";

$radios = new Radios();

// Test Trait method
$radios->name('my_radios');
if ($radios->get('name') !== 'my_radios') {
    die("FAIL: Trait method name() not working\n");
}

// Test Class method (override)
$radios->autoFill(['a' => 'b']);
if ($radios->get('autoFill') !== ['a' => 'b']) {
    die("FAIL: Class method autoFill() not working\n");
}

echo "Radios Trait Test PASS\n";
