<?php

namespace warm\admin\service\system;

use support\Db;
use warm\admin\Admin;
use warm\admin\model\system\File;
use warm\admin\service\AdminService;
use warm\framework\support\facade\Storage;

class FileService extends AdminService
{
    public function __construct()
    {
        parent::__construct();
        $this->modelName = File::class;
    }

    public function query()
    {
        return $this->modelName::query()->where('created_by', Admin::guard()->user(true)->id);
    }

    public function delete(string $ids): bool
    {
        Db::beginTransaction();
        try {
            $this->query()->whereIn($this->primaryKey(), explode(',', $ids))
                ->get()->each(function (File $file) {
                    Storage::delete($file->storage_path);
                });

            $result = $this->query()->whereIn($this->primaryKey(), explode(',', $ids))
                ->delete();
            if ($result) {
                $this->deleted($ids);
            }

            Db::commit();
        } catch (\Throwable $e) {
            Db::rollBack();
            admin_abort($e->getMessage());
        }

        return $result;
    }
}
