<?php

namespace warm\admin\support\apis;

use support\Response;
use warm\admin\support\apis\AdminBaseApi;

/**
 * 保存设置项API
 * 
 * 处理保存系统设置项请求的API类，继承自AdminBaseApi
 * 支持批量保存设置项
 */
class SaveSettingsApi extends AdminBaseApi
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
        return '保存设置项';
    }
    
    /**
     * 处理保存设置项请求
     * 
     * 使用warmConfig的adminSetMany方法批量保存请求中的所有设置项
     * 
     * @return Response 响应结果
     */
    public function handle(): Response
    {
        return systemConfig()->adminSetMany(request()->all());
    }
    
    /**
     * 定义接口参数表单结构
     * 
     * @return array 参数表单结构
     */
    public function argsSchema(): array
    {
        return [
            amis()->Markdown()->value('### 使用说明
- 接口请求方式为 `POST`
- 请求参数为数组格式 (将该api作为表单的提交api即可正常使用)
```JSON
{
    "site_name": "string",
    "name": "string",
    "age": 0
}
```'),
        ];
    }
}