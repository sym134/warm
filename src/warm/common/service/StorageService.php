<?php

namespace warm\common\service;

use warm\framework\support\facade\Storage;
use Webman\Http\UploadFile;
use finfo;
use RuntimeException;

class StorageService extends BaseService
{
    // 允许的文件类型扩展名
    protected static array $allowedFileExtensions = [];
    // 允许的图片类型扩展名
    protected static array $allowedImageExtensions = [];
    // 最大文件大小
    protected static int $maxSize = 0;

    // 扩展名到MIME类型的映射表
    protected const EXTENSION_MIME_MAP = [
        'txt' => 'text/plain',
        'doc' => 'application/msword',
        'docx' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
        'xls' => 'application/vnd.ms-excel',
        'xlsx' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        'ppt' => 'application/vnd.ms-powerpoint',
        'pptx' => 'application/vnd.openxmlformats-officedocument.presentationml.presentation',
        'rar' => 'application/x-rar-compressed',
        'zip' => 'application/zip',
        '7z' => 'application/x-7z-compressed',
        'gz' => 'application/gzip',
        'pdf' => 'application/pdf',
        'wps' => 'application/vnd.ms-works',
        'md' => 'text/markdown',
        'pem' => 'application/x-pem-file',
        'jpg' => 'image/jpeg',
        'jpeg' => 'image/jpeg',
        'png' => 'image/png',
        'gif' => 'image/gif',
        'svg' => 'image/svg+xml',
        'bmp' => 'image/bmp'
    ];

    /**
     * 初始化上传配置
     * @return void
     */
    public static function initUploadConfig(): void
    {
        $config = Storage::getUploadConfig();

        self::$allowedFileExtensions = array_map('trim',
            explode(',', $config['file_type'] ?? '')
        );
        self::$allowedImageExtensions = array_map('trim',
            explode(',', $config['image_type'] ?? '')
        );
        self::$maxSize = (int)($config['upload_size'] ?? 0);
    }

    /**
     * 验证图片文件
     * @param UploadFile $file
     * @return bool
     */
    public static function validateImage(UploadFile $file): bool
    {
        self::initUploadConfig();

        // 获取文件真实信息
        $realMime = self::getRealMimeType($file->getPathname());
        $realExt = self::getExtensionByMime($realMime);

        // 首先验证是否确实是图片类型
        if (!self::isImageMime($realMime)) {
            self::setError('文件不是有效的图片类型');
            return false;
        }

        // 图片完整性验证
        if (!self::validateImageContent($file->getPathname(), $realMime)) {
            self::setError('图片文件损坏或无效');
            return false;
        }

        // 验证扩展名是否在允许范围内
        if (!in_array(strtolower($realExt), self::$allowedImageExtensions)) {
            self::setError('不允许的图片类型: ' . $realExt);
            return false;
        }

        // 验证文件大小
        if ($file->getSize() > self::$maxSize) {
            self::setError('图片大小超过限制: ' . self::$maxSize . ' bytes');
            return false;
        }

        return true;
    }

    /**
     * 验证普通文件
     * @param UploadFile $file
     * @return bool
     */
    public static function validateFile(UploadFile $file): bool
    {
        self::initUploadConfig();

        // 获取文件真实信息
        $realMime = self::getRealMimeType($file->getPathname());
        $realExt = self::getExtensionByMime($realMime);

        // 验证文件类型 - 不能是图片类型
        if (self::isImageMime($realMime)) {
            self::setError('图片文件请使用validateImage方法验证');
            return false;
        }

        // 验证扩展名是否在允许范围内
        if (!in_array(strtolower($realExt), self::$allowedFileExtensions)) {
            self::setError('不允许的文件类型: ' . $realExt);
            return false;
        }

        // 验证文件大小
        if ($file->getSize() > self::$maxSize) {
            self::setError('文件大小超过限制: ' . self::$maxSize . ' bytes');
            return false;
        }

        return true;
    }

    /**
     * 获取文件的真实MIME类型
     * @param string $path
     * @return string
     */
    protected static function getRealMimeType(string $path): string
    {
        $finfo = new finfo(FILEINFO_MIME_TYPE);
        $mime = $finfo->file($path);
        return strtolower(trim($mime));
    }

    /**
     * 判断是否为图片MIME类型
     * @param string $mime
     * @return bool
     */
    protected static function isImageMime(string $mime): bool
    {
        return str_starts_with($mime, 'image/');
    }

    /**
     * 验证图片内容
     * @param string $path
     * @param string $mime
     * @return bool
     */
    protected static function validateImageContent(string $path, string $mime): bool
    {
        // SVG需要特殊处理
        if ($mime === 'image/svg+xml') {
            return self::validateSvgFile($path);
        }

        // 通用图片验证
        return (bool)@getimagesize($path);
    }

    /**
     * SVG文件安全验证
     * @param string $path
     * @return bool
     */
    protected static function validateSvgFile(string $path): bool
    {
        $content = @file_get_contents($path, false, null, 0, 2048);
        if (!$content) return false;

        // 基础SVG结构验证
        if (false === strpos($content, '<svg') || false === strpos($content, '</svg>')) {
            return false;
        }

        // 禁止脚本标签
        $dangerousTags = [
            '<script', '<iframe', '<foreignobject', '<handler', '<script'
        ];

        foreach ($dangerousTags as $tag) {
            if (false !== strpos($content, $tag)) {
                return false;
            }
        }

        // 禁止危险属性和事件
        $dangerousPatterns = [
            '/on\w+\s*=/i',
            '/href\s*=\s*["\']\s*javascript:/i',
            '/style\s*=\s*["\'][^"\']*expression\s*\(/i'
        ];

        foreach ($dangerousPatterns as $pattern) {
            if (preg_match($pattern, $content)) {
                return false;
            }
        }

        return true;
    }

    /**
     * 生成文件名
     * @param UploadFile $file
     * @param string $prefix
     * @return string
     */
    public static function generateFilename(UploadFile $file, string $prefix = ''): string
    {
        // 根据真实类型确定扩展名
        $realMime = self::getRealMimeType($file->getPathname());
        $extension = self::getSafeExtension($realMime);

        return uniqid($prefix) . '.' . $extension;
    }

    /**
     * 根据MIME类型获取安全扩展名
     * @param string $mime
     * @return string
     */
    protected static function getSafeExtension(string $mime): string
    {
        // 在映射表中查找匹配的扩展名
        foreach (self::EXTENSION_MIME_MAP as $ext => $mimeType) {
            if ($mimeType === $mime) {
                return $ext;
            }
        }

        // 如果是图片，尝试提取类型作为扩展名
        if (str_starts_with($mime, 'image/')) {
            $type = substr($mime, 6); // 提取image/后面的部分
            if (in_array($type, ['jpeg', 'png', 'gif', 'bmp'])) {
                return $type;
            }
            return 'img';
        }

        // 默认处理：转换MIME类型为安全扩展名
        return preg_replace('/[^a-z0-9]/', '', substr($mime, strpos($mime, '/') + 1)) ?: 'bin';
    }

    /**
     * 根据MIME类型获取扩展名
     * @param string $mime
     * @return string
     */
    protected static function getExtensionByMime(string $mime): string
    {
        foreach (self::EXTENSION_MIME_MAP as $ext => $mimeType) {
            if ($mimeType === $mime) {
                return $ext;
            }
        }

        // 默认返回前两个字符作为扩展名
        return substr(str_replace('/', '_', $mime), 0, 3);
    }

    protected function getStream(UploadFile $file)
    {
        $stream = fopen($file->getRealPath(), 'r');

        if (is_resource($stream)) {
            fclose($stream);
        }
        return $stream;
    }

    /**
     * 上传文件
     * @param UploadFile $file
     * @param string $path
     * @param string $fileName
     * @param bool $isImage 是否为图片类型
     * @return string|false
     */
    public static function upload(UploadFile $file, string $path = 'uploads', string $fileName = '', bool $isImage = false): string|false
    {
        // 选择对应的验证方法
        $validationMethod = $isImage ? 'validateImage' : 'validateFile';
        if (!self::$validationMethod($file)) {
            return false;
        }

        $filename = empty($fileName) ? self::generateFilename($file) : $fileName;
        $filepath = trim($path . '/' . $filename, '/');

        try {
            Storage::write($filepath, $file);
        } catch (\Throwable $e) {
            self::setError('文件上传失败: ' . $e->getMessage());
            return false;
        }

        return $filepath;
    }
}