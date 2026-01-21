<?php

namespace warm\admin\service\system;

use support\Db;
use warm\admin\Admin;
use warm\admin\model\system\SystemFile;
use warm\admin\model\system\SystemFileGroup;
use warm\admin\service\AdminService;
use warm\framework\filesystem\facade\Storage;

/**
 * 系统文件服务类
 * 
 * 提供系统文件管理功能，包括文件删除、移动、重命名等
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
     * 获取文件分组列表
     * 
     * 返回所有文件分组，用于左侧边栏显示
     * 包含每个分组的文件数量统计
     * 支持按文件类型筛选
     * 
     * @param string|null $fileType 文件类型筛选（可选）
     * @return array 分组列表
     */
    public function getGroups(?string $fileType = null): array
    {
        // 构建基础查询
        $baseQuery = SystemFile::baseQuery();
        
        // 如果指定了文件类型，添加筛选条件
        if ($fileType && $fileType !== 'all') {
            $baseQuery->where('file_type', $fileType);
        }
        
        // 获取分组列表（从分组表获取）
        $groupQuery = SystemFileGroup::baseQuery();
        if ($fileType && $fileType !== 'all') {
            $groupQuery->where(function ($q) use ($fileType) {
                $q->where('file_type', $fileType)->orWhereNull('file_type');
            });
        }
        
        $groupStats = $groupQuery
            ->orderBy('sort', 'asc')
            ->orderBy('id', 'asc')
            ->get()
            ->map(function ($group) use ($baseQuery, $fileType) {
                // 统计该分组的文件数量
                $countQuery = clone $baseQuery;
                $count = $countQuery->where('group_id', $group->id)->count();
                
                $to = '?group_id=' . $group->id;
                if ($fileType) {
                    $to .= '&file_type=' . $fileType;
                }
                
                return [
                    'id' => $group->id,
                    'name' => $group->name,
                    'count' => (int)$count,
                    'to' => $to,
                ];
            })
            ->toArray();

        // 统计未分组的文件数量
        $ungroupedQuery = clone $baseQuery;
        $ungroupedCount = $ungroupedQuery
            ->whereNull('group_id')
            ->count();

        // 统计全部文件数量
        $totalCount = $baseQuery->count();

        // 构建分组列表
        $allTo = '?group_id=';
        $ungroupedTo = '?group_id=ungrouped';
        if ($fileType) {
            $allTo .= '&file_type=' . $fileType;
            $ungroupedTo .= '&file_type=' . $fileType;
        }
        
        $groups = [
            [
                'id' => null,
                'name' => '全部',
                'count' => $totalCount,
                'to' => $allTo,
            ],
        ];

        // 合并自定义分组
        $groups = array_merge($groups, $groupStats);

        return $groups;
    }

    /**
     * 移动文件到指定分组
     * 
     * @param string|array $ids 文件ID列表（逗号分隔的字符串或数组）
     * @param mixed $groupId 目标分组ID（null 表示未分组）
     * @return bool 是否移动成功
     */
    public function moveToGroup(string|array $ids, mixed $groupId): bool
    {
        $ids = is_array($ids) ? $ids : explode(',', $ids);
        
        // 转换为整数（如果是字符串且是数字）
        $ids = array_map(function ($id) {
            return is_numeric($id) ? (int)$id : $id;
        }, $ids);
        
        Db::beginTransaction();
        try {
            // 如果 groupId 不是 null 且不是 'ungrouped'，验证分组是否存在
            if ($groupId !== null && $groupId !== '' && $groupId !== 'ungrouped') {
                $group = SystemFileGroup::find($groupId);
                if (!$group) {
                    $this->setError('分组不存在');
                    Db::rollBack();
                    return false;
                }
            }
            
            $finalGroupId = ($groupId === null || $groupId === '' || $groupId === 'ungrouped') ? null : (int)$groupId;
            
            $result = SystemFile::baseQuery()
                ->whereIn('id', $ids)
                ->update(['group_id' => $finalGroupId]);
            
            Db::commit();
            return $result > 0;
        } catch (\Throwable $e) {
            Db::rollBack();
            admin_abort($e->getMessage());
        }

        return false;
    }

    /**
     * 重命名文件
     * 
     * @param int $id 文件ID
     * @param string $newName 新文件名
     * @return bool 是否重命名成功
     */
    public function rename(int $id, string $newName): bool
    {
        Db::beginTransaction();
        try {
            $result = SystemFile::baseQuery()
                ->where('id', $id)
                ->update(['origin_name' => $newName]);
            
            Db::commit();
            return $result > 0;
        } catch (\Throwable $e) {
            Db::rollBack();
            admin_abort($e->getMessage());
        }

        return false;
    }

    /**
     * 重写列表查询，支持文件类型和分组筛选
     * 
     * @return mixed 查询构造器
     */
    public function listQuery(): mixed
    {
        $query = parent::listQuery();

        // 文件类型筛选（从file_type参数获取）
        $fileType = request()->input('file_type');
        if ($fileType && $fileType !== 'all') {
            $query->where('file_type', $fileType);
        }

        // 分组筛选（从group_id参数获取）
        $groupId = request()->input('group_id');
        if ($groupId !== null && $groupId !== '') {
            if ($groupId === 'ungrouped') {
                $query->whereNull('group_id');
            } else {
                $query->where('group_id', $groupId);
            }
        }

        // 文件来源筛选（从storage_mode参数获取）
        $storageMode = request()->input('storage_mode');
        if ($storageMode) {
            $query->where('storage_mode', $storageMode);
        }

        // 名称搜索（从origin_name参数获取）
        $originName = request()->input('origin_name');
        if ($originName) {
            $query->where('origin_name', 'like', '%' . $originName . '%');
        }

        return $query;
    }

    /**
     * 重写删除方法，支持物理删除文件
     * 
     * @param string|int $ids 删除的ID列表
     * @return bool 是否删除成功
     */
    public function delete(string|int $ids): bool
    {
        $ids = is_array($ids) ? $ids : explode(',', $ids);
        
        Db::beginTransaction();
        try {
            // 获取要删除的文件信息
            $files = SystemFile::baseQuery()->whereIn('id', $ids)->get();
            
            // 删除数据库记录
            $result = SystemFile::baseQuery()->whereIn('id', $ids)->delete();
            
            // 尝试删除物理文件（可选，根据需求决定）
            foreach ($files as $file) {
                if ($file->storage_path && Storage::exists($file->storage_path)) {
                    try {
                        Storage::delete($file->storage_path);
                    } catch (\Throwable $e) {
                        // 物理文件删除失败不影响数据库删除
                    }
                }
            }
            
            Db::commit();
            return $result > 0;
        } catch (\Throwable $e) {
            Db::rollBack();
            admin_abort($e->getMessage());
        }

        return false;
    }

    /**
     * 创建分组
     * 
     * 创建一个新的文件分组
     * 
     * @param string $name 分组名称
     * @param string|null $fileType 文件类型（可选）
     * @return int|false 返回分组ID，失败返回false
     */
    public function createGroup(string $name, ?string $fileType = null): int|false
    {
        if (empty($name)) {
            $this->setError('分组名称不能为空');
            return false;
        }

        Db::beginTransaction();
        try {
            // 检查分组名是否已存在（同类型下）
            $exists = SystemFileGroup::baseQuery()
                ->where('name', $name)
                ->when($fileType, function ($q) use ($fileType) {
                    $q->where(function ($query) use ($fileType) {
                        $query->where('file_type', $fileType)->orWhereNull('file_type');
                    });
                })
                ->exists();
            
            if ($exists) {
                $this->setError('分组名称已存在');
                Db::rollBack();
                return false;
            }
            
            // 获取最大排序值
            $maxSort = SystemFileGroup::baseQuery()
                ->when($fileType, function ($q) use ($fileType) {
                    $q->where(function ($query) use ($fileType) {
                        $query->where('file_type', $fileType)->orWhereNull('file_type');
                    });
                })
                ->max('sort') ?? 0;
            
            // 创建分组
            $group = SystemFileGroup::create([
                'name' => $name,
                'file_type' => $fileType,
                'sort' => $maxSort + 1,
                'created_by' => Admin::user()?->id ?? 1,
            ]);
            
            Db::commit();
            return $group->id;
        } catch (\Throwable $e) {
            Db::rollBack();
            $this->setError($e->getMessage());
            return false;
        }
    }

    /**
     * 删除分组
     * 
     * 删除指定的分组，同时删除该分组下的所有文件
     * 
     * @param int|string $groupId 分组ID
     * @return bool 是否删除成功
     */
    public function deleteGroup(int|string $groupId): bool
    {
        if (empty($groupId)) {
            $this->setError('分组ID不能为空');
            return false;
        }

        Db::beginTransaction();
        try {
            // 检查分组是否存在
            $group = SystemFileGroup::find($groupId);
            if (!$group) {
                $this->setError('分组不存在');
                Db::rollBack();
                return false;
            }
            
            // 获取分组下的所有文件
            $files = SystemFile::baseQuery()
                ->where('group_id', $groupId)
                ->get();
            
            // 删除分组下的所有文件
            foreach ($files as $file) {
                // 删除物理文件
                if ($file->storage_path) {
                    try {
                        Storage::delete($file->storage_path);
                    } catch (\Throwable $e) {
                        // 物理文件删除失败不影响数据库删除
                    }
                }
                // 删除数据库记录
                $file->delete();
            }
            
            // 删除分组记录
            $group->delete();
            
            Db::commit();
            return true;
        } catch (\Throwable $e) {
            Db::rollBack();
            $this->setError($e->getMessage());
            return false;
        }
    }

    /**
     * 重命名分组
     * 
     * @param int|string $groupId 分组ID
     * @param string $newName 新名称
     * @return bool 是否重命名成功
     */
    public function renameGroup(int|string $groupId, string $newName): bool
    {
        if (empty($groupId)) {
            $this->setError('分组ID不能为空');
            return false;
        }

        if (empty($newName)) {
            $this->setError('分组名称不能为空');
            return false;
        }

        Db::beginTransaction();
        try {
            // 检查分组是否存在
            $group = SystemFileGroup::find($groupId);
            if (!$group) {
                $this->setError('分组不存在');
                Db::rollBack();
                return false;
            }
            
            // 检查新名称是否已存在（同类型下）
            $exists = SystemFileGroup::baseQuery()
                ->where('name', $newName)
                ->where('id', '!=', $groupId)
                ->where(function ($q) use ($group) {
                    if ($group->file_type) {
                        $q->where('file_type', $group->file_type);
                    } else {
                        $q->whereNull('file_type');
                    }
                })
                ->exists();
            
            if ($exists) {
                $this->setError('分组名称已存在');
                Db::rollBack();
                return false;
            }
            
            // 更新分组名称
            $group->name = $newName;
            $group->save();
            
            Db::commit();
            return true;
        } catch (\Throwable $e) {
            Db::rollBack();
            $this->setError($e->getMessage());
            return false;
        }
    }

    /**
     * 重写列表方法，格式化返回数据
     * 
     * @return array 列表数据
     */
    public function list(): array
    {
        $result = parent::list();
        
        // 格式化文件大小显示
        if (isset($result['data']['items'])) {
            foreach ($result['data']['items'] as &$item) {
                // 确保文件大小字段存在
                if (isset($item['file_size'])) {
                    $item['file_size_kb'] = round($item['file_size'], 2);
                    $item['file_size_mb'] = round($item['file_size'] / 1024, 2);
                }
                
                // 格式化文件类型图标
                if (isset($item['file_type'])) {
                    $item['file_type_icon'] = $this->getFileTypeIcon($item['file_type'], $item['mime_type'] ?? '');
                }
            }
        }
        
        return $result;
    }

    /**
     * 获取文件类型图标
     * 
     * @param string $fileType 文件类型
     * @param string $mimeType MIME类型
     * @return string 图标类名
     */
    protected function getFileTypeIcon(string $fileType, string $mimeType = ''): string
    {
        return match ($fileType) {
            'image' => 'fa fa-image',
            'video' => 'fa fa-video',
            'audio' => 'fa fa-music',
            default => 'fa fa-file',
        };
    }
}