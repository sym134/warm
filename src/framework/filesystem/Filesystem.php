<?php

namespace warm\framework\filesystem;

use ErrorException;
use FilesystemIterator;
use RuntimeException;
use SplFileObject;
use Symfony\Component\Finder\Finder;

/**
 * 文件系统操作类
 * 
 * 提供底层文件系统操作，类似于 Laravel 的 Illuminate\Filesystem\Filesystem
 * 用于直接操作本地文件系统，不涉及 Storage 磁盘的概念
 */
class Filesystem
{
    /**
     * 检查文件或目录是否存在
     * 
     * @param string $path
     * @return bool
     */
    public function exists(string $path): bool
    {
        return file_exists($path);
    }

    /**
     * 检查文件或目录是否不存在
     * 
     * @param string $path
     * @return bool
     */
    public function missing(string $path): bool
    {
        return !$this->exists($path);
    }

    /**
     * 读取文件内容
     * 
     * @param string $path
     * @param bool $lock 是否使用文件锁
     * @return string
     * @throws RuntimeException
     */
    public function get(string $path, bool $lock = false): string
    {
        if ($this->isFile($path)) {
            return $lock ? $this->sharedGet($path) : file_get_contents($path);
        }

        throw new RuntimeException("File does not exist at path {$path}.");
    }

    /**
     * 读取文件内容（带共享锁）
     * 
     * @param string $path
     * @return string
     */
    public function sharedGet(string $path): string
    {
        $contents = '';

        $handle = fopen($path, 'rb');

        if ($handle) {
            try {
                if (flock($handle, LOCK_SH)) {
                    clearstatcache(true, $path);

                    $contents = fread($handle, $this->size($path) ?: 1);

                    flock($handle, LOCK_UN);
                }
            } finally {
                fclose($handle);
            }
        }

        return $contents;
    }

    /**
     * 读取 JSON 文件并解码
     * 
     * @param string $path
     * @param int $flags JSON 解码标志
     * @param bool $lock 是否使用文件锁
     * @return array
     * @throws RuntimeException
     */
    public function json(string $path, int $flags = 0, bool $lock = false): array
    {
        $content = $this->get($path, $lock);
        $decoded = json_decode($content, true, 512, $flags);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new RuntimeException("Failed to decode JSON from path {$path}: " . json_last_error_msg());
        }

        return $decoded;
    }

    /**
     * 写入文件
     * 
     * @param string $path
     * @param string $contents
     * @param bool $lock 是否使用文件锁
     * @return int|false 写入的字节数，失败返回 false
     */
    public function put(string $path, string $contents, bool $lock = false)
    {
        return file_put_contents($path, $contents, $lock ? LOCK_EX : 0);
    }

    /**
     * 追加内容到文件
     * 
     * @param string $path
     * @param string $data
     * @return int|false 写入的字节数，失败返回 false
     */
    public function append(string $path, string $data)
    {
        return file_put_contents($path, $data, FILE_APPEND | LOCK_EX);
    }

    /**
     * 在文件开头添加内容
     * 
     * @param string $path
     * @param string $data
     * @return int|false 写入的字节数，失败返回 false
     */
    public function prepend(string $path, string $data)
    {
        if ($this->exists($path)) {
            return $this->put($path, $data . $this->get($path));
        }

        return $this->put($path, $data);
    }

    /**
     * 删除文件
     * 
     * @param string|array $paths
     * @return bool
     */
    public function delete($paths): bool
    {
        $paths = is_array($paths) ? $paths : func_get_args();

        $success = true;

        foreach ($paths as $path) {
            try {
                if (!@unlink($path)) {
                    $success = false;
                }
            } catch (ErrorException $e) {
                $success = false;
            }
        }

        return $success;
    }

    /**
     * 复制文件
     * 
     * @param string $from
     * @param string $to
     * @return bool
     */
    public function copy(string $from, string $to): bool
    {
        return copy($from, $to);
    }

    /**
     * 移动/重命名文件
     * 
     * @param string $from
     * @param string $to
     * @return bool
     */
    public function move(string $from, string $to): bool
    {
        return rename($from, $to);
    }

    /**
     * 获取文件大小（字节）
     * 
     * @param string $path
     * @return int
     */
    public function size(string $path): int
    {
        return filesize($path);
    }

    /**
     * 获取文件最后修改时间（时间戳）
     * 
     * @param string $path
     * @return int
     */
    public function lastModified(string $path): int
    {
        return filemtime($path);
    }

    /**
     * 获取文件 MIME 类型
     * 
     * @param string $path
     * @return string
     */
    public function mimeType(string $path): string
    {
        return mime_content_type($path);
    }

    /**
     * 检查是否为文件
     * 
     * @param string $path
     * @return bool
     */
    public function isFile(string $path): bool
    {
        return is_file($path);
    }

    /**
     * 检查是否为目录
     * 
     * @param string $path
     * @return bool
     */
    public function isDirectory(string $path): bool
    {
        return is_dir($path);
    }

    /**
     * 检查文件是否可读
     * 
     * @param string $path
     * @return bool
     */
    public function isReadable(string $path): bool
    {
        return is_readable($path);
    }

    /**
     * 检查文件是否可写
     * 
     * @param string $path
     * @return bool
     */
    public function isWritable(string $path): bool
    {
        return is_writable($path);
    }

    /**
     * 创建目录
     * 
     * @param string $path
     * @param int $mode 目录权限，默认 0755
     * @param bool $recursive 是否递归创建
     * @return bool
     */
    public function makeDirectory(string $path, int $mode = 0755, bool $recursive = false): bool
    {
        if (!$this->isDirectory($path)) {
            return mkdir($path, $mode, $recursive);
        }

        return true;
    }

    /**
     * 删除目录
     * 
     * @param string $directory
     * @param bool $preserve 是否保留目录本身
     * @return bool
     */
    public function deleteDirectory(string $directory, bool $preserve = false): bool
    {
        if (!$this->isDirectory($directory)) {
            return false;
        }

        $items = new FilesystemIterator($directory);

        foreach ($items as $item) {
            if ($item->isDir() && !$item->isLink()) {
                $this->deleteDirectory($item->getPathname());
            } else {
                $this->delete($item->getPathname());
            }
        }

        if (!$preserve) {
            @rmdir($directory);
        }

        return true;
    }

    /**
     * 清空目录
     * 
     * @param string $directory
     * @return bool
     */
    public function cleanDirectory(string $directory): bool
    {
        return $this->deleteDirectory($directory, true);
    }

    /**
     * 获取目录中的所有文件
     * 
     * @param string $directory
     * @param bool $recursive 是否递归
     * @return array
     */
    public function files(string $directory, bool $recursive = false): array
    {
        if (!$this->isDirectory($directory)) {
            return [];
        }

        $finder = Finder::create()
            ->files()
            ->in($directory);

        if (!$recursive) {
            $finder->depth(0);
        }

        $files = [];
        foreach ($finder as $file) {
            $files[] = $file->getPathname();
        }

        return $files;
    }

    /**
     * 获取目录中的所有文件（递归）
     * 
     * @param string $directory
     * @return array
     */
    public function allFiles(string $directory): array
    {
        return $this->files($directory, true);
    }

    /**
     * 获取目录中的所有子目录
     * 
     * @param string $directory
     * @param bool $recursive 是否递归
     * @return array
     */
    public function directories(string $directory, bool $recursive = false): array
    {
        if (!$this->isDirectory($directory)) {
            return [];
        }

        $finder = Finder::create()
            ->directories()
            ->in($directory);

        if (!$recursive) {
            $finder->depth(0);
        }

        $directories = [];
        foreach ($finder as $dir) {
            $directories[] = $dir->getPathname();
        }

        return $directories;
    }

    /**
     * 获取目录中的所有子目录（递归）
     * 
     * @param string $directory
     * @return array
     */
    public function allDirectories(string $directory): array
    {
        return $this->directories($directory, true);
    }

    /**
     * 获取文件扩展名
     * 
     * @param string $path
     * @return string
     */
    public function extension(string $path): string
    {
        return pathinfo($path, PATHINFO_EXTENSION);
    }

    /**
     * 获取文件类型
     * 
     * @param string $path
     * @return string
     */
    public function type(string $path): string
    {
        return filetype($path);
    }

    /**
     * 获取文件名称（不含扩展名）
     * 
     * @param string $path
     * @return string
     */
    public function name(string $path): string
    {
        return pathinfo($path, PATHINFO_FILENAME);
    }

    /**
     * 获取文件名称（含扩展名）
     * 
     * @param string $path
     * @return string
     */
    public function basename(string $path): string
    {
        return pathinfo($path, PATHINFO_BASENAME);
    }

    /**
     * 获取目录名称
     * 
     * @param string $path
     * @return string
     */
    public function dirname(string $path): string
    {
        return pathinfo($path, PATHINFO_DIRNAME);
    }

    /**
     * 获取文件路径（不含文件名）
     * 
     * @param string $path
     * @return string
     */
    public function path(string $path): string
    {
        return dirname($path);
    }

    /**
     * 获取文件的哈希值
     * 
     * @param string $path
     * @param string $algorithm 哈希算法，默认 md5
     * @return string
     */
    public function hash(string $path, string $algorithm = 'md5'): string
    {
        return hash_file($algorithm, $path);
    }

    /**
     * 获取文件的行数
     * 
     * @param string $path
     * @return int
     */
    public function lines(string $path): int
    {
        $file = new SplFileObject($path);
        $file->seek(PHP_INT_MAX);
        return $file->key() + 1;
    }
}

