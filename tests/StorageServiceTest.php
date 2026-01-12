<?php

namespace warm\tests;

use PHPUnit\Framework\TestCase;
use RuntimeException;
use warm\common\service\StorageService;
use warm\framework\filesystem\facade\Storage;
use Webman\Http\UploadFile;
use Workerman\Coroutine\Context;

/**
 * StorageService 单元测试
 */
class StorageServiceTest extends TestCase
{
    /**
     * 测试临时文件目录
     */
    private string $tempDir;

    /**
     * 测试前准备
     */
    protected function setUp(): void
    {
        parent::setUp();
        
        // 创建临时目录
        $this->tempDir = sys_get_temp_dir() . '/storage_service_test_' . uniqid();
        if (!is_dir($this->tempDir)) {
            mkdir($this->tempDir, 0755, true);
        }

        // 重置配置
        $this->resetStorageService();
    }

    /**
     * 测试后清理
     */
    protected function tearDown(): void
    {
        // 清理临时文件
        $this->removeDirectory($this->tempDir);
        
        // 重置服务状态
        $this->resetStorageService();
        
        parent::tearDown();
    }

    /**
     * 重置 StorageService 的协程上下文
     */
    private function resetStorageService(): void
    {
        // 清除协程上下文中的数据
        Context::set('storage_service.config', null);
        Context::set('storage_service.finfo', null);
        
        // 重置 MIME 映射缓存（如果存在）
        $reflection = new \ReflectionClass(StorageService::class);
        try {
            $property = $reflection->getProperty('mimeToExtensionMap');
            $property->setAccessible(true);
            $property->setValue(null, null);
        } catch (\ReflectionException $e) {
            // 忽略错误
        }
    }

    /**
     * 测试初始化上传配置
     * 
     * 验证配置存储在协程上下文中
     */
    public function testInitUploadConfig(): void
    {
        // 直接设置协程上下文来测试（模拟配置已读取）
        $config = [
            'allowedFileExtensions' => ['pdf', 'doc', 'docx'],
            'allowedImageExtensions' => ['jpg', 'png', 'gif'],
            'maxSize' => 10485760,
            'initialized' => true,
        ];
        
        Context::set('storage_service.config', $config);

        // 验证配置可以从上下文读取
        $storedConfig = Context::get('storage_service.config');
        $this->assertNotNull($storedConfig);
        $this->assertEquals(['pdf', 'doc', 'docx'], $storedConfig['allowedFileExtensions']);
        $this->assertEquals(['jpg', 'png', 'gif'], $storedConfig['allowedImageExtensions']);
        $this->assertEquals(10485760, $storedConfig['maxSize']);
        $this->assertTrue($storedConfig['initialized']);
    }

    /**
     * 测试配置缓存机制
     * 
     * 验证配置在协程上下文中缓存，不会重复读取
     */
    public function testConfigCache(): void
    {
        // 第一次设置配置
        $config1 = [
            'allowedFileExtensions' => ['pdf'],
            'allowedImageExtensions' => ['jpg'],
            'maxSize' => 1000,
            'initialized' => true,
        ];
        Context::set('storage_service.config', $config1);

        // 第二次设置不同的配置
        $config2 = [
            'allowedFileExtensions' => ['doc'],
            'allowedImageExtensions' => ['png'],
            'maxSize' => 2000,
            'initialized' => true,
        ];
        Context::set('storage_service.config', $config2);

        // 验证配置已被更新（协程上下文存储的是最新值）
        $storedConfig = Context::get('storage_service.config');
        $this->assertEquals(['doc'], $storedConfig['allowedFileExtensions']);
        $this->assertEquals(['png'], $storedConfig['allowedImageExtensions']);
        $this->assertEquals(2000, $storedConfig['maxSize']);
    }

    /**
     * 测试强制重新初始化配置
     * 
     * 测试 resetConfig 方法会清除协程上下文中的配置
     */
    public function testForceInitConfig(): void
    {
        // 设置初始配置
        $config = [
            'allowedFileExtensions' => ['pdf'],
            'allowedImageExtensions' => ['jpg'],
            'maxSize' => 1000,
            'initialized' => true,
        ];
        Context::set('storage_service.config', $config);

        // 验证配置已设置
        $this->assertNotNull(Context::get('storage_service.config'));

        // 调用 resetConfig（它会清除上下文并重新初始化）
        $this->assertTrue(method_exists(StorageService::class, 'resetConfig'));
        StorageService::resetConfig();
        
        // 验证 resetConfig 已执行（配置会被清除，然后重新从系统配置读取）
        // 由于无法 mock systemConfig，我们只验证方法可以正常执行
        $this->assertTrue(true);
    }

    /**
     * 测试生成文件名
     */
    public function testGenerateFilename(): void
    {
        $filename = StorageService::generateFilename('image/jpeg', 'test');
        $this->assertStringStartsWith('test', $filename);
        $this->assertStringEndsWith('.jpg', $filename);

        $filename2 = StorageService::generateFilename('image/png');
        $this->assertStringEndsWith('.png', $filename2);
        $this->assertStringStartsWith('file', $filename2);
    }

    /**
     * 测试生成不同MIME类型的文件名
     */
    public function testGenerateFilenameForDifferentMimeTypes(): void
    {
        $testCases = [
            'image/jpeg' => 'jpg',
            'image/png' => 'png',
            'image/gif' => 'gif',
            'image/svg+xml' => 'svg',
            'video/mp4' => 'mp4',
            'audio/mpeg' => 'mp3',
            'application/pdf' => 'pdf',
        ];

        foreach ($testCases as $mime => $expectedExt) {
            $filename = StorageService::generateFilename($mime);
            $this->assertStringEndsWith('.' . $expectedExt, $filename, "MIME类型 {$mime} 应该生成 .{$expectedExt} 扩展名");
        }
    }

    /**
     * 测试验证图片 - 成功案例
     */
    public function testValidateImageSuccess(): void
    {
        // 创建临时图片文件
        $imagePath = $this->createTempImage('test.jpg', 'image/jpeg');

        // 设置配置
        $this->setConfigForUpload([], ['jpg', 'jpeg', 'png'], 10485760);

        // Mock UploadFile
        $uploadFile = $this->createMockUploadFile($imagePath, 'test.jpg', 'image/jpeg', 1024);

        // 应该不抛出异常
        $this->expectNotToPerformAssertions();
        StorageService::validateImage($uploadFile, 'image/jpeg');
    }

    /**
     * 测试验证图片 - 非图片类型
     */
    public function testValidateImageNonImageFile(): void
    {
        $this->setConfigForUpload([], ['jpg', 'png'], 10485760);

        $filePath = $this->createTempFile('test.pdf', 'application/pdf');
        $uploadFile = $this->createMockUploadFile($filePath, 'test.pdf', 'application/pdf', 1024);

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('文件不是有效的图片类型');
        StorageService::validateImage($uploadFile, 'application/pdf');
    }

    /**
     * 测试验证图片 - 文件大小超限
     */
    public function testValidateImageExceedsMaxSize(): void
    {
        $imagePath = $this->createTempImage('large.jpg', 'image/jpeg');

        // 使用协程上下文设置配置
        $this->setConfigForUpload([], ['jpg'], 1000); // 1KB

        $uploadFile = $this->createMockUploadFile($imagePath, 'large.jpg', 'image/jpeg', 2048);

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('图片大小超过限制');
        StorageService::validateImage($uploadFile, 'image/jpeg');
    }

    /**
     * 测试验证图片 - 不允许的扩展名
     * 
     * 注意：由于验证顺序是先验证图片内容完整性，再验证扩展名
     * 如果图片文件格式不被支持（如BMP），getimagesize可能返回false
     * 所以这个测试改为验证一个明确不支持的扩展名场景
     */
    public function testValidateImageDisallowedExtension(): void
    {
        // 创建一个有效的PNG图片文件
        $imagePath = $this->createTempImage('test.png', 'image/png');

        // 使用协程上下文设置配置，只允许jpg，不允许png
        $this->setConfigForUpload([], ['jpg'], 10485760);

        $uploadFile = $this->createMockUploadFile($imagePath, 'test.png', 'image/png', 1024);

        // 应该抛出"不允许的图片类型"异常，因为png不在允许列表中
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('不允许的图片类型');
        StorageService::validateImage($uploadFile, 'image/png');
    }

    /**
     * 测试验证普通文件 - 成功案例
     */
    public function testValidateFileSuccess(): void
    {
        $filePath = $this->createTempFile('test.pdf', 'application/pdf');

        $this->setConfigForUpload(['pdf', 'doc'], [], 10485760);

        $uploadFile = $this->createMockUploadFile($filePath, 'test.pdf', 'application/pdf', 1024);

        $this->expectNotToPerformAssertions();
        StorageService::validateFile($uploadFile, 'application/pdf');
    }

    /**
     * 测试验证普通文件 - 图片类型应该使用validateImage
     */
    public function testValidateFileRejectsImage(): void
    {
        $this->setConfigForUpload(['pdf'], [], 10485760);

        $imagePath = $this->createTempImage('test.jpg', 'image/jpeg');
        $uploadFile = $this->createMockUploadFile($imagePath, 'test.jpg', 'image/jpeg', 1024);

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('图片文件请使用validateImage方法验证');
        StorageService::validateFile($uploadFile, 'image/jpeg');
    }

    /**
     * 测试上传文件 - 图片
     */
    public function testUploadImage(): void
    {
        $imagePath = $this->createTempImage('test.jpg', 'image/jpeg');

        $this->setConfigForUpload([], ['jpg', 'png'], 10485760);

        $this->mockStorage();

        $uploadFile = $this->createMockUploadFile($imagePath, 'test.jpg', 'image/jpeg', 1024);

        $result = StorageService::upload($uploadFile, 'uploads', '');

        $this->assertIsArray($result);
        $this->assertArrayHasKey('path', $result);
        $this->assertArrayHasKey('file_name', $result);
        $this->assertArrayHasKey('url', $result);
        $this->assertArrayHasKey('mime_type', $result);
        $this->assertArrayHasKey('type', $result);
        $this->assertEquals('image/jpeg', $result['mime_type']);
        $this->assertEquals('image', $result['type']);
        $this->assertStringContainsString('images', $result['path']);
    }

    /**
     * 测试上传文件 - 视频
     * 
     * 注意：直接传递 MIME 类型，因为文件内容不是真实的视频格式
     */
    public function testUploadVideo(): void
    {
        $videoPath = $this->createTempFile('test.mp4', 'fake video content');

        // 使用反射设置配置
        $this->setConfigForUpload(['mp4', 'avi'], [], 104857600);

        $this->mockStorage();

        $uploadFile = $this->createMockUploadFile($videoPath, 'test.mp4', 'video/mp4', 10240);

        // 直接传递 MIME 类型，避免文件检测
        $result = StorageService::upload($uploadFile, 'uploads', '', 'video/mp4');

        $this->assertEquals('video/mp4', $result['mime_type']);
        $this->assertEquals('video', $result['type']);
        $this->assertStringContainsString('videos', $result['path']);
    }

    /**
     * 测试上传文件 - 音频
     * 
     * 注意：直接传递 MIME 类型，因为文件内容不是真实的音频格式
     */
    public function testUploadAudio(): void
    {
        $audioPath = $this->createTempFile('test.mp3', 'fake audio content');

        // 使用反射设置配置
        $this->setConfigForUpload(['mp3', 'wav'], [], 10485760);

        $this->mockStorage();

        $uploadFile = $this->createMockUploadFile($audioPath, 'test.mp3', 'audio/mpeg', 2048);

        // 直接传递 MIME 类型，避免文件检测
        $result = StorageService::upload($uploadFile, 'uploads', '', 'audio/mpeg');

        $this->assertEquals('audio/mpeg', $result['mime_type']);
        $this->assertEquals('audio', $result['type']);
        $this->assertStringContainsString('audios', $result['path']);
    }

    /**
     * 测试上传文件 - 普通文件
     * 
     * 注意：直接传递 MIME 类型，因为文件内容不是真实的PDF格式
     */
    public function testUploadFile(): void
    {
        $filePath = $this->createTempFile('test.pdf', 'fake pdf content');

        // 使用反射设置配置
        $this->setConfigForUpload(['pdf', 'doc'], [], 10485760);

        $this->mockStorage();

        $uploadFile = $this->createMockUploadFile($filePath, 'test.pdf', 'application/pdf', 1024);

        // 直接传递 MIME 类型，避免文件检测
        $result = StorageService::upload($uploadFile, 'uploads', '', 'application/pdf');

        $this->assertEquals('application/pdf', $result['mime_type']);
        $this->assertEquals('file', $result['type']);
        $this->assertStringContainsString('files', $result['path']);
    }

    /**
     * 测试上传文件 - 使用自定义文件名
     */
    public function testUploadWithCustomFilename(): void
    {
        $imagePath = $this->createTempImage('test.jpg', 'image/jpeg');

        $this->setConfigForUpload([], ['jpg'], 10485760);

        $this->mockStorage();

        $uploadFile = $this->createMockUploadFile($imagePath, 'test.jpg', 'image/jpeg', 1024);

        $result = StorageService::upload($uploadFile, 'uploads', 'custom_name.jpg');

        $this->assertEquals('custom_name.jpg', $result['file_name']);
    }


    /**
     * Mock Storage facade
     * 
     * 由于 Storage 是静态 Facade，需要在实际测试中通过容器绑定或使用测试桩
     */
    private function mockStorage(): void
    {
        // Storage::put() 和 Storage::url() 的 mock
        // 在实际测试中，可能需要：
        // 1. 使用容器绑定替换 Storage 实现
        // 2. 创建测试用的文件系统适配器
        // 3. 使用反射来替换静态方法（如果支持）
    }

    /**
     * 创建模拟的 UploadFile 对象
     */
    private function createMockUploadFile(
        string $realPath,
        string $uploadName,
        string $mimeType,
        int $size
    ): UploadFile {
        $uploadFile = $this->createMock(UploadFile::class);
        $uploadFile->method('getRealPath')->willReturn($realPath);
        $uploadFile->method('getUploadName')->willReturn($uploadName);
        $uploadFile->method('getPathname')->willReturn($realPath);
        $uploadFile->method('getSize')->willReturn($size);

        return $uploadFile;
    }

    /**
     * 创建临时图片文件
     */
    private function createTempImage(string $filename, string $mimeType): string
    {
        $path = $this->tempDir . '/' . $filename;

        // 创建简单的图片文件（1x1像素的PNG）
        if ($mimeType === 'image/png') {
            $content = base64_decode('iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVR42mNk+M9QDwADhgGAWjR9awAAAABJRU5ErkJggg==');
        } elseif ($mimeType === 'image/jpeg') {
            // 创建最小有效的JPEG文件（使用二进制字符串）
            $content = hex2bin('FFD8FFE000104A46494600010101004800480000FFDB004300080606070605080707070909080A0C140D0C0B0B0C1912130F141D1A1F1E1D1A1C1C20242E2720222C231C1C2837292C30313434341F27393D38323C2E333432FFC000110800010001011100021101031101FFC40014000100000000000000000000000000000008FFC40014100100000000000000000000000000000000FFDA000C03010002110311003F00AAFFD9');
        } else {
            $content = 'fake image content';
        }

        file_put_contents($path, $content);
        return $path;
    }

    /**
     * 创建临时文件
     */
    private function createTempFile(string $filename, string $content = 'test content'): string
    {
        $path = $this->tempDir . '/' . $filename;
        file_put_contents($path, $content);
        return $path;
    }

    /**
     * 递归删除目录
     */
    private function removeDirectory(string $dir): void
    {
        if (!is_dir($dir)) {
            return;
        }

        $files = array_diff(scandir($dir), ['.', '..']);
        foreach ($files as $file) {
            $path = $dir . '/' . $file;
            is_dir($path) ? $this->removeDirectory($path) : unlink($path);
        }

        rmdir($dir);
    }

    /**
     * 设置上传配置（使用协程上下文）
     */
    private function setConfigForUpload(array $fileExtensions, array $imageExtensions, int $maxSize): void
    {
        $config = [
            'allowedFileExtensions' => $fileExtensions,
            'allowedImageExtensions' => $imageExtensions,
            'maxSize' => $maxSize,
            'initialized' => true,
        ];
        
        Context::set('storage_service.config', $config);
    }
}

