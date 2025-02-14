<?php

namespace warm\admin\support\apis;

use support\Response;
use warm\admin\Admin;
use warm\admin\support\apis\AdminBaseApi;

class GetSettingsApi extends AdminBaseApi
{
    public string $method = 'get';
    public function getTitle(): string
    {
        return '获取设置项';
    }
    public function handle(): Response
    {
        $data = match ($this->getArgs('mode')) {
            'all'  => warmConfig()->all(),
            'part' => collect(warmConfig()->all())->filter(fn($_, $k) => in_array($k, $this->getArgs('keys')))->toArray(),
            'one'  => warmConfig()->get($this->getArgs('key')),
        };
        return Admin::response()->success($data);
    }
    public function argsSchema(): array
    {
        $allKeys = collect(warmConfig()->all())->keys()->map(fn($i) => [
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
