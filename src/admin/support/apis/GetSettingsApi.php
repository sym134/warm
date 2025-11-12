<?php

namespace warm\admin\support\apis;

use support\Response;
use warm\admin\Admin;

/**
 * 获取设置项API
 * 
 * 处理获取系统设置项请求的API类，继承自AdminBaseApi
 * 支持获取所有、部分或单个设置项
 */
class GetSettingsApi extends AdminBaseApi
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
        return '获取设置项';
    }
    
    /**
     * 处理获取设置项请求
     * 
     * 根据参数中的mode值决定获取所有、部分还是单个设置项
     * 
     * @return Response 响应结果
     */
    public function handle(): Response
    {
        $data = match ($this->getArgs('mode')) {
            'all'  => systemConfig()->all(),
            'part' => collect(systemConfig()->all())->filter(fn($_, $k) => in_array($k, $this->getArgs('keys')))->toArray(),
            'one'  => systemConfig()->get($this->getArgs('key')),
        };
        return Admin::response()->success($data);
    }
    
    /**
     * 定义接口参数表单结构
     * 
     * @return array 参数表单结构
     */
    public function argsSchema(): array
    {
        $allKeys = collect(systemConfig()->all())->keys()->map(fn($i) => [
            'value' => $i,
            'label' => $i,
        ])->toArray();
        return [
            amis()->RadiosControl('mode', '获取模式')->options([
                ['value' => 'all', 'label' => '所有'],
                ['value' => 'part', 'label' => '部分'],
                ['value' => 'one', 'label' => '单个'],
            ])->selectFirst(),
            amis()->TextControl('key', '设置项')->required()->visibleOn('${mode == "one"}')->options($allKeys),
            amis()->ArrayControl('keys', '设置项')->required()->visibleOn('${mode == "part"}')->items([
                amis()->TextControl('value')->required()->options($allKeys),
            ]),
        ];
    }
}