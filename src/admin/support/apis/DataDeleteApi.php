<?php

namespace warm\admin\support\apis;

use support\Response;
use warm\admin\Admin;
use warm\admin\service\AdminService;

/**
 * 数据删除API
 * 
 * 处理数据删除请求的API类，继承自AdminBaseApi
 * 支持通过主键删除指定模型的数据记录
 */
class DataDeleteApi extends AdminBaseApi
{
    /** @var string 请求方法类型 */
    public string $method = 'delete';

    /**
     * 获取接口标题
     * 
     * @return string 接口标题
     */
    public function getTitle(): string
    {
        return translator('admin.api_templates.data_delete');
    }

    /**
     * 处理数据删除请求
     * 
     * 使用AdminService的delete方法删除记录，并返回操作结果
     * 
     * @return Response 响应结果
     */
    public function handle(): Response
    {
        $result = $this->service()->delete(request()->input($this->getArgs('primary_key', 'ids')));

        if ($result) {
            return Admin::response()
                ->successMessage(translator('admin.successfully_message', ['attribute' => translator('admin.delete')]));
        }

        return Admin::response()->fail(translator('admin.failed_message', ['attribute' => translator('admin.delete')]));
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
            amis()->InputText('primary_id', translator('admin.code_generators.primary_key'))->value('ids'),
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