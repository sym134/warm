<?php

namespace warm\framework\filesystem;

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
     * 检查文件是否不存在
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
     * 存储上传的文件
     * 
     * @param string $path 存储路径（目录）
     * @param string|\SplFileInfo $file 文件路径或文件对象
     * @param array $config
     * @return string|false 返回存储的文件路径，失败返回 false
     */
    public function putFile(string $path, $file, array $config = [])
    {
        $filePath = is_string($file) ? $file : $file->getPathname();
        
        if (!file_exists($filePath)) {
            return false;
        }

        $name = basename($filePath);
        return $this->putFileAs($path, $file, $name, $config);
    }

    /**
     * 以指定名称存储上传的文件
     * 
     * @param string $path 存储路径（目录）
     * @param string|\SplFileInfo $file 文件路径或文件对象
     * @param string $name 存储的文件名
     * @param array $config
     * @return string|false 返回存储的文件路径，失败返回 false
     */
    public function putFileAs(string $path, $file, string $name, array $config = [])
    {
        $filePath = is_string($file) ? $file : $file->getPathname();
        
        if (!file_exists($filePath)) {
            return false;
        }

        $path = rtrim($path, '/') . '/' . $name;
        
        $resource = fopen($filePath, 'r');
        if ($resource === false) {
            return false;
        }

        try {
            $this->filesystem->writeStream($path, $resource, $config);
            return $path;
        } catch (UnableToWriteFile $e) {
            return false;
        } finally {
            if (is_resource($resource)) {
                fclose($resource);
            }
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
     * 递归获取所有文件
     * 
     * @param string $directory
     * @return array
     */
    public function allFiles(string $directory = ''): array
    {
        return $this->files($directory, true);
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
     * 递归获取所有目录
     * 
     * @param string $directory
     * @return array
     */
    public function allDirectories(string $directory = ''): array
    {
        return $this->directories($directory, true);
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
     * 获取文件的完整路径（仅本地存储）
     * 
     * @param string $path
     * @return string
     * @throws FilesystemException
     */
    public function path(string $path): string
    {
        $root = $this->config['root'] ?? '';
        
        if (empty($root)) {
            throw new FilesystemException("Path method is only supported for local filesystem.");
        }

        $path = str_replace(['\\', '/'], DIRECTORY_SEPARATOR, $path);
        $root = rtrim($root, DIRECTORY_SEPARATOR);
        
        return $root . DIRECTORY_SEPARATOR . ltrim($path, DIRECTORY_SEPARATOR);
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

    /**
     * 获取底层适配器实例
     * 
     * @return mixed
     */
    public function getAdapter()
    {
        // 如果 filesystem 是 Filesystem 实例，尝试获取底层适配器
        if ($this->filesystem instanceof \League\Flysystem\Filesystem) {
            // Filesystem 类有一个 adapter() 方法可以获取底层适配器
            if (method_exists($this->filesystem, 'adapter')) {
                return $this->filesystem->adapter();
            }
        }

        // 否则返回 filesystem 本身
        return $this->filesystem;
    }

    /**
     * 获取磁盘配置
     * 
     * @return array
     */
    public function getConfig(): array
    {
        return $this->config;
    }

    /**
     * 获取文件的哈希值（MD5）
     * 
     * @param string $path
     * @return string
     * @throws FilesystemException
     */
    public function hash(string $path): string
    {
        try {
            $contents = $this->filesystem->read($path);
            return md5($contents);
        } catch (UnableToReadFile $e) {
            throw new FilesystemException("File [{$path}] not found.", 0, $e);
        }
    }

    /**
     * 获取文件的校验和
     * 
     * @param string $path
     * @param string $algorithm 算法，默认为 'md5'
     * @return string
     * @throws FilesystemException
     */
    public function checksum(string $path, string $algorithm = 'md5'): string
    {
        try {
            $contents = $this->filesystem->read($path);
            
            if ($algorithm === 'md5') {
                return md5($contents);
            } elseif ($algorithm === 'sha1') {
                return sha1($contents);
            } elseif ($algorithm === 'sha256') {
                return hash('sha256', $contents);
            } elseif (in_array($algorithm, hash_algos())) {
                return hash($algorithm, $contents);
            } else {
                throw new FilesystemException("Unsupported hash algorithm [{$algorithm}].");
            }
        } catch (UnableToReadFile $e) {
            throw new FilesystemException("File [{$path}] not found.", 0, $e);
        }
    }
}

