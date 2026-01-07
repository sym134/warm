<?php

namespace warm\admin\support\apis;

use support\Response;
use warm\admin\Admin;

/**
 * 获取选项列表API
 * 
 * 处理获取选项列表请求的API类，继承自AdminBaseApi
 * 支持根据指定模型和字段生成选项列表
 */
class OptionsApi extends AdminBaseApi
{
    /** @var string 请求方法类型 */
    public string $method = 'get';
    
    /**
     * 获取接口标题
     * 
     * @return string 接口标题
     */
    public function getTitle(): string
    {
        return '获取选项列表';
    }
    
    /**
     * 处理获取选项列表请求
     * 
     * 根据参数中的模型和字段设置，查询数据并返回value/label格式的选项列表
     * 
     * @return Response 响应结果
     */
    public function handle(): Response
    {
        $data = $this->service()->query()->get([
            $this->getArgs('value_field') . ' as value',
            $this->getArgs('label_field') . ' as label',
        ]);
        return Admin::response()->success($data);
    }
    
    /**
     * 定义接口参数表单结构
     * 
     * @return array 参数表单结构
     */
    public function argsSchema(): array
    {
        return [
            amis()
                ->Select('model', translator('admin.relationships.model'))
                ->required()
                ->menuTpl('${label} <span class="text-gray-300 pl-2">${table}</span>')
                ->source('/dev_tools/relation/model_options')
                ->searchable(),
            amis()
                ->InputText('value_field', 'Value 字段')
                ->source('/dev_tools/relation/column_options?model=${model}'),
            amis()
                ->InputText('label_field', 'Label 字段')
                ->source('/dev_tools/relation/column_options?model=${model}'),
        ];
    }
    
    /**
     * 获取AdminService实例
     * 
     * 创建并配置AdminService实例，设置模型名称
     * 
     * @return mixed AdminService实例
     */
    protected function service(): mixed
    {
        $service = $this->blankService();
        $service->setModelName($this->getArgs('model'));
        return $service;
    }
}