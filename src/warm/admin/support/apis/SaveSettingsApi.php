<?php

namespace warm\admin\support\apis;

use support\Response;
use warm\admin\support\apis\AdminBaseApi;

class SaveSettingsApi extends AdminBaseApi
{
    public string $method = 'post';
    public function getTitle(): string
    {
        return '保存设置项';
    }
    public function handle(): Response
    {
        return warmConfig()->adminSetMany(request()->all());
    }
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
