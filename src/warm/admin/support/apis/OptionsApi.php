<?php

namespace warm\admin\support\apis;

use support\Response;
use warm\admin\Admin;
use warm\admin\support\apis\AdminBaseApi;
use function warm\support\apis\__;

class OptionsApi extends AdminBaseApi
{
    public string $method = 'get';
    public function getTitle(): string
    {
        return '获取选项列表';
    }
    public function handle(): Response
    {
        $data = $this->service()->query()->get([
            $this->getArgs('value_field') . ' as value',
            $this->getArgs('label_field') . ' as label',
        ]);
        return Admin::response()->success($data);
    }
    public function argsSchema(): array
    {
        return [
            amis()
                ->SelectControl('model', __('admin.relationships.model'))
                ->required()
                ->menuTpl('${label} <span class="text-gray-300 pl-2">${table}</span>')
                ->source('/dev_tools/relation/model_options')
                ->searchable(),
            amis()
                ->TextControl('value_field', 'Value 字段')
                ->source('/dev_tools/relation/column_options?model=${model}'),
            amis()
                ->TextControl('label_field', 'Label 字段')
                ->source('/dev_tools/relation/column_options?model=${model}'),
        ];
    }
    protected function service()
    {
        $service = $this->blankService();
        $service->setModelName($this->getArgs('model'));
        return $service;
    }
}
