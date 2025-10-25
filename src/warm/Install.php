<?php

namespace warm;

/**
 * 安装类
 *
 * 处理Webman插件的安装和卸载操作
 * 用于在插件安装时复制必要的文件和目录
 */
class Install
{
    /**
     * 标识为Webman插件
     *
     * @var bool
     */
    const WEBMAN_PLUGIN = true;

    /**
     * 路径映射关系
     *
     * 定义源路径和目标路径的对应关系，用于文件复制
     *
     * @var array
     */
    protected static $pathRelation = array(
        '../config/plugin/jizhi/warm' => 'config/plugin/jizhi/warm',
        '../config/database.php' => 'config/database.php',
        '../config/redis.php' => 'config/redis.php',
        '../admin-assets' => 'public/admin-assets',
        '../resource' => 'resource',
        '../database' => 'database',
        '../.example.env' => '.env',
    );

    /**
     * 安装方法
     *
     * 执行插件安装操作
     *
     * @return void
     */
    public static function install()
    {
        static::installByRelation();
    }

    /**
     * 卸载方法
     *
     * 执行插件卸载操作
     *
     * @return void
     */
    public static function uninstall()
    {
        self::uninstallByRelation();
    }

    /**
     * 根据路径关系执行安装
     *
     * 遍历路径映射关系，创建目录并复制文件
     *
     * @return void
     */
    public static function installByRelation()
    {
        foreach (static::$pathRelation as $source => $dest) {
            if ($pos = strrpos($dest, '/')) {
                $parent_dir = base_path() . '/' . substr($dest, 0, $pos);
                if (!is_dir($parent_dir)) {
                    mkdir($parent_dir, 0777, true);
                }
            }

            copy_dir(__DIR__ . "/$source", base_path() . "/$dest", true);
            echo "Create $dest
";
        }
    }

    /**
     * 根据路径关系执行卸载
     *
     * 遍历路径映射关系，删除对应的文件和目录
     *
     * @return void
     */
    public static function uninstallByRelation()
    {
        foreach (static::$pathRelation as $source => $dest) {
            $path = base_path() . "/$dest";
            if (!is_dir($path) && !is_file($path)) {
                continue;
            }
            echo "Remove $dest
";
            if (is_file($path) || is_link($path)) {
                unlink($path);
                continue;
            }
            remove_dir($path);
        }
    }

}