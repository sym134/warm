<?php

namespace warm\admin\trait;

use Illuminate\Support\Str;

/**
 * 查询路径Trait
 * 
 * 提供各种操作路径的生成方法，包括列表、创建、编辑、删除等操作的API路径
 * 用于生成Admin系统中各种操作对应的URL路径
 */
trait QueryPathTrait
{
    /**
     * 列表获取数据路径
     *
     * @return string 列表获取数据的API路径
     */
    public function getListGetDataPath(): string
    {
        return admin_url($this->queryPath . '?_action=getData');
    }

    /**
     * 导出路径
     *
     * @return string 导出数据的API路径
     */
    public function getExportPath(): string
    {
        return admin_url($this->queryPath . '?_action=export', true);
    }

    /**
     * 删除路径
     *
     * @return string 删除数据的API路径
     */
    public function getDeletePath(): string
    {
        $primaryKey = isset($this->service) ? $this->service->primaryKey() : 'id';

        return 'delete:' . admin_url($this->queryPath . '/${' . $primaryKey . '}');
    }

    /**
     * 批量删除路径
     *
     * @return string 批量删除数据的API路径
     */
    public function getBulkDeletePath(): string
    {
        return 'delete:' . admin_url($this->queryPath . '/${ids}');
    }

    /**
     * 编辑页面路径
     *
     * @return string 编辑页面的路由路径
     */
    public function getEditPath(): string
    {
        return '/' . trim($this->queryPath, '/') . '/${' . $this->service->primaryKey() . '}/edit';
    }

    /**
     * 编辑页面获取数据路径
     *
     * @return string 编辑页面获取数据的API路径
     */
    public function getEditGetDataPath(): string
    {
        $path = $this->queryPath;

        $last = collect(explode('/', $path))->last();

        if ($last != 'edit') {
            $primaryKey = isset($this->service) ? $this->service->primaryKey() : 'id';

            $path .= '/${' . $primaryKey . '}/edit';
        }

        return admin_url($path . '?_action=getData');
    }

    /**
     * 详情页面路径
     *
     * @return string 详情页面的路由路径
     */
    public function getShowPath(): string
    {
        return '/' . trim($this->queryPath, '/') . '/${' . $this->service->primaryKey() . '}';
    }

    /**
     * 更新数据路径
     *
     * @return string 更新数据的API路径
     */
    public function getUpdatePath(): string
    {
        $path = $this->queryPath;

        $last = collect(explode('/', $path))->last();

        if ($last == 'edit') {
            $path = str_replace('/edit', '', $path);
        } else {
            $primaryKey = isset($this->service) ? $this->service->primaryKey() : 'id';

            $path .= '/${' . $primaryKey . '}';
        }

        return 'put:' . admin_url($path);
    }

    /**
     * 快速编辑路径
     *
     * @return string 快速编辑数据的API路径
     */
    public function getQuickEditPath(): string
    {
        return $this->getStorePath() . '?_action=quickEdit';
    }

    /**
     * 快速编辑单项路径
     *
     * @return string 快速编辑单项数据的API路径
     */
    public function getQuickEditItemPath(): string
    {
        return $this->getStorePath() . '?_action=quickEditItem';
    }

    /**
     * 详情页面获取数据路径
     *
     * @return string 详情页面获取数据的API路径
     */
    public function getShowGetDataPath(): string
    {
        $path = $this->queryPath;
        if (blank(request()->action)) {
            $path .= '/${' . $this->service->primaryKey() . '}';
        }

        return admin_url($path . '?_action=getData');
    }

    /**
     * 新增页面路径
     *
     * @return string 新增页面的路由路径
     */
    public function getCreatePath(): string
    {
        return '/' . trim($this->queryPath, '/') . '/create';
    }

    /**
     * 新增数据路径
     *
     * @return string 新增数据的API路径
     */
    public function getStorePath(): string
    {
        return 'post:' . admin_url(str_replace('/create', '', $this->queryPath));
    }

    /**
     * 列表页面路径
     *
     * @return string 列表页面的路由路径
     */
    public function getListPath(): string
    {
        $path = $this->queryPath;

        if (Str::contains($this->queryPath, '/create')) {
            $path = str_replace('/create', '', $path);
        }

        if (Str::contains($this->queryPath, '/edit')) {
            $_path = explode('/', $path);
            array_pop($_path);
            array_pop($_path);
            $path = implode('/', $_path);
        }

        return '/' . trim($path, '/');
    }

    /**
     * 发布路径（自定义操作路径）
     *
     * @return string 发布操作的API路径
     */
    public function getPublishPath(): string
    {
        $path = $this->queryPath;

        // 清理路径中的 create 或 edit
        if (Str::contains($path, '/create')) {
            $path = str_replace('/create', '', $path);
        }

        if (Str::contains($path, '/edit')) {
            $_path = explode('/', $path);
            array_pop($_path);
            array_pop($_path);
            $path = implode('/', $_path);
        }

        return 'post:' . admin_url($path . '/publish');
    }
}