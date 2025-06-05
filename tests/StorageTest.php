<?php

namespace tests;

use PHPUnit\Framework\TestCase;
use warm\framework\support\facade\Storage;

class StorageTest extends TestCase
{
    protected function setUp(): void
    {
        // 确保测试目录存在
        if (!is_dir(__DIR__.'/storage')) {
            mkdir(__DIR__.'/storage');
        }
    }

    protected function tearDown(): void
    {
        // 清理测试文件
        if (file_exists(__DIR__.'/storage/test.txt')) {
            unlink(__DIR__.'/storage/test.txt');
        }
    }

    public function testBasicFileOperations()
    {
        $testContent = 'test content';
        $testPath = 'test.txt';

        // 测试写入文件
        $writeResult = Storage::disk()->put($testPath, $testContent);
        $this->assertTrue($writeResult);

        // 测试读取文件
        $readContent = Storage::disk()->get($testPath);
        $this->assertEquals($testContent, $readContent);

        // 测试文件是否存在
        $exists = Storage::disk()->exists($testPath);
        $this->assertTrue($exists);

        // 测试删除文件
        $deleteResult = Storage::disk()->delete($testPath);
        $this->assertTrue($deleteResult);
    }

    public function testDirectoryOperations()
    {
        $dirPath = 'test_dir';

        // 测试创建目录
        $createResult = Storage::disk()->createDirectory($dirPath);
        $this->assertTrue($createResult);

        // 测试目录是否存在
        $exists = Storage::disk()->directoryExists($dirPath);
        $this->assertTrue($exists);

        // 测试删除目录
        $deleteResult = Storage::disk()->deleteDirectory($dirPath);
        $this->assertTrue($deleteResult);
    }

    public function testGetDefaultDriver()
    {
        $defaultDriver = Storage::getDefaultDriver();
        $this->assertIsString($defaultDriver);
        $this->assertEquals('local', $defaultDriver);
    }

    public function testCustomDisk()
    {
        // 测试自定义磁盘
        $customDisk = Storage::disk('local');
        $this->assertInstanceOf(\warm\framework\filesystem\FilesystemAdapter::class, $customDisk);
    }
}
