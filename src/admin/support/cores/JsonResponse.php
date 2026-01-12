<?php

namespace warm\admin\support\cores;

use support\Response;
use warm\admin\support\SqlRecord;

/**
 * JSON响应处理类
 * 
 * 用于生成统一格式的JSON响应，包括成功和失败情况的处理
 * 提供消息提示、额外参数等配置功能
 */
class JsonResponse
{
    /** @var array 额外参数 */
    private array $additionalData = [
        'status' => 0,
        'msg' => '',
        'doNotDisplayToast' => 0,
    ];

    /**
     * 失败响应
     * 
     * @param string $message 错误消息
     * @param mixed $data 响应数据
     * @return Response 响应对象
     */
    public function fail(string $message = 'Service error', $data = null): Response
    {
        $this->setFailMsg($message);

        return $this->json($data);
    }

    /**
     * 成功响应
     * 
     * @param mixed $data 响应数据
     * @param string $message 成功消息
     * @return Response 响应对象
     */
    public function success($data = null, string $message = ''): Response
    {
        $this->setSuccessMsg($message);

        // if ($data instanceof JsonResource) {
        //     return $data->additional($this->additionalData)->response();
        // }

        if ($data === null) {
            $data = (object)$data;
        }

        return $this->json($data);
    }

    /**
     * 生成JSON响应
     * 
     * @param mixed $data 响应数据
     * @return Response 响应对象
     */
    private function json(mixed $data): Response
    {
        if (config('app.debug')) {
            $this->additionalData['_debug'] = [
                'sql' => SqlRecord::$sql,
            ];
            SqlRecord::clear(); // 清空sql记录
        }
        return json(array_merge($this->additionalData, ['data' => $data]));
    }

    /**
     * 成功消息响应
     * 
     * @param string $message 成功消息
     * @return Response 响应对象
     */
    public function successMessage(string $message = ''): Response
    {
        return $this->success([], $message);
    }

    /**
     * 设置成功消息
     * 
     * @param string $message 成功消息
     * @return void
     */
    private function setSuccessMsg($message): void
    {
        $this->additionalData['msg'] = $message;
    }

    /**
     * 设置失败消息
     * 
     * @param string $message 失败消息
     * @return void
     */
    private function setFailMsg($message): void
    {
        $this->additionalData['msg'] = $message;
        $this->additionalData['status'] = 1;
    }

    /**
     * 配置弹框时间 (ms)
     * 
     * @param int $timeout 超时时间（毫秒）
     * @return $this 返回当前实例以支持链式调用
     */
    public function setMsgTimeout($timeout): static
    {
        return $this->additional(['msgTimeout' => $timeout]);
    }

    /**
     * 添加额外参数
     * 
     * @param array $params 额外参数
     * @return $this 返回当前实例以支持链式调用
     */
    public function additional(array $params = []): static
    {
        $this->additionalData = array_merge($this->additionalData, $params);

        return $this;
    }

    /**
     * 不显示弹框
     * 
     * @param int $value 值（默认为1表示不显示）
     * @return $this 返回当前实例以支持链式调用
     */
    public function doNotDisplayToast($value = 1): static
    {
        $this->additionalData['doNotDisplayToast'] = $value;

        return $this;
    }
}