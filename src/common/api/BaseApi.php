<?php

namespace warm\common\api;

/**
 * 微信 API 基础类
 * 
 * 提供所有微信 API 类的通用功能
 */
abstract class BaseApi
{

    /**
     * 处理 API 响应
     * 
     * @param mixed $response API 响应
     * @return array
     */
    protected function handleResponse($response): array
    {
        if (is_array($response)) {
            return $response;
        }

        if (is_object($response) && method_exists($response, 'toArray')) {
            return $response->toArray();
        }

        return ['data' => $response];
    }

    /**
     * 检查 API 响应是否成功
     * 
     * @param array $response API 响应
     * @return bool
     */
    public function isSuccess(array $response): bool
    {
        // easywechat 通常返回 errcode 字段，0 表示成功
        if (isset($response['errcode'])) {
            return $response['errcode'] === 0;
        }

        // 如果没有 errcode，认为成功
        return true;
    }

    /**
     * 获取错误信息
     * 
     * @param array $response API 响应
     * @return string
     */
    public function getErrorMessage(array $response): string
    {
        if (isset($response['errmsg'])) {
            return $response['errmsg'];
        }

        if (isset($response['errcode'])) {
            return "错误代码: {$response['errcode']}";
        }

        return '未知错误';
    }
}
