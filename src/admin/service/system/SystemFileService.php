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
        
        // 获取所有有分组的文件，统计每个分组的文件数量
        $groupStatsQuery = clone $baseQuery;
        $groupStats = $groupStatsQuery
            ->selectRaw('group_id, COUNT(*) as count')
            ->whereNotNull('group_id')
            ->where('group_id', '!=', '')
            ->groupBy('group_id')
            ->get()
            ->map(function ($item) use ($fileType) {
                // 尝试从某个文件获取分组名称（使用 remark 字段存储分组名称）
                $groupFile = SystemFile::baseQuery()
                    ->where('group_id', $item->group_id)
                    ->whereNotNull('remark')
                    ->where('remark', '!=', '')
                    ->first();
                
                $to = '?group_id=' . $item->group_id;
                if ($fileType) {
                    $to .= '&file_type=' . $fileType;
                }
                
                return [
                    'id' => $item->group_id,
                    'name' => $groupFile && $groupFile->remark ? $groupFile->remark : '分组 ' . $item->group_id,
                    'count' => (int)$item->count,
                    'to' => $to,
                ];
            })
            ->toArray();

        // 统计未分组的文件数量
        $ungroupedQuery = clone $baseQuery;
        $ungroupedCount = $ungroupedQuery
            ->where(function ($q) {
                $q->whereNull('group_id')->orWhere('group_id', '');
            })
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
            [
                'id' => 'ungrouped',
                'name' => '未分组',
                'count' => $ungroupedCount,
                'to' => $ungroupedTo,
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
     * @param mixed $groupId 目标分组ID
     * @return bool 是否移动成功
     */
    public function moveToGroup(string|array $ids, mixed $groupId): bool
    {
        $ids = is_array($ids) ? $ids : explode(',', $ids);
        
        Db::beginTransaction();
        try {
            $result = SystemFile::baseQuery()
                ->whereIn('id', $ids)
                ->update(['group_id' => $groupId === 'ungrouped' ? null : $groupId]);
            
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
                $query->where(function ($q) {
                    $q->whereNull('group_id')->orWhere('group_id', '');
                });
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
     * @return string|false 返回分组ID，失败返回false
     */
    public function createGroup(string $name): string|false
    {
        if (empty($name)) {
            $this->setError('分组名称不能为空');
            return false;
        }

        // 生成分组ID（使用时间戳+随机数）
        $groupId = 'group_' . time() . '_' . mt_rand(1000, 9999);

        Db::beginTransaction();
        try {
            // 创建一个虚拟文件记录来存储分组信息（使用 remark 字段存储分组名称）
            // 或者我们可以直接使用 group_id 作为标识，不创建文件
            // 这里我们使用一个标记文件来保存分组名称
            // 实际上，我们可以考虑在数据库中创建一个分组表，但为了简化，这里使用文件表的 remark 字段
            
            // 检查分组名是否已存在
            $exists = SystemFile::baseQuery()
                ->where('group_id', $groupId)
                ->where('remark', $name)
                ->exists();
            
            if (!$exists) {
                // 创建一个隐藏的文件记录来标识分组（不存储实际文件）
                SystemFile::baseQuery()->insert([
                    'group_id' => $groupId,
                    'origin_name' => '.group',
                    'remark' => $name,
                    'file_type' => 'file',
                    'storage_mode' => 'local',
                    'created_by' => Admin::user()?->id ?? 1,
                    'created_at' => date('Y-m-d H:i:s'),
                ]);
            }
            
            Db::commit();
            return $groupId;
        } catch (\Throwable $e) {
            Db::rollBack();
            $this->setError($e->getMessage());
            return false;
        }
    }

    /**
     * 删除分组
     * 
     * 删除指定的分组，分组下的文件将变为未分组状态
     * 
     * @param string $groupId 分组ID
     * @return bool 是否删除成功
     */
    public function deleteGroup(string $groupId): bool
    {
        if (empty($groupId)) {
            $this->setError('分组ID不能为空');
            return false;
        }

        Db::beginTransaction();
        try {
            // 将分组下的所有文件移动到未分组（group_id 设为 null）
            SystemFile::baseQuery()
                ->where('group_id', $groupId)
                ->update(['group_id' => null]);
            
            // 删除分组标记记录（如果有的话）
            SystemFile::baseQuery()
                ->where('group_id', $groupId)
                ->where('origin_name', '.group')
                ->delete();
            
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