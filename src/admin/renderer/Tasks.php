<?php
namespace warm\admin\renderer;
/**
 * Tasks
 *
 * @see https://aisuda.bce.baidu.com/amis/zh-CN/components/tasks
 */
class Tasks extends BaseRenderer
{
    public string $type = 'tasks';

    /**
     * 外层 Dom 的类名
     *
     * @param string $value
     * @return self
     */
    public function className(string $value = ''): self
    {
        return $this->set('className', $value);
    }

    /**
     * table Dom 的类名
     *
     * @param string $value
     * @return self
     */
    public function tableClassName(string $value = ''): self
    {
        return $this->set('tableClassName', $value);
    }

    /**
     * 任务列表
     *
     * @param array $value
     * @return self
     */
    public function items(array $value = []): self
    {
        return $this->set('items', $value);
    }

    /**
     * 返回任务列表，返回的数据请参考 items。
     *
     * @param mixed $value
     * @return self
     */
    public function checkApi(mixed $value = null): self
    {
        return $this->set('checkApi', $value);
    }

    /**
     * 提交任务使用的 API
     *
     * @param mixed $value
     * @return self
     */
    public function submitApi(mixed $value = null): self
    {
        return $this->set('submitApi', $value);
    }

    /**
     * 如果任务失败，且可以重试，提交的时候会使用此 API
     *
     * @param mixed $value
     * @return self
     */
    public function reSubmitApi(mixed $value = null): self
    {
        return $this->set('reSubmitApi', $value);
    }

    /**
     * 当有任务进行中，会每隔一段时间再次检测，而时间间隔就是通过此项配置，默认 3s。
     *
     * @param int|float $value
     * @return self
     */
    public function interval(int|float $value = 3000): self
    {
        return $this->set('interval', $value);
    }

    /**
     * 任务名称列说明
     *
     * @param string $value
     * @return self
     */
    public function taskNameLabel(string $value = '任务名称'): self
    {
        return $this->set('taskNameLabel', $value);
    }

    /**
     * 操作列说明
     *
     * @param string $value
     * @return self
     */
    public function operationLabel(string $value = '操作'): self
    {
        return $this->set('operationLabel', $value);
    }

    /**
     * 状态列说明
     *
     * @param string $value
     * @return self
     */
    public function statusLabel(string $value = '状态'): self
    {
        return $this->set('statusLabel', $value);
    }

    /**
     * 备注列说明
     *
     * @param string $value
     * @return self
     */
    public function remarkLabel(string $value = '备注'): self
    {
        return $this->set('remarkLabel', $value);
    }

    /**
     * 操作按钮文字
     *
     * @param string $value
     * @return self
     */
    public function btnText(string $value = '上线'): self
    {
        return $this->set('btnText', $value);
    }

    /**
     * 重试操作按钮文字
     *
     * @param string $value
     * @return self
     */
    public function retryBtnText(string $value = '重试'): self
    {
        return $this->set('retryBtnText', $value);
    }

    /**
     * 配置容器按钮 className
     *
     * @param string $value
     * @return self
     */
    public function btnClassName(string $value = 'btn-sm btn-default'): self
    {
        return $this->set('btnClassName', $value);
    }

    /**
     * 配置容器重试按钮 className
     *
     * @param string $value
     * @return self
     */
    public function retryBtnClassName(string $value = 'btn-sm btn-danger'): self
    {
        return $this->set('retryBtnClassName', $value);
    }

    /**
     * 状态显示对应的类名配置
     *
     * @param array $value
     * @return self
     */
    public function statusLabelMap(array $value = []): self
    {
        return $this->set('statusLabelMap', $value);
    }

    /**
     * 状态显示对应的文字显示配置
     *
     * @param array $value
     * @return self
     */
    public function statusTextMap(array $value = []): self
    {
        return $this->set('statusTextMap', $value);
    }
}
