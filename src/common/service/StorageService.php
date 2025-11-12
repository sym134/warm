<?php

namespace warm\common\service;

use finfo;
use Illuminate\Support\Facades\Storage;
use RuntimeException;
use Webman\Http\UploadFile;

/**
 * 存储服务类
 * 
 * 提供文件存储相关功能，包括文件上传、验证、路径管理等
 * 支持图片、视频、音频等多种文件类型的处理
 */
class StorageService extends BaseService
{
    /**
     * 允许的文件类型扩展名
     * 
     * @var array
     */
    protected static array $allowedFileExtensions = [];
    
    /**
     * 允许的图片类型扩展名
     * 
     * @var array
     */
    protected static array $allowedImageExtensions = [];
    
    /**
     * 最大文件大小
     * 
     * @var int
     */
    protected static int $maxSize = 0;

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
     * 
     * @return void
     */
    public static function initUploadConfig(): void
    {
        $config = systemConfig()->get('filesystems');

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
        if (!in_array(strtolower($realExt), self::$allowedImageExtensions)) {
            throw new RuntimeException('不允许的图片类型: ' . $realExt);
        }

        // 验证文件大小
        if ($file->getSize() > self::$maxSize) {
            throw new RuntimeException('图片大小超过限制: ' . self::$maxSize . ' bytes');
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
        if (!in_array(strtolower($realExt), self::$allowedFileExtensions)) {
            throw new RuntimeException('不允许的文件类型: ' . $realExt);
        }

        // 验证文件大小
        if ($file->getSize() > self::$maxSize) {
            throw new RuntimeException('文件大小超过限制: ' . self::$maxSize . ' bytes');
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
     * 
     * @param string $path 文件路径
     * @return string 真实的MIME类型
     */
    protected static function getRealMimeType(string $path): string
    {
        $info = new finfo(FILEINFO_MIME_TYPE);
        $mime = $info->file($path);
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
        // SVG需要特殊处理
        if ($mime === 'image/svg+xml') {
            return self::validateSvgFile($path);
        }

        // 通用图片验证
        return (bool)@getimagesize($path);
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
        $content = @file_get_contents($path, false, null, 0, 2048);
        if (!$content) return false;

        // 基础SVG结构验证
        if (!str_contains($content, '<svg') || !str_contains($content, '</svg>')) {
            return false;
        }

        // 禁止脚本标签
        $dangerousTags = [
            '<script', '<iframe', '<foreignobject', '<handler', '<script'
        ];

        foreach ($dangerousTags as $tag) {
            if (str_contains($content, $tag)) {
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
     * 
     * 根据MIME类型生成安全的文件名
     * 
     * @param string $realMime 真实的MIME类型
     * @param string $fileName 原始文件名
     * @return string 生成的文件名
     */
    public static function generateFilename(string $realMime, string $fileName = ''): string
    {
        $extension = self::getSafeExtension($realMime);

        return uniqid($fileName) . '.' . $extension;
    }

    /**
     * 根据MIME类型获取安全扩展名
     * 
     * @param string $mime MIME类型
     * @return string 安全的扩展名
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
            return in_array($type, ['jpeg', 'png', 'gif', 'bmp', 'webp']) ? $type : 'img';
        }

        // 如果是视频
        if (str_starts_with($mime, 'video/')) {
            $type = substr($mime, 6);
            return in_array($type, ['mp4', 'quicktime', 'x-msvideo']) ? $type : 'vid';
        }

        // 如果是音频
        if (str_starts_with($mime, 'audio/')) {
            $type = substr($mime, 6);
            return in_array($type, ['mpeg', 'wav', 'ogg', 'flac']) ? $type : 'aud';
        }

        // 默认处理：转换MIME类型为安全扩展名
        return preg_replace('/[^a-z0-9]/', '', substr($mime, strpos($mime, '/') + 1)) ?: 'bin';
    }

    /**
     * 根据MIME类型获取扩展名
     * 
     * @param string $mime MIME类型
     * @return string 扩展名
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

        // 自动检测文件类型
        if (self::isImageMime($realMime)) {
            // 图片类型
            self::validateImage($file, $realMime);
            $fileType = 'image';
            $path .= '/images'; // 为图片创建子目录
        } elseif (self::isVideoMime($realMime)) {
            // 视频类型
            self::validateFile($file, $realMime);
            $fileType = 'video';
            $path .='/videos'; // 为视频创建子目录
        } elseif (self::isAudioMime($realMime)) {
            // 音频类型
            self::validateFile($file, $realMime);
            $fileType = 'audio';
            $path  .= '/audios'; // 为音频创建子目录
        } else {
            // 其他文件类型
            self::validateFile($file, $realMime);
            $fileType = 'file';
            $path  .='/files'; // 为普通文件创建子目录
        }
        $path .= '/' . date('Y-m-d');
        $filename = empty($fileName) ? self::generateFilename($realMime) : $fileName;
        $filepath = trim($path . '/' . $filename, '/');

        // 保存文件
        Storage::put($filepath, file_get_contents($file->getPathname()));

        return [
            'path' => $filepath,
            'file_name' => $filename,
            'origin_name' => $file->getUploadName(),
            'url' => Storage::url($filepath),
            'adapter' => Storage::getDefaultDriver(),
            'mime_type' => $realMime,
            'size' => $file->getSize(),
            'extension' => self::getExtensionByMime($realMime),
            'type' => $fileType
        ];
    }
}