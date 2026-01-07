<?php

namespace warm\common\service;

use finfo;
use warm\framework\filesystem\facade\Storage;
use RuntimeException;
use Webman\Http\UploadFile;
use Workerman\Coroutine\Context;

/**
 * 存储服务类
 * 
 * 提供文件存储相关功能，包括文件上传、验证、路径管理等
 * 支持图片、视频、音频等多种文件类型的处理
 */
class StorageService extends BaseService
{
    /**
     * SVG文件验证时的最大读取字节数
     */
    private const SVG_VALIDATION_MAX_BYTES = 2048;

    /**
     * MIME类型前缀长度（用于提取类型部分）
     */
    private const MIME_PREFIX_LENGTH = 6;

    /**
     * 协程上下文中的配置键名
     */
    private const CONTEXT_KEY_CONFIG = 'storage_service.config';
    private const CONTEXT_KEY_FINFO = 'storage_service.finfo';

    /**
     * MIME类型到扩展名的反向映射缓存（进程级别，所有协程共享）
     * 
     * @var array|null
     */
    private static ?array $mimeToExtensionMap = null;

    /**
     * 扩展名到MIME类型的映射表（包含图片、视频、音频）
     */
    protected const EXTENSION_MIME_MAP = [
        // 文本/文档类
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

        // 图片类
        'jpg' => 'image/jpeg',
        'jpeg' => 'image/jpeg',
        'png' => 'image/png',
        'gif' => 'image/gif',
        'svg' => 'image/svg+xml',
        'bmp' => 'image/bmp',
        'webp' => 'image/webp',
        'tiff' => 'image/tiff',

        // 视频类
        'mp4' => 'video/mp4',
        'avi' => 'video/x-msvideo',
        'mov' => 'video/quicktime',
        'wmv' => 'video/x-ms-wmv',
        'flv' => 'video/x-flv',
        'mkv' => 'video/x-matroska',
        'webm' => 'video/webm',

        // 音频类
        'mp3' => 'audio/mpeg',
        'wav' => 'audio/wav',
        'ogg' => 'audio/ogg',
        'flac' => 'audio/flac',
        'aac' => 'audio/aac',
        'm4a' => 'audio/mp4',
        'wma' => 'audio/x-ms-wma'
    ];

    /**
     * 初始化上传配置
     * 
     * 从存储配置中读取允许的文件类型和大小限制
     * 配置存储在协程上下文中，每个协程独立
     * 
     * @param bool $force 强制重新初始化配置
     * @return void
     */
    public static function initUploadConfig(bool $force = false): void
    {
        // 从协程上下文获取配置
        $config = Context::get(self::CONTEXT_KEY_CONFIG);
        
        if ($config !== null && !$force) {
            return;
        }

        $systemConfig = systemConfig()->get('filesystems');

        $fileType = $systemConfig['file_type'] ?? '';
        $imageType = $systemConfig['image_type'] ?? '';
        $uploadSize = $systemConfig['upload_size'] ?? 0;

        $config = [
            'allowedFileExtensions' => $fileType 
                ? array_map('trim', explode(',', $fileType))
                : [],
            'allowedImageExtensions' => $imageType 
                ? array_map('trim', explode(',', $imageType))
                : [],
            'maxSize' => (int)$uploadSize,
            'initialized' => true,
        ];

        // 存储到协程上下文
        Context::set(self::CONTEXT_KEY_CONFIG, $config);
    }

    /**
     * 获取协程上下文中的配置
     * 
     * @return array|null
     */
    private static function getConfig(): ?array
    {
        return Context::get(self::CONTEXT_KEY_CONFIG);
    }

    /**
     * 获取允许的文件扩展名
     * 
     * @return array
     */
    private static function getAllowedFileExtensions(): array
    {
        $config = self::getConfig();
        return $config['allowedFileExtensions'] ?? [];
    }

    /**
     * 获取允许的图片扩展名
     * 
     * @return array
     */
    private static function getAllowedImageExtensions(): array
    {
        $config = self::getConfig();
        return $config['allowedImageExtensions'] ?? [];
    }

    /**
     * 获取最大文件大小
     * 
     * @return int
     */
    private static function getMaxSize(): int
    {
        $config = self::getConfig();
        return $config['maxSize'] ?? 0;
    }

    /**
     * 重置配置缓存
     * 
     * 用于在配置更新后强制重新加载当前协程的配置
     * 
     * @return void
     */
    public static function resetConfig(): void
    {
        // 清除当前协程的配置
        Context::set(self::CONTEXT_KEY_CONFIG, null);
        self::initUploadConfig(true);
    }

    /**
     * 验证图片文件
     * 
     * 验证上传的图片文件是否符合要求，包括类型、完整性、大小等
     * 
     * @param UploadFile $file 上传的文件对象
     * @param string $realMime 文件的真实MIME类型
     * @throws RuntimeException 当验证失败时抛出异常
     */
    public static function validateImage(UploadFile $file, string $realMime): void
    {
        self::initUploadConfig();

        // 获取文件真实信息
        $realExt = self::getExtensionByMime($realMime);

        // 首先验证是否确实是图片类型
        if (!self::isImageMime($realMime)) {
            throw new RuntimeException('文件不是有效的图片类型');
        }

        // 图片完整性验证
        if (!self::validateImageContent($file->getRealPath(), $realMime)) {
            throw new RuntimeException('图片文件损坏或无效');
        }

        // 验证扩展名是否在允许范围内
        $allowedImageExtensions = self::getAllowedImageExtensions();
        $realExtLower = strtolower($realExt);
        if (!empty($allowedImageExtensions) && !in_array($realExtLower, $allowedImageExtensions, true)) {
            throw new RuntimeException("不允许的图片类型: {$realExt}");
        }

        // 验证文件大小
        $maxSize = self::getMaxSize();
        if ($maxSize > 0 && $file->getSize() > $maxSize) {
            $maxSizeMB = round($maxSize / 1048576, 2);
            $fileSizeMB = round($file->getSize() / 1048576, 2);
            throw new RuntimeException("图片大小超过限制: {$fileSizeMB}MB (最大: {$maxSizeMB}MB)");
        }
    }

    /**
     * 验证普通文件（包含视频和音频）
     * 
     * 验证上传的普通文件是否符合要求，包括类型和大小等
     * 
     * @param UploadFile $file 上传的文件对象
     * @param string $realMime 文件的真实MIME类型
     * @return void
     * @throws RuntimeException 当验证失败时抛出异常
     */
    public static function validateFile(UploadFile $file, string $realMime): void
    {
        self::initUploadConfig();

        // 获取文件真实信息
        $realExt = self::getExtensionByMime($realMime);

        // 验证文件类型 - 不能是图片类型
        if (self::isImageMime($realMime)) {
            throw new RuntimeException('图片文件请使用validateImage方法验证');
        }

        // 验证扩展名是否在允许范围内
        $allowedFileExtensions = self::getAllowedFileExtensions();
        $realExtLower = strtolower($realExt);
        if (!empty($allowedFileExtensions) && !in_array($realExtLower, $allowedFileExtensions, true)) {
            throw new RuntimeException("不允许的文件类型: {$realExt}");
        }

        // 验证文件大小
        $maxSize = self::getMaxSize();
        if ($maxSize > 0 && $file->getSize() > $maxSize) {
            $maxSizeMB = round($maxSize / 1048576, 2);
            $fileSizeMB = round($file->getSize() / 1048576, 2);
            throw new RuntimeException("文件大小超过限制: {$fileSizeMB}MB (最大: {$maxSizeMB}MB)");
        }
    }

    /**
     * 判断是否为视频MIME类型
     * 
     * @param string $mime MIME类型
     * @return bool 是否为视频MIME类型
     */
    protected static function isVideoMime(string $mime): bool
    {
        return str_starts_with($mime, 'video/');
    }

    /**
     * 判断是否为音频MIME类型
     * 
     * @param string $mime MIME类型
     * @return bool 是否为音频MIME类型
     */
    protected static function isAudioMime(string $mime): bool
    {
        return str_starts_with($mime, 'audio/');
    }

    /**
     * 获取文件的真实MIME类型
     * 
     * 使用finfo扩展检测文件的真实MIME类型
     * finfo实例会被复用以提高性能
     * 
     * @param string $path 文件路径
     * @return string 真实的MIME类型
     * @throws RuntimeException 当无法检测MIME类型时抛出异常
     */
    protected static function getRealMimeType(string $path): string
    {
        if (!file_exists($path)) {
            throw new RuntimeException("文件不存在: {$path}");
        }

        // 从协程上下文获取 finfo 实例
        $finfoInstance = Context::get(self::CONTEXT_KEY_FINFO);
        
        if ($finfoInstance === null) {
            if (!extension_loaded('fileinfo')) {
                throw new RuntimeException('fileinfo扩展未安装');
            }
            $finfoInstance = new finfo(FILEINFO_MIME_TYPE);
            // 存储到协程上下文
            Context::set(self::CONTEXT_KEY_FINFO, $finfoInstance);
        }

        $mime = $finfoInstance->file($path);
        
        if ($mime === false) {
            throw new RuntimeException("无法检测文件的MIME类型: {$path}");
        }

        return strtolower(trim($mime));
    }

    /**
     * 判断是否为图片MIME类型
     * 
     * @param string $mime MIME类型
     * @return bool 是否为图片MIME类型
     */
    protected static function isImageMime(string $mime): bool
    {
        return str_starts_with($mime, 'image/');
    }

    /**
     * 验证图片内容
     * 
     * 验证图片文件的内容是否完整有效
     * 
     * @param string $path 文件路径
     * @param string $mime MIME类型
     * @return bool 图片是否有效
     */
    protected static function validateImageContent(string $path, string $mime): bool
    {
        if (!file_exists($path) || !is_readable($path)) {
            return false;
        }

        // SVG需要特殊处理
        if ($mime === 'image/svg+xml') {
            return self::validateSvgFile($path);
        }

        // 通用图片验证
        $imageInfo = getimagesize($path);
        return $imageInfo !== false;
    }

    /**
     * SVG文件安全验证
     * 
     * 验证SVG文件是否包含危险内容
     * 
     * @param string $path 文件路径
     * @return bool SVG文件是否安全
     */
    protected static function validateSvgFile(string $path): bool
    {
        if (!is_readable($path)) {
            return false;
        }

        $content = file_get_contents($path, false, null, 0, self::SVG_VALIDATION_MAX_BYTES);
        if ($content === false || $content === '') {
            return false;
        }

        // 基础SVG结构验证
        if (!str_contains($content, '<svg')) {
            return false;
        }

        // 禁止脚本标签和危险元素
        $dangerousTags = [
            '<script',
            '<iframe',
            '<foreignobject',
            '<handler',
            '<embed',
            '<object',
            '<link'
        ];

        foreach ($dangerousTags as $tag) {
            if (stripos($content, $tag) !== false) {
                return false;
            }
        }

        // 禁止危险属性和事件处理器
        $dangerousPatterns = [
            '/on\w+\s*=/i',                                      // 事件处理器 (onclick, onload等)
            '/href\s*=\s*["\']\s*javascript:/i',                // javascript: URL
            '/style\s*=\s*["\'][^"\']*expression\s*\(/i',       // CSS expression
            '/url\s*\(\s*["\']?\s*javascript:/i',               // CSS url中的javascript
            '/@import/i',                                        // CSS @import
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
     * 
     * 根据MIME类型生成安全的文件名
     * 
     * @param string $realMime 真实的MIME类型
     * @param string $prefix 文件名前缀
     * @return string 生成的文件名
     */
    public static function generateFilename(string $realMime, string $prefix = ''): string
    {
        $extension = self::getSafeExtension($realMime);
        $prefix = $prefix ?: 'file';
        
        return uniqid($prefix, true) . '.' . $extension;
    }

    /**
     * 根据MIME类型获取安全扩展名
     * 
     * @param string $mime MIME类型
     * @return string 安全的扩展名
     */
    protected static function getSafeExtension(string $mime): string
    {
        self::initMimeToExtensionMap();

        // 在映射表中查找匹配的扩展名
        if (isset(self::$mimeToExtensionMap[$mime])) {
            return self::$mimeToExtensionMap[$mime];
        }

        // 如果是图片，尝试提取类型作为扩展名
        if (str_starts_with($mime, 'image/')) {
            $type = substr($mime, self::MIME_PREFIX_LENGTH);
            $allowedTypes = ['jpeg', 'png', 'gif', 'bmp', 'webp', 'svg+xml'];
            // 处理特殊类型
            if ($type === 'svg+xml') {
                return 'svg';
            }
            return in_array($type, $allowedTypes, true) ? $type : 'img';
        }

        // 如果是视频
        if (str_starts_with($mime, 'video/')) {
            $type = substr($mime, self::MIME_PREFIX_LENGTH);
            $typeMap = [
                'quicktime' => 'mov',
                'x-msvideo' => 'avi',
                'x-ms-wmv' => 'wmv',
                'x-flv' => 'flv',
                'x-matroska' => 'mkv',
            ];
            return $typeMap[$type] ?? ($type === 'mp4' ? 'mp4' : 'vid');
        }

        // 如果是音频
        if (str_starts_with($mime, 'audio/')) {
            $type = substr($mime, self::MIME_PREFIX_LENGTH);
            $typeMap = [
                'mpeg' => 'mp3',
                'x-ms-wma' => 'wma',
            ];
            return $typeMap[$type] ?? (in_array($type, ['wav', 'ogg', 'flac', 'aac'], true) ? $type : 'aud');
        }

        // 默认处理：转换MIME类型为安全扩展名
        $parts = explode('/', $mime, 2);
        if (count($parts) === 2) {
            $subtype = preg_replace('/[^a-z0-9]/', '', $parts[1]);
            return $subtype ?: 'bin';
        }

        return 'bin';
    }

    /**
     * 初始化MIME到扩展名的反向映射
     * 
     * @return void
     */
    private static function initMimeToExtensionMap(): void
    {
        if (self::$mimeToExtensionMap !== null) {
            return;
        }

        self::$mimeToExtensionMap = [];
        foreach (self::EXTENSION_MIME_MAP as $ext => $mimeType) {
            // 如果同一个MIME类型对应多个扩展名，保留第一个
            if (!isset(self::$mimeToExtensionMap[$mimeType])) {
                self::$mimeToExtensionMap[$mimeType] = $ext;
            }
        }
    }

    /**
     * 根据MIME类型获取扩展名
     * 
     * @param string $mime MIME类型
     * @return string 扩展名
     */
    protected static function getExtensionByMime(string $mime): string
    {
        self::initMimeToExtensionMap();

        if (isset(self::$mimeToExtensionMap[$mime])) {
            return self::$mimeToExtensionMap[$mime];
        }

        // 默认处理：从MIME类型提取扩展名
        $parts = explode('/', $mime, 2);
        if (count($parts) === 2) {
            $subtype = preg_replace('/[^a-z0-9]/', '', $parts[1]);
            return substr($subtype, 0, 3) ?: 'bin';
        }

        return 'bin';
    }

    /**
     * 上传文件（自动判断类型）
     * 
     * 自动识别文件类型并进行相应的验证和上传处理
     * 
     * @param UploadFile $file 上传的文件对象
     * @param string $path 上传路径
     * @param string $fileName 文件名
     * @param string|null $realMime 真实的MIME类型
     * @return array 上传结果信息
     */
    public static function upload(UploadFile $file, string $path = '', string $fileName = '', ?string $realMime = null): array
    {
        // 获取文件真实MIME类型
        $realMime = $realMime ?? self::getRealMimeType($file->getRealPath());

        // 自动检测文件类型并执行验证
        if (self::isImageMime($realMime)) {
            self::validateImage($file, $realMime);
            $fileType = 'image';
            $subdir = 'images';
        } elseif (self::isVideoMime($realMime)) {
            self::validateFile($file, $realMime);
            $fileType = 'video';
            $subdir = 'videos';
        } elseif (self::isAudioMime($realMime)) {
            self::validateFile($file, $realMime);
            $fileType = 'audio';
            $subdir = 'audios';
        } else {
            self::validateFile($file, $realMime);
            $fileType = 'file';
            $subdir = 'files';
        }

        // 构建存储路径
        $path = rtrim($path, '/');
        $path .= '/' . $subdir;
        $path .= '/' . date('Y-m-d');

        // 生成文件名
        $filename = empty($fileName) ? self::generateFilename($realMime) : $fileName;
        $filepath = trim($path . '/' . $filename, '/');

        // 获取文件内容并保存
        $fileContent = file_get_contents($file->getPathname());
        if ($fileContent === false) {
            throw new RuntimeException('无法读取上传的文件内容');
        }

        Storage::put($filepath, $fileContent);

        return [
            'path' => $filepath,
            'file_name' => $filename,
            'origin_name' => $file->getUploadName(),
            'url' => Storage::url($filepath),
            'adapter' => Storage::getConfig()['driver'],
            'mime_type' => $realMime,
            'size' => $file->getSize(),
            'extension' => self::getExtensionByMime($realMime),
            'type' => $fileType
        ];
    }
}