<?php

namespace warm\framework\filesystem\support;

use warm\common\service\SystemConfigService;

/**
 * 配置解析器
 * 
 * 从 SystemConfigService 获取文件系统配置
 */
class ConfigResolver
{
    /**
     * 解析文件系统配置
     * 
     * @param string|null $disk 磁盘名称，null 返回全部配置
     * @return array
     * @throws \warm\framework\filesystem\Exception\FilesystemException
     */
    public static function resolve(?string $disk = null): array
    {
        // 优先从 SystemConfigService 获取配置
        $config = self::loadConfig();

        if ($disk === null) {
            return $config;
        }

        $disks = $config['disks'] ?? [];
        $diskConfig = $disks[$disk] ?? null;

        if ($diskConfig === null) {
            throw new \warm\framework\filesystem\exception\FilesystemException("Disk [{$disk}] not found.");
        }

        return $diskConfig;
    }

    /**
     * 加载配置
     * 
     * @return array
     */
    protected static function loadConfig(): array
    {
        // 优先从 SystemConfigService 获取
        try {
            $config = SystemConfigService::get('filesystems');
            if (!empty($config) && is_array($config)) {
                return $config;
            }
        } catch (\Throwable $e) {
            // SystemConfigService 不可用时，回退到文件配置
        }

        // 回退到从配置文件读取
        $configPath = self::getConfigPath();
        
        if (!file_exists($configPath)) {
            // 使用默认配置
            return self::getDefaultConfig();
        }

        $config = require $configPath;
        // 处理环境变量
        return self::processEnv($config);
    }

    /**
     * 获取配置文件路径
     * 
     * @return string
     */
    protected static function getConfigPath(): string
    {
        // 优先使用 config() 辅助函数
        if (function_exists('config_path')) {
            return config_path('filesystems.php');
        }

        // 尝试多个可能的路径
        $basePath = function_exists('base_path') ? base_path() : getcwd();
        $possiblePaths = [
            $basePath . '/config/filesystems.php',
            getcwd() . '/config/filesystems.php',
        ];

        foreach ($possiblePaths as $path) {
            if (file_exists($path)) {
                return $path;
            }
        }

        return $basePath . '/config/filesystems.php';
    }

    /**
     * 处理环境变量
     * 
     * @param array $config
     * @return array
     */
    protected static function processEnv(array $config): array
    {
        if (!function_exists('env')) {
            return $config;
        }

        // 递归处理配置中的 env() 调用
        array_walk_recursive($config, function (&$value) {
            if (is_string($value) && preg_match('/^env\((.+)\)$/', $value, $matches)) {
                $envKey = trim($matches[1], '\'"');
                $value = env($envKey, $value);
            }
        });

        return $config;
    }

    /**
     * 获取默认配置
     * 
     * @return array
     */
    protected static function getDefaultConfig(): array
    {
        $basePath = function_exists('base_path') ? base_path() : getcwd();
        
        return [
            'default' => 'local',
            'disks' => [
                'local' => [
                    'driver' => 'local',
                    'root' => $basePath . '/storage/app',
                    'url' => '/storage',
                    'visibility' => 'public',
                ],
            ],
        ];
    }

    /**
     * 获取默认磁盘名称
     * 
     * @return string
     */
    public static function getDefaultDisk(): string
    {
        $config = self::resolve();
        return $config['default'] ?? 'local';
    }

    /**
     * 清除配置缓存
     * 
     * @return void
     */
    public static function clearCache(): void
    {
        // SystemConfigService 有自己的缓存机制，这里不需要额外操作
        // 如果需要清除 SystemConfigService 的缓存，可以调用：
        // SystemConfigService::clearCache('filesystems');
    }
}

