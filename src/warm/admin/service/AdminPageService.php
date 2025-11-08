<?php

namespace warm\admin\service;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use warm\admin\model\AdminPage;

/**
 * 管理页面服务类
 * 
 * 提供页面管理相关功能，包括页面结构存储、缓存处理等
 * 
 * @method AdminPage getModel() 获取模型实例
 * @method AdminPage|Builder query() 获取查询构造器
 */
class AdminPageService extends AdminService
{
    /**
     * 模型类名
     * 
     * @var string
     */
    protected string $modelName = AdminPage::class;

    /**
     * 缓存键前缀
     * 
     * @var string
     */
    public string $cacheKeyPrefix = 'admin_page-';

    /**
     * 保存前处理
     * 
     * 验证页面结构并检查标识是否已存在
     * 
     * @param array $data 保存的数据
     * @param string $primaryKey 主键值
     * @return void
     */
    public function saving(array &$data, $primaryKey = ''): void
    {
        $data['schema'] = data_get($data, 'page.schema');
        admin_abort_if(blank($data['schema']), translator('admin.pages.schema_cannot_be_empty'));
        unset($data['page']);

        $exists = $this->query()
            ->where('sign', $data['sign'])
            ->when($primaryKey, fn($q) => $q->where('id', '<>', $primaryKey))
            ->exists();

        admin_abort_if($exists, translator('admin.pages.sign_exists'));
    }

    /**
     * 保存后处理
     * 
     * 清除页面缓存
     * 
     * @param mixed $model 保存的模型实例
     * @param bool $isEdit 是否为编辑操作
     * @return void
     */
    public function saved(mixed $model, $isEdit = false): void
    {
        if ($isEdit) {
            cache()->delete($this->cacheKeyPrefix . $model->sign);
        }
    }

    /**
     * 删除页面
     *
     * 删除页面并清除相关缓存
     *
     * @param string|int $ids 删除的ID列表
     * @return bool 是否删除成功
     */
    public function delete(string|int $ids): bool
    {
        $this->query()->whereIn('id', explode(',', $ids))->get()->map(function ($item) {
            cache()->delete($this->cacheKeyPrefix . $item->sign);
        });


        return parent::delete($ids);
    }

    /**
     * 获取编辑数据
     * 
     * @param mixed $id 数据ID
     * @return Model|Collection|Builder|array|null 页面数据
     */
    public function getEditData(mixed $id): Model|Collection|Builder|array|null
    {
        $data = parent::getEditData($id);

        $data->setAttribute('page', ['schema' => $data->schema]);
        $data->setAttribute('schema', '');

        return $data;
    }

    /**
     * 获取页面结构
     *
     * @param string $sign 页面标识
     * @return mixed 页面结构数据
     */
    public function get($sign): mixed
    {
        return cache()->rememberForever($this->cacheKeyPrefix . $sign, function () use ($sign) {
            return $this->query()->where('sign', $sign)->value('schema');
        });
    }

    /**
     * 获取选项列表
     * 
     * @return Collection|array 选项列表
     */
    public function options(): Collection|array
    {
        return $this->query()->get(['sign as value', 'title as label']);
    }
}