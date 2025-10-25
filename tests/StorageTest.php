<?php

namespace tests;

use PHPUnit\Framework\TestCase;
use warm\framework\support\facade\Storage;

/**
 * 存储服务测试类
 * 
 * 用于测试存储服务的各种功能，包括文件操作、目录操作等
 * 确保存储服务的稳定性和正确性
 */
class StorageTest extends TestCase
{
    /**
     * 测试前准备方法
     * 
     * 在每个测试方法执行前运行，确保测试环境准备就绪
     * 
     * @return void
     */
    protected function setUp(): void
    {
        // 确保测试目录存在
        if (!is_dir(__DIR__.'/storage')) {
            mkdir(__DIR__.'/storage');
        }
    }

    /**
     * 测试后清理方法
     * 
     * 在每个测试方法执行后运行，清理测试产生的文件
     * 
     * @return void
     */
    protected function tearDown(): void
    {
        // 清理测试文件
        if (file_exists(__DIR__.'/storage/test.txt')) {
            unlink(__DIR__.'/storage/test.txt');
        }
    }

    /**
     * 测试基本文件操作
     * 
     * 测试文件的写入、读取、存在性检查和删除功能
     * 
     * @return void
     */
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

    /**
     * 测试目录操作
     * 
     * 测试目录的创建、存在性检查和删除功能
     * 
     * @return void
     */
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

    /**
     * 测试获取默认驱动
     * 
     * 验证能够正确获取默认的存储驱动名称
     * 
     * @return void
     */
    public function testGetDefaultDriver()
    {
        $defaultDriver = Storage::getDefaultDriver();
        $this->assertIsString($defaultDriver);
        $this->assertEquals('local', $defaultDriver);
    }

    /**
     * 测试自定义磁盘
     * 
     * 验证能够正确获取指定名称的存储磁盘实例
     * 
     * @return void
     */
    public function testCustomDisk()
    {
        // 测试自定义磁盘
        $customDisk = Storage::disk('local');
        $this->assertInstanceOf(\warm\framework\filesystem\FilesystemAdapter::class, $customDisk);
    }
}
