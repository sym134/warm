<?php

namespace warm\admin\service\system;

use Illuminate\Database\Eloquent\Builder;
use support\Db;
use warm\admin\model\system\WechatMenu;
use warm\admin\service\AdminService;

/**
 * 微信菜单服务类
 * 
 * 提供微信菜单的增删改查功能
 */
class WechatMenuService extends AdminService
{
    /**
     * 模型类名
     *
     * @var string
     */
    protected string $modelName = WechatMenu::class;

    /**
     * 获取菜单列表（树形结构，用于CRUD嵌套显示）
     *
     * @return array
     */
    public function list(): array
    {
        // 获取所有一级菜单及其子菜单
        $firstLevelMenus = WechatMenu::where('parent_id', 0)
            ->orderBy('sort', 'asc')
            ->with(['subMenus' => function ($query) {
                $query->orderBy('sort', 'asc');
            }])
            ->get();

        // 构建树形结构数据
        $treeItems = $firstLevelMenus->map(function ($menu) {
            /** @var WechatMenu $menu */
            $menuArray = [
                'id' => $menu->id,
                'name' => $menu->name,
                'type' => $menu->type,
                'key' => $menu->key ?? '',
                'url' => $menu->url ?? '',
                'appid' => $menu->appid ?? '',
                'pagepath' => $menu->pagepath ?? '',
                'miniprogram_url' => $menu->miniprogram_url ?? '',
                'parent_id' => $menu->parent_id,
                'sort' => $menu->sort,
                'level' => 1,
                'created_at' => $menu->created_at,
                'updated_at' => $menu->updated_at,
            ];
            
            // 处理子菜单（children字段用于树形展开）
            $subMenus = $menu->subMenus;
            if ($subMenus && $subMenus->count() > 0) {
                $menuArray['children'] = $subMenus->map(function ($subMenu) use ($menu) {
                    /** @var WechatMenu $subMenu */
                    return [
                        'id' => $subMenu->id,
                        'name' => $subMenu->name,
                        'type' => $subMenu->type,
                        'key' => $subMenu->key ?? '',
                        'url' => $subMenu->url ?? '',
                        'appid' => $subMenu->appid ?? '',
                        'pagepath' => $subMenu->pagepath ?? '',
                        'miniprogram_url' => $subMenu->miniprogram_url ?? '',
                        'parent_id' => $subMenu->parent_id,
                        'sort' => $subMenu->sort,
                        'level' => 2,
                        'parent_name' => $menu->name, // 使用父菜单名称
                        'created_at' => $subMenu->created_at,
                        'updated_at' => $subMenu->updated_at,
                    ];
                })->toArray();
            } else {
                $menuArray['children'] = [];
            }
            
            return $menuArray;
        })->toArray();

        // 计算总数（一级菜单 + 二级菜单）
        $total = 0;
        foreach ($treeItems as $item) {
            $total += 1; // 一级菜单
            if (isset($item['children']) && is_array($item['children'])) {
                $total += count($item['children']); // 二级菜单
            }
        }

        return [
            'items' => $treeItems, // 树形结构用于CRUD嵌套显示
            'total' => $total
        ];
    }

    /**
     * 保存菜单
     *
     * @param array $data 菜单数据
     * @return bool
     * @throws \Exception
     */
    public function store(array $data): bool
    {
        return $this->saveMenu($data);
    }

    /**
     * 更新菜单
     *
     * @param mixed $primaryKey 主键值
     * @param array $data 更新的数据
     * @return bool
     * @throws \Exception
     */
    public function update(mixed $primaryKey, array $data): bool
    {
        return $this->saveMenu($data, $primaryKey);
    }

    /**
     * 保存菜单（新增或更新）
     *
     * @param array $data 菜单数据
     * @param mixed $id 菜单ID（更新时使用）
     * @return bool
     * @throws \Exception
     */
    private function saveMenu(array $data, mixed $id = null): bool
    {
        // 验证数据
        if (empty($data['name'])) {
            $this->setError('菜单名称不能为空');
            return false;
        }

        $parentId = $data['parent_id'] ?? 0;
        
        // 验证一级菜单数量限制
        if ($parentId == 0) {
            $count = WechatMenu::where('parent_id', 0)->count();
            if ($id) {
                // 编辑时，如果当前菜单是一级菜单，不计算自己
                $current = WechatMenu::find($id);
                if ($current && $current->parent_id == 0) {
                    $count--;
                }
            }
            if ($count >= 3) {
                $this->setError('一级菜单最多只能有3个');
                return false;
            }
        } else {
            // 验证二级菜单数量限制
            $count = WechatMenu::where('parent_id', $parentId)->count();
            if ($id) {
                // 编辑时，如果当前菜单是二级菜单，不计算自己
                $current = WechatMenu::find($id);
                if ($current && $current->parent_id == $parentId) {
                    $count--;
                }
            }
            if ($count >= 5) {
                $this->setError('二级菜单最多只能有5个');
                return false;
            }
        }

        // 验证小程序参数
        if (($data['type'] ?? '') == 'miniprogram') {
            if (empty($data['appid'])) {
                $this->setError('小程序AppID不能为空');
                return false;
            }
            if (empty($data['pagepath'])) {
                $this->setError('小程序路径不能为空');
                return false;
            }
            if (empty($data['miniprogram_url'])) {
                $this->setError('小程序备用网址不能为空');
                return false;
            }
        }

        Db::beginTransaction();
        try {
            $menuData = [
                'name' => $data['name'],
                'type' => $data['type'] ?? 'click',
                'key' => $data['key'] ?? '',
                'url' => $data['url'] ?? '',
                'appid' => $data['appid'] ?? '',
                'pagepath' => $data['pagepath'] ?? '',
                'miniprogram_url' => $data['miniprogram_url'] ?? '',
                'parent_id' => $parentId,
                'sort' => $data['sort'] ?? 0,
            ];

            if ($id) {
                $menu = WechatMenu::find($id);
                if (!$menu) {
                    throw new \Exception('菜单不存在');
                }
                
                // 如果已有子菜单，只能修改名称
                if ($menu->subMenus()->count() > 0 && ($data['type'] != $menu->type || !empty($data['key']) || !empty($data['url']) || !empty($data['appid']) || !empty($data['pagepath']) || !empty($data['miniprogram_url']))) {
                    throw new \Exception('已添加子菜单，仅可设置菜单名称');
                }
                
                $menu->fill($menuData)->save();
            } else {
                $menu = WechatMenu::create($menuData);
            }

            Db::commit();
            return true;
        } catch (\Exception $e) {
            Db::rollback();
            $this->setError($e->getMessage());
            return false;
        }
    }

    /**
     * 删除菜单
     *
     * @param mixed $id 菜单ID
     * @return bool
     * @throws \Exception
     */
    public function delete(mixed $id): bool
    {
        Db::beginTransaction();
        try {
            $menu = WechatMenu::find($id);
            if (!$menu) {
                throw new \Exception('菜单不存在');
            }

            // 如果是一级菜单，先删除所有子菜单
            if ($menu->parent_id == 0) {
                WechatMenu::where('parent_id', $id)->delete();
            }

            $menu->delete();

            Db::commit();
            return true;
        } catch (\Exception $e) {
            Db::rollback();
            $this->setError($e->getMessage());
            return false;
        }
    }

    /**
     * 获取编辑数据
     *
     * @param mixed $id 菜单ID
     * @return array
     */
    public function getEditData(mixed $id): array
    {
        $menu = WechatMenu::find($id);
        if (!$menu) {
            return [];
        }

        return $menu->toArray();
    }

    /**
     * 发布菜单到微信
     *
     * @return bool
     */
    public function publish(): bool
    {
        // 获取所有一级菜单及其子菜单
        $menus = WechatMenu::where('parent_id', 0)
            ->orderBy('sort', 'asc')
            ->with(['subMenus' => function ($query) {
                $query->orderBy('sort', 'asc');
            }])
            ->get();

        // 转换为微信菜单格式
        $wechatMenu = [];
        foreach ($menus as $menu) {
            $menuData = [
                'name' => $menu->name,
            ];

            // 如果有子菜单
            if ($menu->subMenus->count() > 0) {
                $menuData['sub_button'] = [];
                foreach ($menu->subMenus as $subMenu) {
                    $subMenuData = [
                        'type' => $subMenu->type,
                        'name' => $subMenu->name,
                    ];
                    
                    if ($subMenu->type == 'click') {
                        $subMenuData['key'] = $subMenu->key;
                    } elseif ($subMenu->type == 'view') {
                        $subMenuData['url'] = $subMenu->url;
                    } elseif ($subMenu->type == 'miniprogram') {
                        $subMenuData['appid'] = $subMenu->appid;
                        $subMenuData['pagepath'] = $subMenu->pagepath;
                        $subMenuData['url'] = $subMenu->miniprogram_url;
                    }
                    
                    $menuData['sub_button'][] = $subMenuData;
                }
            } else {
                // 没有子菜单，使用当前菜单的类型
                $menuData['type'] = $menu->type;
                if ($menu->type == 'click') {
                    $menuData['key'] = $menu->key;
                } elseif ($menu->type == 'view') {
                    $menuData['url'] = $menu->url;
                } elseif ($menu->type == 'miniprogram') {
                    $menuData['appid'] = $menu->appid;
                    $menuData['pagepath'] = $menu->pagepath;
                    $menuData['url'] = $menu->miniprogram_url;
                }
            }

            $wechatMenu[] = $menuData;
        }

        // TODO: 调用微信API发布菜单
        // 这里需要集成微信SDK来发布菜单
        // 示例：WechatService::createMenu($wechatMenu);

        return true;
    }
}

