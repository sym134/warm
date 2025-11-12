<?php

namespace warm\admin\support\apis;

use support\Response;
use warm\admin\Admin;
use warm\admin\service\AdminService;

/**
 * 数据更新API
 * 
 * 处理数据更新请求的API类，继承自AdminBaseApi
 * 支持通过主键更新指定模型的数据记录
 */
class DataUpdateApi extends AdminBaseApi
{
    /** @var string 请求方法类型 */
    public string $method = 'put';

    /**
     * 获取接口标题
     * 
     * @return string 接口标题
     */
    public function getTitle(): string
    {
        return translator('admin.api_templates.data_update');
    }

    /**
     * 处理数据更新请求
     * 
     * 使用AdminService的update方法更新记录，并返回操作结果
     * 
     * @return Response 响应结果
     */
    public function handle(): Response
    {
        $result = $this->service()->update(request()->input($this->getArgs('primary_key', 'id')), request()->all());

        if ($result) {
            return Admin::response()
                ->successMessage(translator('admin.successfully_message', ['attribute' => translator('admin.save')]));
        }

        return Admin::response()->fail(translator('admin.failed_message', ['attribute' => translator('admin.save')]));
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
            amis()->TextControl('primary_id', translator('admin.code_generators.primary_key'))->value('id'),
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