<?php

namespace warm\framework\filesystem;

use League\Flysystem\Filesystem;
use League\Flysystem\FilesystemOperator;
use League\Flysystem\UnableToReadFile;
use League\Flysystem\UnableToWriteFile;
use League\Flysystem\UnableToDeleteFile;
use League\Flysystem\UnableToSetVisibility;
use warm\framework\filesystem\support\UrlGenerator;
use warm\framework\filesystem\exception\FilesystemException;

/**
 * 文件系统适配器
 * 
 * 包装 League\Flysystem\Filesystem，提供 Laravel 风格的 API
 */
class FilesystemAdapter
{
    /**
     * Flysystem 实例
     * 
     * @var FilesystemOperator
     */
    protected FilesystemOperator $filesystem;

    /**
     * 磁盘配置
     * 
     * @var array
     */
    protected array $config;

    /**
     * 构造函数
     * 
     * @param FilesystemOperator $filesystem
     * @param array $config
     */
    public function __construct(FilesystemOperator $filesystem, array $config = [])
    {
        $this->filesystem = $filesystem;
        $this->config = $config;
    }

    /**
     * 检查文件是否存在
     * 
     * @param string $path
     * @return bool
     */
    public function exists(string $path): bool
    {
        return $this->filesystem->fileExists($path);
    }

    /**
     * 读取文件内容
     * 
     * @param string $path
     * @return string
     * @throws FilesystemException
     */
    public function get(string $path): string
    {
        try {
            return $this->filesystem->read($path);
        } catch (UnableToReadFile $e) {
            throw new FilesystemException("File [{$path}] not found.", 0, $e);
        }
    }

    /**
     * 写入文件
     * 
     * @param string $path
     * @param string|resource $contents
     * @param array $config
     * @return bool
     * @throws FilesystemException
     */
    public function put(string $path, $contents, array $config = []): bool
    {
        try {
            if (is_resource($contents)) {
                $this->filesystem->writeStream($path, $contents, $config);
            } else {
                $this->filesystem->write($path, $contents, $config);
            }
            return true;
        } catch (UnableToWriteFile $e) {
            throw new FilesystemException("Unable to write file [{$path}].", 0, $e);
        }
    }

    /**
     * 删除文件
     * 
     * @param string|array $paths
     * @return bool
     * @throws FilesystemException
     */
    public function delete($paths): bool
    {
        $paths = is_array($paths) ? $paths : func_get_args();

        $success = true;
        foreach ($paths as $path) {
            try {
                $this->filesystem->delete($path);
            } catch (UnableToDeleteFile $e) {
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
        try {
            $this->filesystem->copy($from, $to);
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * 移动文件
     * 
     * @param string $from
     * @param string $to
     * @return bool
     */
    public function move(string $from, string $to): bool
    {
        try {
            $this->filesystem->move($from, $to);
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * 获取文件大小
     * 
     * @param string $path
     * @return int
     */
    public function size(string $path): int
    {
        return $this->filesystem->fileSize($path);
    }

    /**
     * 获取文件最后修改时间
     * 
     * @param string $path
     * @return int
     */
    public function lastModified(string $path): int
    {
        return $this->filesystem->lastModified($path);
    }

    /**
     * 获取文件 MIME 类型
     * 
     * @param string $path
     * @return string
     */
    public function mimeType(string $path): string
    {
        return $this->filesystem->mimeType($path);
    }

    /**
     * 创建目录
     * 
     * @param string $path
     * @return bool
     */
    public function makeDirectory(string $path): bool
    {
        try {
            $this->filesystem->createDirectory($path);
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * 删除目录
     * 
     * @param string $path
     * @return bool
     */
    public function deleteDirectory(string $path): bool
    {
        try {
            $this->filesystem->deleteDirectory($path);
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * 列出目录下的文件
     * 
     * @param string $directory
     * @param bool $recursive
     * @return array
     */
    public function files(string $directory = '', bool $recursive = false): array
    {
        $contents = $this->listContents($directory, $recursive);
        
        return array_filter($contents, function ($item) {
            return $item['type'] === 'file';
        });
    }

    /**
     * 列出目录
     * 
     * @param string $directory
     * @param bool $recursive
     * @return array
     */
    public function directories(string $directory = '', bool $recursive = false): array
    {
        $contents = $this->listContents($directory, $recursive);
        
        return array_filter($contents, function ($item) {
            return $item['type'] === 'dir';
        });
    }

    /**
     * 列出目录内容
     * 
     * @param string $directory
     * @param bool $recursive
     * @return array
     */
    public function listContents(string $directory = '', bool $recursive = false): array
    {
        $listing = $this->filesystem->listContents($directory, $recursive);
        
        $result = [];
        foreach ($listing as $item) {
            $result[] = [
                'type' => $item->type(),
                'path' => $item->path(),
                'size' => $item->isFile() ? $item->fileSize() : null,
                'lastModified' => $item->lastModified(),
            ];
        }
        
        return $result;
    }

    /**
     * 获取文件可见性
     * 
     * @param string $path
     * @return string
     */
    public function getVisibility(string $path): string
    {
        return $this->filesystem->visibility($path)->toString();
    }

    /**
     * 设置文件可见性
     * 
     * @param string $path
     * @param string $visibility
     * @return bool
     */
    public function setVisibility(string $path, string $visibility): bool
    {
        try {
            $this->filesystem->setVisibility($path, $visibility);
            return true;
        } catch (UnableToSetVisibility $e) {
            return false;
        }
    }

    /**
     * 读取文件流
     * 
     * @param string $path
     * @return resource
     * @throws FilesystemException
     */
    public function readStream(string $path)
    {
        try {
            return $this->filesystem->readStream($path);
        } catch (UnableToReadFile $e) {
            throw new FilesystemException("File [{$path}] not found.", 0, $e);
        }
    }

    /**
     * 写入文件流
     * 
     * @param string $path
     * @param resource $resource
     * @param array $config
     * @return bool
     */
    public function writeStream(string $path, $resource, array $config = []): bool
    {
        try {
            $this->filesystem->writeStream($path, $resource, $config);
            return true;
        } catch (UnableToWriteFile $e) {
            return false;
        }
    }

    /**
     * 在文件开头追加内容
     * 
     * @param string $path
     * @param string $data
     * @return bool
     */
    public function prepend(string $path, string $data): bool
    {
        if ($this->exists($path)) {
            return $this->put($path, $data . $this->get($path));
        }

        return $this->put($path, $data);
    }

    /**
     * 在文件末尾追加内容
     * 
     * @param string $path
     * @param string $data
     * @return bool
     */
    public function append(string $path, string $data): bool
    {
        if ($this->exists($path)) {
            return $this->put($path, $this->get($path) . $data);
        }

        return $this->put($path, $data);
    }

    /**
     * 生成文件 URL
     * 
     * @param string $path
     * @return string
     */
    public function url(string $path): string
    {
        return UrlGenerator::generate($path, $this->config);
    }

    /**
     * 生成临时 URL（用于云存储）
     * 
     * @param string $path
     * @param \DateTimeInterface $expiration
     * @param array $options
     * @return string
     */
    public function temporaryUrl(string $path, \DateTimeInterface $expiration, array $options = []): string
    {
        // 如果适配器支持临时 URL，则使用
        if (method_exists($this->filesystem, 'temporaryUrl')) {
            return $this->filesystem->temporaryUrl($path, $expiration, $options);
        }

        // 否则返回普通 URL
        return $this->url($path);
    }

    /**
     * 获取底层 Flysystem 实例
     * 
     * @return FilesystemOperator
     */
    public function getDriver(): FilesystemOperator
    {
        return $this->filesystem;
    }
}

