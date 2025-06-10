<?php

namespace warm\admin\support\apis;

use support\Response;
use warm\admin\Admin;
use warm\admin\service\AdminService;
use warm\admin\support\apis\AdminBaseApi;

/**
 * 数据更新
 */
class DataUpdateApi extends AdminBaseApi
{
    public string $method = 'put';

    public function getTitle(): string
    {
        return translator('admin.api_templates.data_update');
    }

    public function handle(): Response
    {
        $result = $this->service()->update(request()->input($this->getArgs('primary_key', 'id')), request()->all());

        if ($result) {
            return Admin::response()
                ->successMessage(translator('admin.successfully_message', ['attribute' => translator('admin.save')]));
        }

        return Admin::response()->fail(translator('admin.failed_message', ['attribute' => translator('admin.save')]));
    }

    public function argsSchema(): array
    {
        return [
            amis()->SelectControl('model', translator('admin.relationships.model'))
                ->required()
                ->menuTpl('${label} <span class="text-gray-300 pl-2">${table}</span>')
                ->source('/dev_tools/relation/model_options')
                ->searchable(),
            amis()->TextControl('primary_id', translator('admin.code_generators.primary_key'))->value('id'),
        ];
    }

    protected function service(): AdminService
    {
        $service = $this->blankService();

        $service->setModelName($this->getArgs('model'));

        return $service;
    }
}
