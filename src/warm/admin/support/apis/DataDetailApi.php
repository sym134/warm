<?php

namespace warm\admin\support\apis;

use warm\admin\Admin;
use warm\admin\service\AdminService;
use warm\admin\support\apis\AdminBaseApi;

/**
 * 数据详情
 */
class DataDetailApi extends AdminBaseApi
{
    public string $method = 'get';

    public function getTitle(): string
    {
        return admin_trans('admin.api_templates.data_detail');
    }

    public function handle(): \support\Response
    {
        $data = $this->service()->getDetail(request()->input($this->getArgs('primary_key', 'id')));

        return Admin::response()->success($data);
    }

    public function argsSchema(): array
    {
        return [
            amis()->SelectControl('model', admin_trans('admin.relationships.model'))
                ->required()
                ->menuTpl('${label} <span class="text-gray-300 pl-2">${table}</span>')
                ->source('/dev_tools/relation/model_options')
                ->searchable(),
            amis()->TextControl('primary_id', admin_trans('admin.code_generators.primary_key'))->value('id'),
        ];
    }

    protected function service(): AdminService
    {
        $service = $this->blankService();

        $service->setModelName($this->getArgs('model'));

        return $service;
    }
}
