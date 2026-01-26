<?php

namespace warm\admin\trait;

use support\Response;

/**
 * 导出Trait
 * 
 * 提供数据导出功能，支持将数据导出为Excel文件
 * 使用FastExcel库实现数据导出功能
 */
trait ExportTrait
{
    /**
     * 导出数据
     *
     * @return Response 响应对象
     */
    protected function export(): Response
    {
        admin_abort_if(!class_exists('\Rap2hpoutre\FastExcel\FastExcel'), translator('admin.export.please_install_excel'));

        // 默认在 storage/app/ 下
        $path = 'resource/' . sprintf('%s-%s.xlsx', $this->exportFileName(), date('YmdHis'));

        // 导出本页和导出选中项都是通过 _ids 查询
        $ids = request()->input('_ids');

        // listQuery() 为列表查询条件，与获取列表数据一致
        $query = $this->service->listQuery()
            ->when($ids, fn($query) => $query->whereIn($this->service->getModel()->getTable() . '.' . $this->service->primaryKey(), explode(',', $ids)));
        try {
            (new \Rap2hpoutre\FastExcel\FastExcel($query->get()))->export(base_path($path), fn($row) => $this->exportMap($row));
        } catch (\Throwable $e) {
            admin_abort(translator('admin.action_failed'));
        }

        return $this->response()->success(compact('path'));
    }

    /**
     * 导出数据映射处理
     *
     * @param mixed $row 数据行
     * @return mixed 处理后的数据行
     */
    protected function exportMap($row): mixed
    {
        return $row;
    }

    /**
     * 导出文件名
     *
     * @return string 导出文件名
     */
    protected function exportFileName(): string
    {
        return strtolower(str_replace('Controller', '', class_basename($this)));
    }
}