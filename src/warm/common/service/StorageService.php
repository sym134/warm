<?php

namespace warm\common\service;

use warm\framework\support\facade\Storage;
use Webman\Http\UploadFile;

class StorageService extends BaseService
{
    protected static array $allowedTypes = [];
    protected static array $allowedImageTypes = [];
    protected static int $maxSize = 0;

    public static function init(): void
    {
        $config = Storage::getUploadConfig();

        self::$allowedTypes = explode(',', $config['file_type'] ?? '');
        self::$allowedImageTypes = explode(',', $config['image_type'] ?? '');
        self::$maxSize = (int)($config['upload_size'] ?? 0);
    }

    public static function validateFile(UploadFile $file): bool
    {
        self::init();
        // 验证文件类型
        if (!empty(self::$allowedTypes) && !in_array($file->getUploadMimeType(), self::$allowedTypes)) {
            self::setError('不允许的文件类型: ' . $file->getUploadMimeType());
            return false;
        }

        // 验证图片类型
        if (str_starts_with($file->getUploadMimeType(), 'image/') &&
            !in_array($file->getUploadMimeType(), self::$allowedImageTypes)) {
            self::setError('不允许的图片类型: ' . $file->getUploadMimeType());
            return false;
        }

        // 验证文件大小
        if ($file->getSize() > self::$maxSize) {
            self::setError('文件大小超过限制: ' . self::$maxSize . ' bytes');
            return false;
        }

        return true;
    }

    public static function generateFilename(UploadFile $file, string $prefix = ''): string
    {
        $extension = $file->getUploadExtension();
        return uniqid($prefix) . '.' . $extension;
    }

    public static function upload(UploadFile $file, string $path = 'uploads', string $prefix = ''): string|false
    {
        if (!self::validateFile($file)) {
            return false;
        }

        $filename = self::generateFilename($file, $prefix);
        $filepath = trim($path . '/' . $filename, '/');

        if (!Storage::disk()->put($filepath, $file)) {
            self::setError('文件上传失败');
            return false;
        }

        return $filepath;
    }
}