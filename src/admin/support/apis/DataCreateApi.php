<?php

namespace warm\admin\support\apis;

use support\Response;
use warm\admin\Admin;
use warm\admin\service\AdminService;

/**
 * 数据新增API
 * 
 * 处理数据新增请求的API类，继承自AdminBaseApi
 * 支持通过指定模型创建新记录
 */
class DataCreateApi extends AdminBaseApi
{
    /** @var string 请求方法类型 */
    public string $method = 'post';

    /**
     * 获取接口标题
     * 
     * @return string 接口标题
     */
    public function getTitle(): string
    {
        return translator('admin.api_templates.data_create');
    }

    /**
     * 处理数据新增请求
     * 
     * 使用AdminService的store方法创建新记录，并返回操作结果
     * 
     * @return Response 响应结果
     */
    public function handle(): Response
    {
        $result = $this->service()->store(request()->all());

        if ($result) {
            return Admin::response()
                ->successMessage(translator('admin.successfully_message', ['attribute' => translator('admin.create')]));
        }

        return Admin::response()->fail(translator('admin.failed_message', ['attribute' => translator('admin.create')]));
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