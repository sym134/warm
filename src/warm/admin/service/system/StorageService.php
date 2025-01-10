<?php

namespace warm\admin\service\system;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use warm\admin\service\AdminService;

/**
 * 配置已保存
 * StorageService
 *
 * @author heimiao
 * Company:极智网络科技
 * @date 2025-01-09 10:09
 */
class StorageService extends AdminService
{
    public function saveConfig(array $data): bool
    {
        warmConfig()->set('storage', $data);
        warmConfig()->clearCache('storage');
        return true;
    }

    public function getEditData($id): Model|Collection|Builder|array|null
    {
        return warmConfig()->get('storage');
    }
}
