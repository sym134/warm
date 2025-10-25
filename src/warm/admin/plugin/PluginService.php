<?php

namespace warm\admin\plugin;

use Illuminate\Database\Eloquent\Collection;
use warm\admin\Admin;
use warm\admin\model\Plugin;
use warm\admin\service\AdminService;

/**
 * 插件服务类
 * 
 * 提供插件管理相关功能，包括插件创建、启用/禁用、配置读取等
 */
class PluginService extends AdminService
{
    /**
     * 模型类名
     * 
     * @var string
     */
    protected string $modelName = Plugin::class;

    /**
     * 插件属性配置
     * 
     * @var array
     */
    protected array $property = [];
    
    /**
     * 插件路径
     * 
     * @var string
     */
    protected string $path = '';

    /**
     * 创建插件
     * 
     * 执行插件创建命令并保存插件信息到数据库
     * 
     * @param array $data 插件数据，必须包含name字段
     * @return bool 是否创建成功
     */
    public function store($data): bool
    {
        if (strtolower($data['name']) === 'app') {
            $this->setError('禁止使用app目录');
            return false;
        }
        // 判断数据库是否存在
        if (!is_null($this->modelName::query()->where('name', $data['name'])->first())) {
            $this->setError('插件已存在');
            return false;
        }
        [$state, $msg] = runCommand('cms-plugin:create ' . $data['name']);
        if ($state) {
            return parent::store($data);
        }
        $this->setError($msg);
        return false;
    }

    /**
     * 获取扩展包路径
     *
     * @param string|null $path 相对路径
     * @return string 完整路径
     * @throws \Exception 当配置的路径不是目录时抛出异常
     */
    public function path(string $path = null): string
    {
        if (!$this->path) {
            $this->path = Admin::config('app.extension.dir');
            if (!is_dir($this->path)) {
                throw new \Exception("The {$this->path} is not a directory.");
            }
        }

        $path = ltrim($path, '/');

        return $path ? $this->path . '/' . $path : $this->path;
    }

    /**
     * 排序处理
     * 
     * 重写排序方法，按ID倒序排列
     * 
     * @param mixed $query 查询构造器
     * @return void
     */
    public function sortable($query): void
    {
        $query->orderByDesc('id');
    }

    /**
     * 启用/禁用插件
     * 
     * 切换插件的启用状态
     * 
     * @param array $data 包含插件ID和当前启用状态的数据
     * @return int 更新影响的行数
     */
    public function enable($data): int
    {
        return $this->modelName::query()->where('id', $data['id'])->update(['is_enabled' => $data['enabled'] === 1 ? 0 : 1]);
    }

    /**
     * 插件配置文件
     *
     * 获取指定插件的配置信息
     *
     * @param string $name 插件名称
     * @return array|null 插件配置数组或null
     *
     * Author:sym
     * Date:2024/6/18 上午11:07
     * Company:极智科技
     */
    public function configApp(string $name): array|null
    {
        return config('plugin.' . $name . '.app');
    }

    /**
     * 获取已启用的插件列表
     * 
     * @return array|Collection 已启用的插件集合
     */
    public function getPlugins(): array|Collection
    {
        return $this->modelName::query()->where('is_enabled', 1)->get();
    }
}