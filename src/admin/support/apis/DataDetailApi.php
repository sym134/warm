<?php

namespace warm\admin\support\apis;

use warm\admin\Admin;
use warm\admin\service\AdminService;
/**
 * 数据详情API
 * 
 * 处理数据详情请求的API类，继承自AdminBaseApi
 * 支持通过主键获取指定模型的单条数据记录详情
 */
class DataDetailApi extends AdminBaseApi
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
        return translator('admin.api_templates.data_detail');
    }

    /**
     * 处理数据详情请求
     * 
     * 使用AdminService的getDetail方法获取记录详情，并返回结果
     * 
     * @return \support\Response 响应结果
     */
    public function handle(): \support\Response
    {
        $data = $this->service()->getDetail(request()->input($this->getArgs('primary_key', 'id')));

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
            amis()->Select('model', translator('admin.relationships.model'))
                ->required()
                ->menuTpl('${label} <span class="text-gray-300 pl-2">${table}</span>')
                ->source('/dev_tools/relation/model_options')
                ->searchable(),
            amis()->InputText('primary_id', translator('admin.code_generators.primary_key'))->value('id'),
        ];
    }

    /**
     * 获取AdminService实例
     * 
     * 创建并配置AdminService实例，设置模型名称
     * 
     * @return AdminService AdminService实例
     */
    protected function service(): AdminService
    {
        $service = $this->blankService();

        $service->setModelName($this->getArgs('model'));

        return $service;
    }
}