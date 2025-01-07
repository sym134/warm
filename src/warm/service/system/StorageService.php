<?php

namespace warm\service\system;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use warm\model\system\Config;
use warm\service\AdminService;

class StorageService extends AdminService
{
    public function saveConfig(array $data): bool
    {
        settings()->set('storage', $data);
        settings()->clearCache('storage');
        return true;
    }

    public function getEditData($id): Model|Collection|Builder|array|null
    {
        return settings()->get('storage');
    }
}
