<?php

namespace warm\admin\support\apis;

use support\Response;
use warm\admin\Admin;
use warm\admin\service\AdminService;
use warm\admin\support\apis\AdminBaseApi;

/**
 * 数据新增
 */
class DataCreateApi extends AdminBaseApi
{
    public string $method = 'post';

    public function getTitle(): string
    {
        return admin_trans('admin.api_templates.data_create');
    }

    public function handle(): Response
    {
        $result = $this->service()->store(request()->all());

        if ($result) {
            return Admin::response()
                ->successMessage(admin_trans('admin.successfully_message', ['attribute' => admin_trans('admin.create')]));
        }

        return Admin::response()->fail(admin_trans('admin.failed_message', ['attribute' => admin_trans('admin.create')]));
    }

    public function argsSchema(): array
    {
        return [
            amis()->SelectControl('model', admin_trans('admin.relationships.model'))
                ->required()
                ->menuTpl('${label} <span class="text-gray-300 pl-2">${table}</span>')
                ->source('/dev_tools/relation/model_options')
                ->searchable(),
        ];
    }

    protected function service(): AdminService
    {
        $service = $this->blankService();

        $service->setModelName($this->getArgs('model'));

        return $service;
    }
}
