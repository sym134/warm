<?php

namespace warm\framework\filesystem\support;

/**
 * URL 生成器
 * 
 * 用于生成文件访问 URL
 */
class UrlGenerator
{
    /**
     * 生成文件 URL
     * 
     * @param string $path 文件路径
     * @param array $config 磁盘配置
     * @return string
     */
    public static function generate(string $path, array $config): string
    {
        $url = $config['url'] ?? null;
        
        if ($url === null) {
            return $path;
        }

        // 移除路径中的根目录前缀
        $root = $config['root'] ?? '';
        if ($root && strpos($path, $root) === 0) {
            $path = substr($path, strlen($root));
        }

        $path = ltrim($path, '/');
        $url = rtrim($url, '/');

        return $url . '/' . $path;
    }
}

