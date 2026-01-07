<?php

namespace warm\admin\service\system;

use support\Db;
use warm\admin\Admin;
use warm\admin\model\system\SystemFile;
use warm\admin\service\AdminService;
use warm\framework\filesystem\facade\Storage;

/**
 * 系统文件服务类
 * 
 * 提供系统文件管理功能，包括文件删除等
 */
class SystemFileService extends AdminService
{
    /**
     * 构造函数
     * 
     * 初始化文件服务，设置模型名称
     */
    public function __construct()
    {
        parent::__construct();
        $this->modelName = SystemFile::class;
    }

    /**
     * 获取查询构造器
     * 
     * 重写查询方法，只查询当前用户上传的文件
     * 
     * @return mixed 查询构造器
     */
    public function query(): mixed
    {
        return $this->modelName::query()->where('created_by', Admin::guard()->user(true)->id);
    }

    /**
     * 删除文件
     *
     * @param string|int $ids 删除的ID列表
     * @return bool 是否删除成功
     */
    public function delete(string|int $ids): bool
    {
        Db::beginTransaction();
        try {
            $this->query()->whereIn($this->primaryKey(), explode(',', $ids))
                ->get()->each(function (SystemFile $file) {
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