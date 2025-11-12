<?php

namespace warm\admin\trait;

/**
 * 错误信息Trait类
 * 
 * 提供错误信息处理功能，包括设置、获取和检查错误信息
 * 用于在服务类或其他组件中统一处理错误状态
 */
trait ErrorTrait
{
    /**
     * 错误信息
     *
     * @var string 错误信息内容
     */
    protected string $error = '';

    /**
     * 设置错误信息
     *
     * @param string $error 错误信息
     * @return bool 返回false表示操作失败
     */
    protected function setError(string $error): bool
    {
        $this->error = $error ?: translator('admin.unknown_error');
        return false;
    }

    /**
     * 获取错误信息
     *
     * @return string 错误信息内容
     */
    public function getError(): string
    {
        return $this->error;
    }

    /**
     * 是否存在错误信息
     *
     * @return bool 如果存在错误信息返回true，否则返回false
     */
    public function hasError(): bool
    {
        return !empty($this->error);
    }
}