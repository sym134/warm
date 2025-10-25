<?php

namespace warm\admin\support;

/**
 * Zip helper.
 *
 * @author Alexey Bobkov, Samuel Georges
 *
 * Usage:
 *
 *   Zip::make('file.zip', '/some/path/*.php');
 *
 *   Zip::make('file.zip', function($zip) {
 *
 *       // Add all PHP files and directories
 *       $zip->add('/some/path/*.php');
 *
 *       // Do not include subdirectories, one level only
 *       $zip->add('/non/recursive/*', ['recursive' => false]);
 *
 *       // Add multiple paths
 *       $zip->add([
 *           '/collection/of/paths/*',
 *           '/a/single/file.php'
 *       ]);
 *
 *       // Add all INI files to a zip folder "config"
 *       $zip->folder('/config', '/path/to/config/*.ini');
 *
 *       // Add multiple paths to a zip folder "images"
 *       $zip->folder('/images', function($zip) {
 *           $zip->add('/my/gifs/*.gif', );
 *           $zip->add('/photo/reel/*.{png,jpg}', );
 *       });
 *
 *       // Remove these files/folders from the zip
 *       $zip->remove([
 *           '.htaccess',
 *           'config.php',
 *           'some/folder'
 *       ]);
 *
 *   });
 *
 *   Zip::extract('file.zip', '/destination/path');
 */

use warm\admin\support\Helper;
use ZipArchive;

/**
 * Zip压缩包处理类
 * 
 * 扩展自PHP的ZipArchive类，提供更便捷的ZIP文件创建和解压功能。
 * 支持多种方式添加文件和目录到ZIP包中，以及从ZIP包中移除文件。
 * 
 * 主要功能：
 * 1. 创建ZIP文件并添加文件/目录
 * 2. 解压ZIP文件
 * 3. 在ZIP中创建文件夹
 * 4. 从ZIP中移除文件
 */
class Zip extends ZipArchive
{
    /**
     * 文件夹前缀
     * 
     * 用于在ZIP中创建嵌套文件夹结构时的路径前缀
     * 
     * @var string
     */
    protected $folderPrefix = '';

    /**
     * 解压ZIP文件
     * 
     * 将指定的ZIP文件解压到目标目录
     * 
     * @param string $source ZIP文件路径
     * @param string $destination 解压目标路径
     * @param array $options 解压选项，支持'mask'权限掩码
     * @return bool 解压是否成功
     */
    public static function extract(string $source, string $destination, array $options = []): bool
    {
        // 提取选项参数，默认权限掩码为0777
        extract(array_merge([
            'mask' => 0777,
        ], $options));

        // 检查目标目录是否存在，不存在则创建
        if (file_exists($destination) || mkdir($destination, $mask, true)) {
            // 创建ZIP对象
            $zip = new ZipArchive;
            // 打开ZIP文件
            if ($zip->open($source) === true) {
                // 解压到目标目录
                $zip->extractTo($destination);
                // 关闭ZIP文件
                $zip->close();

                return true;
            }
        }

        return false;
    }

    /**
     * 创建新的ZIP文件
     * 
     * 创建一个新的ZIP文件，并根据源添加文件或目录
     * 
     * @param string $destination ZIP文件目标路径
     * @param mixed $source 源文件/目录路径、回调函数或路径数组
     * @param array $options 创建选项
     * @return self ZIP对象实例
     */
    public static function make(string $destination, mixed $source, array $options = []): Zip
    {
        // 创建ZIP对象
        $zip = new self;
        // 打开或创建ZIP文件
        $zip->open($destination, ZIPARCHIVE::CREATE | ZipArchive::OVERWRITE);

        // 根据源类型添加文件
        if (is_string($source)) {
            // 字符串源，直接添加
            $zip->add($source, $options);
        } elseif (is_callable($source)) {
            // 回调函数源，调用回调函数处理
            $source($zip);
        } elseif (is_array($source)) {
            // 数组源，遍历添加每个路径
            foreach ($source as $_source) {
                $zip->add($_source, $options);
            }
        }

        // 关闭ZIP文件
        $zip->close();

        return $zip;
    }

    /**
     * 添加源文件到ZIP
     * 
     * 将指定的文件或目录添加到ZIP包中，支持通配符和多种选项
     * 
     * @param mixed $source 源文件/目录路径
     * @param array $options 添加选项
     *   - recursive: 是否递归添加子目录，默认true
     *   - includeHidden: 是否包含隐藏文件，默认false
     *   - basedir: 基础目录路径
     *   - baseglob: 基础通配符
     * @return self ZIP对象实例
     */
    public function add(mixed $source, array $options = []): static
    {
        /*
         * 如果提供的是目录，则转换为有用的通配符表达式
         *
         * 隐藏文件的通配符规则：
         * - 不以'.'开头的文件
         * - 以'.'开头但后面跟非'.'字符的文件
         * - 以'..'开头但后面至少有一个字符的文件
         */
        if (is_dir($source)) {
            // 根据选项决定是否包含隐藏文件
            $includeHidden = isset($options['includeHidden']) && $options['includeHidden'];
            $wildcard = $includeHidden ? '{*,.[!.]*,..?*}' : '*';
            // 构造通配符路径
            $source = implode('/', [dirname($source), Helper::basename($source), $wildcard]);
        }

        // 提取选项参数
        extract(array_merge([
            'recursive' => true,
            'includeHidden' => false,
            'basedir' => dirname($source),
            'baseglob' => Helper::basename($source),
        ], $options));

        // 处理单个文件
        if (is_file($source)) {
            $files = [$source];
            $recursive = false;
        } else {
            // 处理通配符路径
            $files = glob($source, GLOB_BRACE);
            $folders = glob(dirname($source).'/*', GLOB_ONLYDIR);
        }

        // 添加文件到ZIP
        foreach ($files as $file) {
            // 跳过非文件项
            if (! is_file($file)) {
                continue;
            }

            // 计算本地路径
            $localpath = $this->removePathPrefix($basedir.'/', dirname($file).'/');
            $localfile = $this->folderPrefix.$localpath.Helper::basename($file);
            // 添加文件到ZIP
            $this->addFile($file, $localfile);
        }

        // 如果不递归，则直接返回
        if (! $recursive) {
            return $this;
        }

        // 递归添加文件夹
        foreach ($folders as $folder) {
            // 跳过非目录项
            if (! is_dir($folder)) {
                continue;
            }

            // 计算本地路径并添加空目录
            $localpath = $this->folderPrefix.$this->removePathPrefix($basedir.'/', $folder.'/');
            $this->addEmptyDir($localpath);
            // 递归添加文件夹内容
            $this->add($folder.'/'.$baseglob, array_merge($options, ['basedir' => $basedir]));
        }

        return $this;
    }

    /**
     * 在ZIP中创建新文件夹并添加源文件（可选）
     * 
     * @param string $name 文件夹名称
     * @param mixed|null $source 源文件/目录路径、回调函数或路径数组
     * @return self ZIP对象实例
     */
    public function folder(string $name, mixed $source = null): static
    {
        // 保存当前文件夹前缀
        $prefix = $this->folderPrefix;
        // 添加空目录
        $this->addEmptyDir($prefix.$name);
        
        // 如果没有提供源，则直接返回
        if ($source === null) {
            return $this;
        }

        // 设置新的文件夹前缀
        $this->folderPrefix = $prefix.$name.'/';

        // 根据源类型添加文件
        if (is_string($source)) {
            $this->add($source);
        } elseif (is_callable($source)) {
            $source($this);
        } elseif (is_array($source)) {
            foreach ($source as $_source) {
                $this->add($_source);
            }
        }

        // 恢复文件夹前缀
        $this->folderPrefix = $prefix;

        return $this;
    }

    /**
     * 从ZIP集合中移除文件或文件夹
     * 
     * 注意：不支持通配符
     * 
     * @param string $source 需要移除的文件或文件夹路径
     * @return self ZIP对象实例
     */
    public function remove(string $source): static
    {
        // 如果是数组，则递归移除每个项
        if (is_array($source)) {
            foreach ($source as $_source) {
                $this->remove($_source);
            }
        }

        // 如果不是字符串，则直接返回
        if (! is_string($source)) {
            return $this;
        }

        // 处理以'/'开头的路径
        if (str_starts_with($source, '/')) {
            $source = substr($source, 1);
        }

        // 遍历ZIP中的所有文件，移除匹配的项
        for ($i = 0; $i < $this->numFiles; $i++) {
            $stats = $this->statIndex($i);
            if (str_starts_with($stats['name'], $source)) {
                $this->deleteIndex($i);
            }
        }

        return $this;
    }

    /**
     * 从路径中移除前缀
     * 
     * @param string $prefix 要移除的前缀
     * @param string $path 完整路径
     * @return string 移除前缀后的路径
     */
    protected function removePathPrefix(string $prefix, string $path): string
    {
        // 如果路径以指定前缀开头，则移除前缀
        return (str_starts_with($path, $prefix))
            ? substr($path, strlen($prefix))
            : $path;
    }
}