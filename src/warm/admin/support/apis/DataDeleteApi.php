<?php

namespace warm\admin\support\apis;

use support\Response;
use warm\admin\Admin;
use warm\admin\service\AdminService;
use warm\admin\support\apis\AdminBaseApi;

/**
 * 删除数据
 */
class DataDeleteApi extends AdminBaseApi
{
    public string $method = 'delete';

    public function getTitle(): string
    {
        return admin_trans('admin.api_templates.data_delete');
    }

    public function handle(): Response
    {
        $result = $this->service()->delete(request()->input($this->getArgs('primary_key', 'ids')));

        if ($result) {
            return Admin::response()
                ->successMessage(admin_trans('admin.successfully_message', ['attribute' => admin_trans('admin.delete')]));
        }

        return Admin::response()->fail(admin_trans('admin.failed_message', ['attribute' => admin_trans('admin.delete')]));
    }

    public function argsSchema(): array
    {
        return [
            amis()->SelectControl('model', admin_trans('admin.relationships.model'))
                ->required()
                ->menuTpl('${label} <span class="text-gray-300 pl-2">${table}</span>')
                ->source('/dev_tools/relation/model_options')
                ->searchable(),
            amis()->TextControl('primary_id', admin_trans('admin.code_generators.primary_key'))->value('ids'),
        ];
    }

    protected function service(): AdminService
    {
        $service = $this->blankService();

        $service->setModelName($this->getArgs('model'));

        return $service;
    }
}
