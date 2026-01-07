<?php

namespace warm\admin\service\system;

use warm\admin\service\AdminService;

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
        $config = array_merge(config('filesystems',[]), $data);
        $res = systemConfig()->set('filesystems', $config);
        if ($res) {
            //    LaravelBridge::reloadConfig();
        }
        return $res;
    }

    /**
     * 获取配置
     * @return array
     */
    public function list(): array
    {
        return array_merge(config('filesystems'), systemConfig()->get('filesystems', []));
    }
}