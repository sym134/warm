<?php

namespace warm\admin\service\system;

use Illuminate\Filesystem\Filesystem;
use Illuminate\Filesystem\FilesystemManager;
use Illuminate\Support\Facades\Config;
use warm\admin\service\AdminService;
use warm\bootstrap\LaravelBootstrap;
use warm\common\config\FilesystemConfig;

/**
 * 存储配置服务类
 *
 * 提供存储配置管理功能
 *
 * @author heimiao
 * Company:极智网络科技
 * @date 2025-01-09 10:09
 */
class StorageService extends AdminService
{
    /**
     * 保存配置
     *
     * @param array $data 配置数据
     * @return bool 是否保存成功
     */
    public function saveConfig(array $data): bool
    {
        $config = array_merge(FilesystemConfig::CONFIG, $data);
//        \Illuminate\Support\Facades\Config::set('filesystems', $data);
//        app()->forgetInstance('filesystem');
//        app()->forgetInstance('filesystem.disk');
//        app()->forgetInstance('filesystem.cloud');

//        app()->singleton('files', fn() => new Filesystem());
//        app()->singleton('filesystem', fn($app) => new FilesystemManager($app));

//        $app=app();
        // 移除已实例化的文件系统组件，让下次重新创建
//        foreach (['filesystem', 'filesystem.disk', 'filesystem.cloud'] as $key) {
//            if ($app->bound($key)) {
//                $app->forgetInstance($key);
//            }
//        }
        // 清空 Storage facade 内部缓存
//        \Illuminate\Support\Facades\Storage::clearResolvedInstances();
//        \Illuminate\Support\Facades\Storage::setFacadeApplication($app);
$res = systemConfig()->set('filesystems', $config);
if ($res){
    LaravelBootstrap::reloadConfig();
}
        return $res;
    }

    /**
     * 获取配置
     * @return array
     */
    public function list(): array
    {
        return array_merge(FilesystemConfig::CONFIG, systemConfig()->get('filesystems', []));
    }
}