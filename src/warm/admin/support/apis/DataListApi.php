<?php

namespace warm\admin\support\apis;

use support\Response;
use warm\admin\Admin;
use warm\admin\service\AdminService;
use warm\admin\support\apis\AdminBaseApi;

/**
 * 数据列表API
 * 
 * 处理数据列表请求的API类，继承自AdminBaseApi
 * 支持获取指定模型的数据列表
 */
class DataListApi extends AdminBaseApi
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
        return translator('admin.api_templates.data_list');
    }

    /**
     * 处理数据列表请求
     * 
     * 使用AdminService的list方法获取数据列表，并返回结果
     * 
     * @return Response 响应结果
     */
    public function handle(): Response
    {
        return Admin::response()->success($this->service()->list());
    }

    /**
     * 定义接口参数表单结构
     * 
     * @return array 参数表单结构
     */
    public function argsSchema(): array
    {
        return [
            amis()->SelectControl('model', translator('admin.relationships.model'))
                ->required()
                ->menuTpl('${label} <span class="text-gray-300 pl-2">${table}</span>')
                ->source('/dev_tools/relation/model_options')
                ->searchable(),
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